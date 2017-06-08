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
use App\User;
use App\Order;
use App\Reg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PayController extends Controller
{
    /**
     * Get the payment info given certain order id.
     * 
     * @param Request $request
     * @return string the info of the payment
     */
    public function payInfo(Request $request)
    {
        $srv = new TeegonService(Config::get('teegon.api_url'));
        if (isset($request->oid))
        {
            $order = Order::findOrFail($request->oid);
            if ($order->user_id != Auth::user()->id)
            {
                abort(500);
            }
            $param['out_order_no'] = Config::get('teegon.orderid_prefix').$order->id;
            $param['pay_channel'] = $request->channel;
            $param['return_url'] = route('payResult');
            $param['amount'] = $order->price;
            $param['subject'] = 'MUNPANEL Store';
            $param['metadata'] = json_encode(array('oid' => $order->id, 'uid' => Auth::user()->id));
            $param['notify_url'] = route('payNotify');
            //dd($param);

        } else
            return "No orders specified";
        return $srv->pay($param,false);
 
    }

    /**
     * Mark an order as paid. This function is called
     * by Teegon server.
     *
     * @param Request $request
     * @return void
     */
    public function payNotify(Request $request)
    {
        //UPDATE1: verification in PHP API (no official documentation for that, but will do)  --- Adam Yi Feb 6 2017
        //TO UPDATE: verification for security purposes (Teegon is still working on it. Update after they update lol). Payment status is strongly recommended to be manually checked now.
        //file_put_contents("/var/www/munpanel/storage/t", 'a'.var_export($request,true));
        $srv = new TeegonService(Config::get('teegon.api_url'));
        $meta = json_decode($request->metadata);
        if ($srv->verify_return())
        {
            if (isset($meta->oid))
            {
                $amount = $request->amount;
                //$meta = json_decode($request->metadata);
                //$user = User::find($meta->uid);
                $order = Order::find($meta->oid);
                //VERIFY AMOUNT
                if ($order->price != $amount)
                    return "ERROR";
                //END VERIFICATION
                Cache::tags('orders')->put($order->id, 1, 2);

                $order->getPaid($request->charge_id, $request->buyer, $request->payment_no, $request->pay_channel);
            }
        }
    }

    public function payResult(Request $request)
    {
        //UPDATE1: verification in PHP API (no official documentation for that, but will do)  --- Adam Yi Feb 6 2017
        //TO UPDATE: verification for security purposes (Teegon is still working on it. Update after they update lol). Payment status is strongly recommended to be manually checked now.
        //file_put_contents("/var/www/munpanel/storage/t", 'a'.var_export($request,true));
        $srv = new TeegonService(Config::get('teegon.api_url'));
        if ($srv->verify_return())
        {
            $meta = json_decode($request->metadata);
            return view('paySuccess', ['orderID' => $meta->oid, 'amount' => $request->amount]);
        } else
            return "Error! Please check if the order status is correct and contact wechat adamyi";
    }

    public function resultAjax($id)
    {
        set_time_limit(0);
        $i=0;  
        while (true){  
         //sleep(1);  
         if (Cache::tags('orders')->get($id))
             return 'success';
         usleep(500000);//0.5 seconds
         $i++;  
         if ($i == 60)
             return 'error';
         }  
    }
}
