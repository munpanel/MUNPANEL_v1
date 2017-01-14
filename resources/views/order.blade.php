@extends('layouts.app')
@section('invoice_active', 'active')
@section('content')
      <section class="vbox bg-white">
        <header class="header b-b hidden-print">
          <button href="#" class="btn btn-sm btn-info pull-right" onClick="window.print();">打印</button>
          <p class="pull-right">&nbsp;&nbsp;&nbsp;</p> 
          @if ($order->status == 'unpaid')
            <a href="{{ secure_url('/pay/checkout.modal') }}" data-toggle="ajaxModal" class="btn btn-sm btn-success pull-right">支付</a>
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
                <strong>付款人:</strong>
                <h4>{{Auth::user()->name}}</h4>
                <p>
                  {{Auth::user()->specific()->school->name}}<br>
                  @if ($order->shipment_method == 'mail')
                  Address: {{$order->address}}<br>
                  @elseif ($order->shipment_method == 'conference')
                  会议期间取货
                  @endif
                  Phone: {{Auth::user()->specific()->tel}}<br>
                  Email: {{Auth::user()->email}}<br>
                </p>
              </div>
              <div class="col-xs-6">
                <strong>收款人:</strong>
                <h4>北京市高中生模拟联合国协会</h4>
                <p>
                  <a href="https://www.bjmun.org/">www.bjmun.org</a><br>
                  Email: official@bjmun.org<br>
                  Wechat: beijingbjmun<br>
                </p>
              </div>
            </div>
          </div>
          <p class="m-t m-b">
          @if ($order->status == 'unpaid')
              订单状态: <span class="label bg-danger">未支付</span><br>
          @else
              订单状态: <span class="label bg-success">待发货</span><br>
          @endif
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
                <td>{{$item['amount']}}</td>
                <td>¥{{number_format($item['amount'] * $item['price'], 2)}}</td>
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
