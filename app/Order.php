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

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $incrementing = false;
    public $guarded = [];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function items() {
        return json_decode($this->content, true);
    }

    public function statusBadge() {
        switch($this->status)
        {
            case 'cancelled': return '<span class="label bg-danger">已取消</span>';
            case 'unpaid': return '<span class="label bg-danger">未支付</span>';
            case 'paid': return '<span class="label bg-info">待发货</span>';
            default: return '<span class="label bg-success">已发货</span>';
        }
    }

    public function getPaid($charge_id, $buyer, $payment_no, $pay_channel) {
        if ($this->status == 'unpaid' || $this->status == 'cancelled') //even if it's cancelled, we should still set the this as paid as the user wants the this again
        {
            $this->status ='paid';
            $this->payed_at = date('Y-m-d H:i:s');
        }

        $this->charge_id = $charge_id;
        $this->buyer = $buyer;
        $this->payment_no = $payment_no;
        $this->payment_channel = $pay_channel;

        // Set status to paid
        $reg = Reg::where('order_id', $this->id)->first();
        if (is_object($reg))
        {
            $specific = $reg->specific();
            if (is_object($specific)) {
                $specific->status = 'paid';
                $specific->save();
                $this->status = 'done';
                $this->shipped_at = date('Y-m-d H:i:s');
            }
        }

        $this->save();
    }

}
