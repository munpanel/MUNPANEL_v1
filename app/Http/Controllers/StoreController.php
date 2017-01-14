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
    public function goodsList()
    {
    }

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
            return "Not your order";
        return view('order', ['order' => $order, 'orderItems' => $order->items()]);
    }

    public function checkout()
    {
    }

    public function doCheckout(Request $request)
    {
    }
}
