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
        return redirect(secure_url('/store/cart')); //TODO: 添加操作成功提示
    }

    public function emptyCart()
    {
        Cart::destroy();
        return redirect(secure_url('/store'));
    }

    public function displayOrder($id)
    {
        $order = Order::findOrFail($id);
        if ($order->user_id != Auth::user()->id)
            return view('error', ['msg' => '该订单不属于您！']);
        return view('order', ['order' => $order, 'orderItems' => $order->items()]);
    }

    public function deleteOrder($id, $confirm = false)
    {
        if ($confirm) 
        { 
            $order = Order::findOrFail($id);
            if ($order->user_id != Auth::user()->id)
                return view('error', ['msg' => '该订单不属于您！']);
            $order->status = 'cancelled';
            $order->save();
            //Order::destroy($id);
            return redirect(secure_url('/store')); 
        } 
        else 
        { 
            return view('warningDialogModal', ['danger' => false, 'msg' => "您确实要取消该订单吗？", 'target' => secure_url("/store/deleteOrder/" . $id . "/true")]); 
        }
    }

    public function checkout()
    {
    }

    public function doCheckout(Request $request)
    {
        $order = new Order;
        $order->id = date("YmdHis");
        $order->user_id = Auth::user()->id;
        $method = $request->method;
        $order->shipment_method = $method;
        if ($method == 'mail')
        {
            Cart::add('NID_shipping', '运费', 1, 15);
            $order->address = $request->address;
        }
        $order->content = Cart::content();
        $price = str_replace(",", "", Cart::total());
        $order->price = floatval($price);
        Cart::destroy();
        foreach ($order->content as $row)
        {
            $id = $row->id;
            if (substr($id, 0, 4) == 'NID_')
                continue;
            $good = Good::find($id);
            if (is_object($good))
            {
                if ($good->remains > 0) {
                    $good->remains--;
                    $good->save();
                } else {
                    return view('error', ['msg' => '您的购物车中有商品已售空']);
                }
            } else {
                return view('error', ['msg' => '您的购物车中有商品已下架']);
            }
        }
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
        return view('store', ['orders' => Auth::user()->orders]);
    }
}
