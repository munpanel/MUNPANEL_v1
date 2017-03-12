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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            $param['out_order_no'] = Config::get('teegon.oderid_prefix').$order->id;
            $param['pay_channel'] = $request->channel;
            $param['return_url'] = $request->return;
            $param['amount'] = $order->price;
            $param['subject'] = 'MUNPANEL Store';
            $param['metadata'] = json_encode(array('oid' => $order->id, 'uid' => Auth::user()->id));
            $param['notify_url'] = mp_url('/api/payNotify');//支付成功后天工支付网关通知
            //dd($param);

        }
        else //this should not be used. Fees for participating should be created as order as well. This section will be removed in the future.
        {
            $param['out_order_no'] = Config::get('teegon.oderid_prefix').Auth::user()->id;//.substr(md5(time().print_r($_SERVER,1)), 0, 8); //订单号
            $param['pay_channel'] = $request->channel;
            $param['return_url'] = $request->return;
            $param['amount'] = Auth::user()->invoiceAmount();
            $param['subject'] = 'BJMUNC 2017会费';
            $param['metadata'] = json_encode(array('uid'=> Auth::user()->id));
            $param['notify_url'] = mp_url('/api/payNotify');//支付成功后天工支付网关通知
            //return $param;
        }
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
                if ($order->status == 'unpaid' || $order->status == 'cancelled') //even if it's cancelled, we should still set the order as paid as the user wants the order again
                {
                    $order->status ='paid';
                    $order->payed_at = date('Y-m-d H:i:s');
                }
                /*
                migration:
                $table->string('charge_id')->nullable();//流水号
                $table->string('buyer')->nullable();//支付方(微信ID/支付宝手机号)
                $table->string('payment_no')->nullable();//第三方交易单号
                */
                $order->charge_id = $request->charge_id;
                $order->buyer = $request->buyer;
                $order->payment_no = $request->payment_no;
                $order->save();
            }
            else //this should not be used. Fees for participating should be created as order as well. This section will be removed in the future.
            {
                $amount = $request->amount;
                $meta = json_decode($request->metadata);
                $user = User::find($meta->uid);
                //VERIFY AMOUNT
                if ($user->invoiceAmount() != $amount)
                    return "ERROR";
                //END VERIFICATION
                $specific = $user->specific();
                $specific->status = 'paid';
                $specific->save();
            }
        }
    }
}
