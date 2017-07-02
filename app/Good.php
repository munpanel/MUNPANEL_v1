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
