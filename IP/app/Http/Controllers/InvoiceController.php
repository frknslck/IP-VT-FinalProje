<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show(Invoice $invoice)
    {
        $order = Order::where('id', $invoice->order_id)->first();

        // dd($order->details);

        return view('invoices.show', compact('invoice', 'order'));
    }
}
