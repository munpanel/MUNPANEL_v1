<?php
/**
 * Copyright (C) Console iT
 * This file is part of MUNPANEL System.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * Developed by Adam Yi <xuan@yiad.am>
 */

namespace App\Http\Controllers;

use Config;
use App\Reg;
use App\User;
use App\Good;
use App\Order;
use App\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Cart;

class StoreController extends Controller
{
    /**
     * Display the cart.
     *
     * @return Illuminate\Http\Response;
     */
    public function displayCart()
    {
        //return response()->json(Cart::content());
        return view('cart');
    }

    /**
     * Add an item to the cart
     *
     * @param Request $request
     * @param int $id id of the good to be added
     * @return Illuminate\Http\Response
     */
    public function addCart(Request $request, $id)
    {
        Cart::add(Good::findOrFail($id), $request->num);
        return redirect(mp_url('/store/cart')); //TODO: 添加操作成功提示
    }

    /**
     * Remove an item from the cart
     *
     * @param rowID of the item to be removed
     * @return Illuminate\Http\Response
     */
    public function removeCart($id)
    {
        Cart::remove($id); //ID is rowID instead of goodID
        return redirect(mp_url('/store/cart')); //TODO: 添加操作成功提示
    }

    /**
     * Empty the cart
     *
     * @return void
     */
    public function emptyCart()
    {
        Cart::destroy();
        return redirect(mp_url('/store'));
    }

    /**
     * Display an order info.
     *
     * @param int $id the ID of the order
     * @return Illuminate\Http\Response
     */
    public function displayOrder($id)
    {
        $order = Order::findOrFail($id);
        $self = ($order->user_id == Auth::id());
        $admin = Reg::current()->can('edit-orders') && $order->conference_id == Reg::currentConferenceID();
        if ($self || $admin)
            return view('order', ['order' => $order, 'orderItems' => $order->items(), 'user' => $order->user, 'admin' => $admin]);
        return view('error', ['msg' => '该订单不属于您！']);
    }

    public function orderAdmin($id)
    {
        $order = Order::findOrFail($id);
        if (Reg::current()->can('edit-orders') && $order->conference_id == Reg::currentConferenceID())
            return view('orderAdminModal', ['order' => $order]);
        return view('error', ['msg' => 'Access Denied.']);
    }

    /**
     * Cancel an order.
     *
     * @param int $id the ID of the order
     * @param boolean $confirm whether to cancel or to show a prompt
     * @return Illuminate\Http\Response
     */
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
            return redirect(mp_url('/store')); 
        } 
        else 
        { 
            return view('warningDialogModal', ['danger' => false, 'msg' => "您确实要取消该订单吗？", 'target' => mp_url("/store/deleteOrder/" . $id . "/true")]); 
        }
    }

    /**
     * Create an order from the cart.
     * 
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function doCheckout(Request $request)
    {
        $order = new Order;
        $order->id = date("YmdHis").generateID(6);
        $order->user_id = Auth::user()->id;
        $order->conference_id = Reg::currentConferenceID();
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
        return redirect(mp_url('/store/order/' . $order->id));
    }

    /**
     * Show a modal in which users may specify the shipping method.
     * 
     * @return Illuminate\Http\Response
     */
    public function shipmentModal()
    {
        return view('shipmentModal');
    }

    /**
     * Show a details modal of a good.
     * 
     * @param int $id the id of the good
     * @return Illuminate\Http\Response
     */
    public function goodModal($id)
    {
        $good = Good::findOrFail($id);
        if ($good->enabled == false)
            return 'error';
        return view('goodModal', ['good' => $good]);
    }
        
    /**
     * Show the image of a good
     * 
     * @param int $id the id of the good
     * @return Illuminate\Http\Response
     */
    public function goodImage($id)
    {
        $item = Good::findOrFail($id);
        return response()->file(storage_path('/app/'.$item->image));
    }
    
    /**
     * Display store.
     * 
     * @return Illuminate\Http\Response
     */
    public function home()
    {
        return view('store', ['orders' => Auth::user()->orders()->where('conference_id', Reg::currentConferenceID())->latest()->limit(3)->get(), 'count' => Auth::user()->orders()->where('conference_id', Reg::currentConferenceID())->count()]);
    }

    public function ordersList($id = 0)
    {
        if ($id == 0)
            $id = Auth::id();
        return view('orders', ['id' => $id]);
    }
    
    /**
     * View all orders of one user.
     * 
     * @param int $id the id of the user
     * @return Illuminate\Http\Response
     */
    public function viewAllOrders($id)
    {
	//To-Do: Permissions & conferences
        if (is_numeric($id))
            $user = User::find($id);
        else
            $user = Card::findOrFail($id)->user;
        $orders = $user->orders->where('status', 'paid');
        //$orders = Order::where('status', 'paid')->where('shipment_method', 'mail')->get();
        return view('allOrders', ['user' => $user, 'orders' => $orders]);
    }
    
    /**
     * Mark an order as shipped.
     * 
     * @param int $id the id of the order
     * @return Illuminate\Http\Response
     */
    public function shipOrder($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'done';
        $order->shipped_at = date('Y-m-d H:i:s');
        $order->save();
        return redirect(mp_url('/allOrders/' . $order->user_id));
    }
}
