﻿@extends('layouts.app')
@section('store_active', 'active')
@push('scripts')
    <script src="{{secure_url('/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{secure_url('/js/store.js')}}"></script>
    <script src="{{secure_url('/js/fuelux/fuelux.js')}}"></script>
@endpush
@push('css')
    <link rel="stylesheet" href="{{secure_url('/css/jquery.dataTables.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{secure_url('/js/fuelux/fuelux.css')}}" type="text/css" />
@endpush
@section('content')
<section class="vbox">
      <header class="header b-b bg-white">          
        <p>BJMUNC2017 纪念品商店</p>
        <a href="{{secure_url('/ot/committeeDetails.modal/new')}}" class="btn btn-sm btn-info details-modal pull-right"><i class="fa fa-shopping-cart"></i> 我的购物车</a>
        @if (false) 
           <!-- TODO: 构建后台 -->
        @endif
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
                  <th width="150">操作</th>
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