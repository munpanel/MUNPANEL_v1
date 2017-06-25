@extends('layouts.app')
@section('store_active', 'active')
@section('content')
      <section class="vbox bg-white">
        <header class="header b-b hidden-print">
          @if ($admin)
          <a href="{{mp_url('/store/orders/-1')}}" class="btn btn-sm btn-primary pull-right">返回</a>
          @else
          <a href="{{mp_url('/store/orders')}}" class="btn btn-sm btn-primary pull-right">返回</a>
          @endif
          <p class="pull-right">&nbsp;&nbsp;&nbsp;</p> 
          <button class="btn btn-sm btn-info pull-right" onClick="window.print();">打印</button>
          <p class="pull-right">&nbsp;&nbsp;&nbsp;</p> 
          @if ($order->status == 'unpaid')
            <a href="{{ mp_url('/pay/checkout.modal/'.$order->id) }}" data-toggle="ajaxModal" class="btn btn-sm btn-success pull-right">支付</a>
            <p class="pull-right">&nbsp;&nbsp;&nbsp;</p> 
            <a href="{{ mp_url('/store/deleteOrder/'.$order->id) }}" data-toggle="ajaxModal" class="btn btn-sm btn-white pull-right"><span class="text-danger">取消订单</span></a>
          @endif
          @if ($admin)
            <p class="pull-right">&nbsp;&nbsp;&nbsp;</p> 
            <a href="{{ mp_url('/store/orderAdmin.modal/'.$order->id) }}" data-toggle="ajaxModal" class="btn btn-sm btn-white pull-right">管理与审计</a>
          @endif
          <p>订单详情</p>
        </header>
        <section class="scrollable wrapper">
          <!--i class="fa fa-apple fa fa-3x"></i-->
          <div class="row">
            <div class="col-xs-6">
              <h4>MUNPANEL Store 订单 {{$order->id}}</h4>
            </div>
            <div class="col-xs-6 text-right">
              <p class="h4">{{$order->id}}</p>
              <!--h5>懒得放时间</h5-->           
            </div>
          </div>          
          <div class="well m-t">
            <div class="row">
              <div class="col-xs-6">
                <strong>付款方:</strong>
                <h4>{{$user->name}}</h4>
                <p>
                  @if ($order->shipment_method == 'mail')
                  Address: {{$order->address}}<br>
                  @elseif ($order->shipment_method == 'conference')
                  会议期间取货<br>
                  @endif
                  Phone: {{$user->tel}}<br>
                  Email: {{$user->email}}
                </p>
              </div>
              <div class="col-xs-6">
                <strong>收款方:</strong>
                <h4>{{$order->conference->option('organizer')}}</h4>
                <p>
                  {!!textWithBr($order->conference->option('store_contact'))!!}
                </p>
              </div>
            </div>
          </div>
          <p class="m-t m-b">
              订单状态：{!!$order->statusBadge()!!}<br>
              订单ID: <strong>{{$order->id}}</strong><br>
              付款时间：{{isset($order->payed_at)?$order->payed_at:'未付款'}}<br>
              发货时间：{{isset($order->shipped_at)?$order->shipped_at:'未发货'}}
          </p>
          <div class="line"></div>
          <table class="table">
            <thead>
              <tr>
                <th>描述</th>
                <th width="90">单价</th>
                <th width="60">数量</th>
                <th width="90">总价</th>
              </tr>
            </thead>
            <tbody>
              @foreach($orderItems as $item)
              <tr>
                <td>{{$item['name']}}</td>
                <td>¥{{number_format($item['price'], 2)}}</td>
                <td>{{$item['qty']}}</td>
                <td>¥{{number_format($item['qty'] * $item['price'], 2)}}</td>
              </tr>
              @endforeach
              <tr>
                <td colspan="3" class="text-right no-border"><strong>总计</strong></td>
                <td><strong>¥{{number_format($order->price, 2)}}</strong></td>
              </tr>
            </tbody>
          </table>
          <!--账单状态如有问题，请微信联系adamyi。-->
        </section>
      </section>
      <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
    </section>

@endsection
