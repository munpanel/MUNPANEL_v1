@extends('layouts.app')
@if ($id == Auth::id())
@section('store_active', 'active')
@else
@section('orderManage_active', 'active')
@endif
@push('scripts')
    <script src="{{cdn_url('/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{cdn_url('/js/datatables/fnReloadAjax.js')}}"></script>
    <script>
    $(document).ready(function() {
    $('#orders-table').DataTable({
            paging: false,
            bFilter: false,
            ajax: '{{mp_url('/ajax/orders/'.$id)}}',
            columns: [
                {data: 'details', name: 'details', orderable: false},
                {data: 'id', name: 'id', orderable: false},
                @if (Auth::id() != $id)
                {data: 'uid', name: 'uid', orderable: true},
                {data: 'username', name: 'username', orderable: false},
                @endif
                {data: 'price', name: 'price', orderable: true},
                {data: 'status', name: 'status', orderable: true},
                {data: 'time', name: 'time', orderable: true}            
            ],
            fnInitComplete: function(oSettings, json) {
            $(document).on('hidden.bs.modal', '#ajaxModal', function() {
                $('#orders-table').dataTable().fnReloadAjax(undefined, undefined, true);
            });
            $('#orders-table_length').hide();
            $('#orders-table_info').appendTo($('#orders-pageinfo'));
            $('#orders-table').removeClass('no-footer');
            },
            "language": {
                "zeroRecords": "无订单",
                "info": "共 _MAX_ 项订单",
                "infoEmpty": "无订单",
            },
            "order": [[{{Auth::id() == $id ? 4:6}}, "asc"]],
        });
    });
    </script>
@endpush
@push('css')
    <link rel="stylesheet" href="{{cdn_url('/css/jquery.dataTables.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{cdn_url('/js/fuelux/fuelux.css')}}" type="text/css" />
@endpush
@section('content')
<section class="vbox">
      <header class="header b-b bg-white">          
        <p>MUNPANEL STORE - {{Reg::currentConference()->name}}</p>
        <a href="{{mp_url('/store')}}" class="btn btn-sm btn-info pull-right"><i class="fa fa-shopping-cart"></i> 前去购物</a>
        @permission('edit-orders')
        <p class="pull-right">&nbsp;&nbsp;&nbsp;</p> 
        @if ($id == -1)
        <a href="{{mp_url('/store/orders')}}" class="btn btn-sm btn-warning pull-right"><i class="fa fa-user"></i> 我的订单</a>
        @else
        <a href="{{mp_url('/store/orders/-1')}}" class="btn btn-sm btn-warning pull-right"><i class="fa fa-users"></i> 全部用户订单</a>
        @endif
        @endpermission
      </header>
      <section class="scrollable wrapper w-f">
        <section class="panel">
          <div class="table-responsive">
            <table class="table table-striped m-b-none" id="orders-table">
              <thead>
                <tr>
                  <th width="10"></th>
                  <th width="160">ID</th>
                  @if (Auth::id() != $id)
                  <th width="30">UID</th>
                  <th width="80">买家姓名</th>
                  @endif
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
                  <small class="text-muted inline m-t-sm m-b-sm" id="orders-pageinfo"></small>
                </div>
              </div>
            </footer>
          </section>
@endsection
