@extends('layouts.app')
@section('store_active', 'active')
@push('scripts')
    <script src="{{cdn_url('/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{mp_url('/js/orders.js')}}"></script>
    <script src="{{cdn_url('/js/fuelux/fuelux.js')}}"></script>
@endpush
@push('css')
    <link rel="stylesheet" href="{{cdn_url('/css/jquery.dataTables.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{cdn_url('/js/fuelux/fuelux.css')}}" type="text/css" />
@endpush
@section('content')
<section class="vbox">
      <header class="header b-b bg-white">          
        <p>My Orders | MUNPANEL STORE - {{Reg::currentConference()->name}}</p>
        <a href="{{mp_url('/store')}}" class="btn btn-sm btn-info pull-right"><i class="fa fa-shopping-cart"></i> 前去购物</a>
      </header>
      <section class="scrollable wrapper w-f">
        <section class="panel">
          <div class="table-responsive">
            <table class="table table-striped m-b-none" id="store-table">
              <thead>
                <tr>
                  <th width="10"></th>
                  <th width="160">ID</th>
                  <th width="30">价格</th>
                  <th width="30">状态</th>
                  <th>下单时间</th>
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
