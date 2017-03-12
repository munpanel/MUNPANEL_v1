@extends('layouts.app')
@section('invoice_active', 'active')
@section('content')
      <section class="vbox bg-white">
        <header class="header b-b hidden-print">
          <button href="#" class="btn btn-sm btn-info pull-right" onClick="window.print();">打印</button>
          <p class="pull-right">&nbsp;&nbsp;&nbsp;</p> 
          @if (Reg::current()->specific()->status != 'paid')
            <a href="{{ mp_url('/pay/checkout.modal') }}" data-toggle="ajaxModal" class="btn btn-sm btn-success pull-right">支付</a>
          @endif
          <p>账单</p>
        </header>
        <section class="scrollable wrapper">
          <!--i class="fa fa-apple fa fa-3x"></i-->
          <div class="row">
            <div class="col-xs-6">
              <h4>BJMUNC 2017 账单</h4>
            </div>
            <div class="col-xs-6 text-right">
              <p class="h4">#{{Auth::user()->id}}</p>
              <!--h5>懒得放时间</h5-->           
            </div>
          </div>          
          <div class="well m-t">
            <div class="row">
              <div class="col-xs-6">
                <strong>付款人:</strong>
                <h4>{{Auth::user()->name}}</h4>
                <p>
                  {{Reg::current()->specific()->school->name}}<br>
                  Phone: {{Reg::current()->specific()->tel}}<br>
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
          @if (Reg::current()->specific()->status != 'paid')
              账单状态: <span class="label bg-danger">未支付</span><br>
          @else
              账单状态: <span class="label bg-success">已支付</span><br>
          @endif
              账单ID: <strong>#{{Auth::user()->id}}</strong>
          </p>
          <div class="line"></div>
          <table class="table">
            <thead>
              <tr>
                <th width="60">数量</th>
                <th>描述</th>
                <th width="140">单价</th>
                <th width="90">总价</th>
              </tr>
            </thead>
            <tbody>
              @foreach($invoiceItems as $item)
              <tr>
                <td>{{$item[0]}}</td>
                <td>{{$item[1]}}</td>
                <td>¥{{$item[2]}}.00</td>
                <td>¥{{$item[0] * $item[2]}}.00</td>
              </tr>
              @endforeach
              <tr>
                <td colspan="3" class="text-right no-border"><strong>总计</strong></td>
                <td><strong>¥{{$invoiceAmount}}.00</strong></td>
              </tr>
            </tbody>
          </table>
          账单状态如有问题，请微信联系adamyi。
        </section>
      </section>
      <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
    </section>

@endsection
