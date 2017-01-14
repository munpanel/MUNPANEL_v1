<?php

namespace App\Http\Controllers;

use Config;
use App\User;
use App\Good;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cart;

class StoreController extends Controller
{

    public function displayCart()
    {
    }

    public function addCart($id)
    {
    }

    public function removeCart($id)
    {
    }

    public function emptyCart()
    {
    }

    public function displayOrder($id)
    {
        $order = Order::findOrFail($id);
        if ($order->user_id != Auth::user()->id)
            return view('error', ['msg' => '该订单不属于您！']);
        return view('order', ['order' => $order, 'orderItems' => $order->items()]);
    }

    public function checkout()
    {
    }

    public function doCheckout(Request $request)
    {
    }
        
    public function goodImage($id)
    {
        $item = Good::findOrFail($id);
        return response()->download(storage_path('/app/'.$item->image));
    }
    
    public function home()
    {
        return view('store');
    }
}