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

}
