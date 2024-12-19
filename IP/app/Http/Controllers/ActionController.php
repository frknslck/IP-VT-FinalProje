<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Campaign;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\RequestComplaint;
use App\Models\Category;
use App\Models\Order;
use App\Models\User;

class ActionController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $actions = $user->roles->flatMap->actions->unique('id');
        $selectedActionId = $request->input('action_id');
        $viewData = null;
        $orders = Order::select('id', 'total_amount', 'created_at')->get()->toJson();

        if ($selectedActionId) {
            switch ($selectedActionId) {
                case 1;
                    $categories = Category::with('parent')->get();
                    $viewData = view('action-panel.partials.category', compact('categories'))->render();
                    break;
                case 2:
                    $campaigns = Campaign::all();
                    $viewData = view('action-panel.partials.campaign', compact('campaigns'))->render();
                    break;
                case 3:
                    $coupons = Coupon::all();
                    $viewData = view('action-panel.partials.coupon', compact('coupons'))->render();
                    break;
                case 4:
                    $products = Product::all();
                    $brands = Brand::all();
                    $viewData = view('action-panel.partials.product', compact('products', 'brands'))->render();
                    break;
                case 5:
                    $orders = Order::whereIn('status', ['pending', 'processing'])
                        ->orderBy('created_at', 'desc')
                        ->get();
                    $viewData = view('action-panel.partials.order', compact('orders'))->render();
                    break;
                case 6:
                    $users = User::all();
                    $viewData = view('action-panel.partials.notification', compact('users'))->render();
                    break;
                case 7;
                    $rcs = RequestComplaint::whereIn('status', ['Pending', 'Reviewed'])->get();
                    $viewData = view('action-panel.partials.request-complaints', compact('rcs'))->render();
                    break;
            }
        }

        return view('action-panel.index', compact('user', 'actions', 'selectedActionId', 'viewData', 'orders'));
    }
}