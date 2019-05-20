<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

namespace App;

use Cart;
use Gloudemans\Shoppingcart\Contracts\Buyable;
use Illuminate\Database\Eloquent\Model;

class Good extends Model implements Buyable
{
    public $guarded = [];

    public function getBuyableIdentifier($options = null){
        return $this->id;
    }

    public function getBuyableDescription($options = null){
        $append = '';
        $prefix = '';
        if (is_array($options))
        {
            $options_config = json_decode($this->options, true);
            $append = '(';
            foreach($options as $key => $value)
            {
                $append .= $prefix . $options_config[$key]['values'][$value];
                $prefix = ', ';
            }
            $append .= ')';
        }
        return $this->name . $append;
    }

    public function getBuyablePrice($options = null){
        return $this->price;
    }

}
