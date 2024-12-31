<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Coupon;
use App\Models\PaymentMethod;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductVariant;
use App\Models\ActionLog;
use App\Models\Invoice;
use App\Models\Address as AppAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Iyzipay\Model\Payment;
use Iyzipay\Model\PaymentCard;
use Iyzipay\Options;
use Iyzipay\Request\CreatePaymentRequest;
use Iyzipay\Model\Locale;
use Iyzipay\Model\Currency;
use Iyzipay\Model\PaymentChannel;
use Iyzipay\Model\PaymentGroup;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\BasketItemType;
use Iyzipay\Request\RetrievePaymentRequest;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->latest()->get();
        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access.');
        }

        $deliveryAddress = Auth::user()->addresses()->find($order->address_id);
        $paymentMethod = PaymentMethod::find($order->payment_method_id);
        $invoice = $order->invoice;

        return view('orders.show', compact('order', 'deliveryAddress', 'paymentMethod', 'invoice'));
    }

    public function store(Request $request)
    {
        $cart = Auth::user()->cart;

        if (!$cart || $cart->items->isEmpty()) {
            return back()->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();

        try {
            $coupon = $this->validateAndApplyCoupon($cart);
            $order = $this->createOrder($cart, $request, $coupon);
            $address = AppAddress::findOrFail($request->address_id);

            $this->createOrderDetails($order, $cart);

            $paymentMethod = PaymentMethod::findOrFail($request->payment_method_id);

            if ($paymentMethod->name === 'Online Payment') {
                $cardInfo = $request->only(['card_name', 'card_number', 'expiry_month', 'expiry_year', 'cvc']);
                $paymentResult = $this->processIyzicoPayment($order, $cart, $cardInfo, $address);

                // dd($paymentResult);

                Log::info('Iyzico Payment Result', [
                    'detailed' => $paymentResult->getStatus(),
                    'conversationId' => $paymentResult->getConversationId(),
                    'paymentId' => $paymentResult->getPaymentId(),
                    'errorCode' => $paymentResult->getErrorCode(),
                    'errorMessage' => $paymentResult->getErrorMessage(),
                    'rawResult' => $paymentResult->getRawResult()
                ]);

                if ($paymentResult->getStatus() != 'success') {
                    throw new \Exception('Payment failed: ' . $paymentResult->getErrorMessage());
                }
                
                $order->update([
                    'payment_id' => $paymentResult->getPaymentId(),
                    'payment_status' => 'paid',
                    // 'status' => 'processing',
                ]);

                // dd($paymentResult);

                $this->createInvoice($order, $paymentResult);
            }

            $cart->items()->delete();
            $cart->delete();

            DB::commit();

            $this->logAction('create', 'order', 'success', 'Order placed successfully. Order ID: ' . $order->id);

            return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($coupon)) {
                $coupon->decrement('used_count');
            }
            return back()->with('error', 'Failed to place order. Please try again or contact support. ' . $e->getMessage());
        }
    }

    private function validateAndApplyCoupon($cart)
    {
        if (!$cart->coupon_id) {
            return null;
        }

        $coupon = Coupon::find($cart->coupon_id);
        if (!$coupon) {
            return null;
        }

        if ($coupon->used_count >= $coupon->usage_limit) {
            throw new \Exception('This coupon has reached its maximum usage limit.');
        }

        if (!$coupon->is_active) {
            throw new \Exception('This coupon is not active anymore.');
        }

        $coupon->increment('used_count');
        return $coupon;
    }

    private function createOrder($cart, $request, $coupon)
    {
        return Order::create([
            'user_id' => Auth::id(),
            'payment_method_id' => $request->payment_method_id,
            'address_id' => $request->address_id,
            'used_coupon' => $coupon 
                ? ($coupon->type == 'fixed' 
                    ? "{$coupon->code} -> {$coupon->value}$" 
                    : "{$coupon->code} -> {$coupon->value}%")
                : null,
            'order_number' => 'ORDER-'.Auth::id().'-'.uniqid().'-'.time(),
            'total_amount' => $cart->total,
            'status' => 'pending',
            'notes' => $request->notes,
        ]);
    }

    private function createOrderDetails($order, $cart)
    {
        foreach ($cart->items as $item) {
            OrderDetail::create([
                'order_id' => $order->id,
                'product_variant_id' => $item->product_variant_id,
                'quantity' => $item->quantity,
                'price' => $item->total_price,
            ]);

            $productVariant = ProductVariant::findOrFail($item->product_variant_id);
            if ($productVariant->stock) {
                $productVariant->stock->decrement('quantity', $item->quantity);
            }
        }
    }

    private function processIyzicoPayment($order, $cart, $cardInfo, $address)
    {
        $cart->load('items.productVariant.product.categories');
        $items = $cart->items()->with('productVariant.product.categories')->get();

        $options = new Options();
        $options->setApiKey(env('IYZI_SANDBOX_API_KEY'));
        $options->setSecretKey(env('IYZI_SANDBOX_SECRET_KEY'));
        $options->setBaseUrl(env('IYZI_SANDBOX_BASE_URL'));
        
        $request = new CreatePaymentRequest();
        $request->setLocale(Locale::TR);
        $request->setConversationId($order->order_number);
        $request->setPrice(number_format($cart->subtotal, 2, '.', ''));
        $request->setPaidPrice(number_format($cart->total, 2, '.', ''));
        $request->setCurrency(Currency::TL);
        $request->setInstallment(1);
        $request->setBasketId($order->id);
        $request->setPaymentChannel(PaymentChannel::WEB);
        $request->setPaymentGroup(PaymentGroup::PRODUCT);

        $paymentCard = new PaymentCard();
        $paymentCard->setCardHolderName($cardInfo['card_name']);
        $paymentCard->setCardNumber($cardInfo['card_number']);
        $paymentCard->setExpireMonth($cardInfo['expiry_month']);
        $paymentCard->setExpireYear($cardInfo['expiry_year']);
        $paymentCard->setCvc($cardInfo['cvc']);
        $paymentCard->setRegisterCard(0);
        $request->setPaymentCard($paymentCard);

        $buyer = new Buyer();
        $buyer->setId((string)Auth::id());
        $buyer->setName(auth()->user()->name);
        $buyer->setSurname(auth()->user()->surname ?? 'xxx');
        $buyer->setGsmNumber(auth()->user()->tel_no);
        $buyer->setEmail(auth()->user()->email);
        $buyer->setIdentityNumber("00000000000");
        $buyer->setLastLoginDate("2015-10-05 12:43:35");
        $buyer->setRegistrationDate("2013-04-21 15:12:09");
        $buyer->setRegistrationAddress($address->full_address);
        $buyer->setIp(request()->ip());
        $buyer->setZipCode('0');
        $buyer->setCity($address->city);
        $buyer->setCountry($address->country);
        $request->setBuyer($buyer);

        $shippingAddress = new Address();
        $shippingAddress->setContactName(auth()->user()->name . ' ' . (auth()->user()->surname ?? ''));
        $shippingAddress->setCity($address->city);
        $shippingAddress->setCountry($address->country);
        $shippingAddress->setAddress($address->full_address);
        $shippingAddress->setZipCode("0");
        $request->setShippingAddress($shippingAddress);

        $billingAddress = new Address();
        $billingAddress->setContactName(auth()->user()->name . ' ' . (auth()->user()->surname ?? ''));
        $billingAddress->setCity($address->city);
        $billingAddress->setCountry($address->country);
        $billingAddress->setAddress($address->full_address);
        $billingAddress->setZipCode("0");
        $request->setBillingAddress($billingAddress);

        $basketItems = [];
        foreach ($items as $item) {
            $basketItem = new BasketItem();
            $basketItem->setId($item->id);
            $basketItem->setName($item->first()->productVariant->product->name);
            $basketItem->setCategory1($item->first()->productVariant->product->categories->first()->name);
            $basketItem->setCategory2('');
            $basketItem->setItemType(BasketItemType::PHYSICAL);
            $basketItem->setPrice(number_format($item->total_price, 2, '.', ''));
            $basketItems[] = $basketItem;
        }
        $request->setBasketItems($basketItems);

        // dd($request);

        $payment = Payment::create($request, $options);

        $paymentId = $payment->getPaymentId();
        $paymentConversationId = $payment->getConversationId();

        // dd($payment);

        $retrievePaymentRequest = new RetrievePaymentRequest();
        $retrievePaymentRequest->setLocale('tr');
        $retrievePaymentRequest->setConversationId($paymentConversationId);
        $retrievePaymentRequest->setPaymentId($paymentId);
        $retrievePaymentRequest->setPaymentConversationId($paymentConversationId);

        // dd($retrievePaymentRequest);
        
        Payment::retrieve($retrievePaymentRequest, $options);

        return $payment;
    }

    private function createInvoice($order, $paymentResult)
    {
        Invoice::create([
            'order_id' => $order->id,
            'invoice_number' => 'INV-' . $order->order_number,
            'total_amount' => $order->total_amount,
            'status' => 'paid',
            'payment_id' => $paymentResult->getPaymentId(),
            'payment_details' => json_encode($paymentResult->getRawResult()),
        ]);
    }

    public function updateOrderStatus($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status == 'pending') {
            $order->status = 'processing';
            $order->save();

            $this->logAction('update', 'order', 'success', 'Order status updated to processing. Order ID: ' . $order->id);

            return back()->with('success', 'Order is now being processed.');
        }

        return back()->with('error', 'Invalid order status.');
    }

    public function finalizeOrderStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
            'message' => 'nullable|string|max:255',
        ]);

        $order = Order::findOrFail($id);

        $status = $validated['status'];
        $message = $validated['message'];

        $order->status = $status;
        $order->save();

        if ($status == 'cancelled') {
            foreach ($order->details as $detail) {
                $productVariant = ProductVariant::find($detail->product_variant_id);
                if ($productVariant && $productVariant->stock) {
                    $productVariant->stock->increment('quantity', $detail->quantity);
                }
            }
        }

        $notificationMessage = $status == 'completed'
            ? 'Congratulations on your purchase! Your order ' . $order->order_number . ' has been completed. ' . $message
            : 'We are sorry to inform you that your order ' . $order->order_number . ' has been cancelled. ' . $message;

        $notification = Notification::create([
            'from' => 'System',
            'title' => 'Order Status of -> ' . $order->order_number,
            'message' => $notificationMessage,
        ]);
        $notification->users()->attach(auth()->user()->id);

        $this->logAction('update', 'order', 'success', 'Order status updated to ' . $status . '. Order ID: ' . $order->id);

        return back()->with('success', 'Order status updated and notification sent.');
    }

    private function logAction($action, $target, $status, $details)
    {
        ActionLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'target' => $target,
            'status' => $status,
            'ip_address' => request()->ip(),
            'details' => $details,
        ]);
    }
}