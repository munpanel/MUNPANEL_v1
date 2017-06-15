@extends('layouts.app')
@section('store_active', 'active')
@push('css')
<link href="{{cdn_url('css/store.css')}}" rel="stylesheet" type="text/css">
@endpush('css')
@section('content')
<div class="container">
<div class="check"> 
<h1>My Cart ({{Cart::instance('conf_'.Reg::currentConferenceID())->count()}})</h1>
<div class="col-md-9 cart-items">
@foreach (Cart::instance('conf_'.Reg::currentConferenceID())->content() as $row)
<div class="cart-header">
<div class="close"> </div>
<div class="cart-sec simpleCart_shelfItem">
<div class="cart-item cyc">
<img src='{{mp_url("store/goodimg/$row->id")}}' class="img-responsive" alt="">
</div>
<div class="cart-item-info">
<h3><a href="#">{{$row->name}}</a><!--ispan>Powered by Adam Yi LOL</span--></h3>
<ul class="qty">
<li><p>Qty : {{$row->qty}}</p></li>
<li><p>Price: {{number_format($row->price, 2)}}</p></li>
<li><p>Subtotal: {{number_format($row->subtotal, 2)}}</p></li>

</ul>
<a href='{{mp_url("store/cart/remove/$row->rowId")}}'>Click <u>here</u> to remove from cart</a>

<!--div class="delivery">
<p>Provided by BJMUN</p>
<span>Powered by MUNPANEL</span>
<div class="clearfix"></div>
</div-->
</div>
<div class="clearfix"></div>

</div>
</div>
@endforeach
</div>
<div class="col-md-3 cart-total">
<a class="continue" href="{{mp_url('/store')}}">Continue to Store</a>
<div class="price-details">
<h3>Price Details</h3>
<span>Subtotal</span>
<span class="total1">{{Cart::instance('conf_'.Reg::currentConferenceID())->subtotal()}}</span>
<span>Discount</span>
<span class="total1">---</span>
<span>Tax</span>
<span class="total1">{{Cart::instance('conf_'.Reg::currentConferenceID())->tax()}}</span>
<!--span>Delivery Charges</span>
<span class="total1">150.00</span-->
<div class="clearfix"></div> 
</div>
<ul class="total_price">
<li class="last_price"> <h4>TOTAL</h4></li>
<li class="last_price"><span>{{Cart::instance('conf_'.Reg::currentConferenceID())->total()}}</span></li>
<div class="clearfix"> </div>
</ul>


<div class="clearfix"></div>
@if (Config::get('munpanel.store_checkout'))
<a class="order" data-toggle="ajaxModal" href="{{mp_url('/store/shipment.modal')}}">Place Order</a>
@else
<a class="order" href="#">购买通道已关闭</a>
@endif
<div class="total-item">
<h3>OPTIONS</h3>
<h4>COUPONS</h4>
<a class="cpns" href="#">Apply Coupons</a>
</div>
</div>

<div class="clearfix"> </div>
</div>
</div>
@endsection
