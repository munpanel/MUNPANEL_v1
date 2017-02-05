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
        //return response()->json(Cart::content());
        return view('cart');
    }

    public function addCart(Request $request, $id)
    {
        Cart::add(Good::findOrFail($id), $request->num);
        return redirect(secure_url('/store/cart')); //TODO: 添加操作成功提示
    }

    public function removeCart($id)
    {
        Cart::remove($id); //ID is rowID instead of goodID
    }

    public function emptyCart()
    {
        Cart::destroy();
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
        $order = new Order;
        $order->id = date("YmdHis");
        $order->user_id = Auth::user()->id;
        $order->content = Cart::content();
        $order->shipment_method = $request->method;
        $order->price = Cart::subtotal();
        if ($method == 'mail')
            $order->address = $request->address;
        $order->save();
        return redirect(secure_url('/store/order/' . $order->id));
    }

    public function shipmentModal()
    {
        return view('shipmentModal');
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
