@extends('layouts.app')
@section('store_active', 'active')
@push('scripts')
    <script src="{{cdn_url('/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{cdn_url('/js/store.js')}}"></script>
    <script src="{{cdn_url('/js/fuelux/fuelux.js')}}"></script>
@endpush
@push('css')
    <link rel="stylesheet" href="{{cdn_url('/css/jquery.dataTables.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{cdn_url('/js/fuelux/fuelux.css')}}" type="text/css" />
@endpush
@section('content')
<section class="vbox">
      <header class="header b-b bg-white">          
        <p>MUNPANEL STORE - {{Reg::currentConference()->name}}</p>
        <a href="{{mp_url('/store/cart')}}" class="btn btn-sm btn-info pull-right"><i class="fa fa-shopping-cart"></i> 我的购物车 ({{Cart::instance('conf_'.Reg::currentConferenceID())->count()}})</a>
        <p class="pull-right">&nbsp;</p>
        <div class="btn-group pull-right">
          <button class="btn btn-white btn-sm dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><i class="fa fa-money"></i> 我的订单 ({{$count}}) <span class="caret"></span></button>
          <ul class="dropdown-menu">
          @foreach($orders as $order)
            <li><a href="{{mp_url('/store/order/' . $order->id)}}">订单 {{$order->id}}</a></li>
          @endforeach
          @if ($orders->count() != 0)
            <li class="divider"></li>
          @endif
            <li><a href="{{mp_url('/store/orders')}}">查看所有订单</a></li>
          </ul>
        </div>
        @permission('edit-store')
           <!-- TODO: 构建后台 -->
        @endpermission
      </header>
      <section class="scrollable wrapper w-f">
        <section class="panel">
          <div class="table-responsive">
            <table class="table table-striped m-b-none" id="store-table">
              <thead>
                <tr>
                  <th width="20">#</th>
                  <th width="160">图片</th>
                  <th>品名</th>
                  <th width="40">单价</th>
                  <th width="240">操作</th>
                </tr>
              </thead>
            </table>
          </div>
        </section>
      </section>
            <footer class="footer bg-white b-t">
              <div class="row m-t-sm text-center-xs">
                <div class="col-sm-12">
                  <small class="text-muted inline m-t-sm m-b-sm" id="store-pageinfo"></small>
                </div>
              </div>
            </footer>
          </section>
@endsection
