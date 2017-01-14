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
        $result = new Collection;
        $goods = Good::all();//get(['id', 'title', 'deadline']);
        $i = 0;
        foreach($goods as $good)
        {
            $remain = $good->remains;
            if ($remain == -1) $remain = 99999;
            if ($remain == 0) $command = '<p class="text-muted">已售罄</p>';
            else
            {
                if (!$good->enabled) continue;
                if (Auth::user()->type == 'ot')
                {
                    $command = 'TODO: 针对组委的操作项'
                }
                else
                {
                    $command = '数量： <div class="spinner input-group shop-spinner" id="MySpinner" data-max="' . $remain . '" data-min="1">
                              <input name="spinner" class="form-control spinner-input" type="text" maxlength="2" value="1">
                              <div class="btn-group btn-group-vertical input-group-btn">
                                <button class="btn btn-white spinner-up" type="button">
                                  <i class="fa fa-chevron-up text-muted"></i>
                                </button>
                                <button class="btn btn-white spinner-down" type="button">
                                  <i class="fa fa-chevron-down text-muted"></i>
                                </button>
                              </div>
                            </div> 　
                  <a href="'.secure_url('/ot/committeeDetails.modal/new').'" class="btn btn-sm btn-success details-modal"><i class="fa fa-plus"></i> 加入购物车</a>'
                }
            }
            $result->push([
                'id' => ++$i, 
                'image' => '<a href="good.modal/'. $good->id.'" data-toggle="ajaxModal"><img src="goodimg/' . $good->id . '" class="shop-image-small"></a>',
                'title' => '<a href="good.modal/'. $good->id.'" data-toggle="ajaxModal">'.$good->name.'</a>',
                'price' => $good->price,
                'command' => $command,
            ]);
        }
        return Datatables::of($result)->make(true);
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
            return view('error', ['msg' => '该订单不属于您！']);
        return view('order', ['order' => $order, 'orderItems' => $order->items()]);
    }

    public function checkout()
    {
    }

    public function doCheckout(Request $request)
    {
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
