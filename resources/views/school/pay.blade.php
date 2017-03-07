@extends('layouts.app')
@section('pay_active', 'active')
@section('content')
<div class="container">
    <div class="row"><br/><br/><br/></div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">{{Reg::current()->school->name}} 的支付信息</div>

                <div class="panel-body">
                    贵校共{{Reg::current()->school->delegates->where('status', 'oVerified')->count()}}名未缴费代表，其中{{Reg::current()->school->delegates->where('status','oVerified')->where('accomodate', 1)->count()}}位代表住宿；{{Reg::current()->school->volunteers->where('status', 'oVerified')->count()}}名未缴费志愿者，其中{{Reg::current()->school->volunteers->where('status','oVerified')->where('accomodate', 1)->count()}}位志愿者住宿，总待缴费{{Reg::current()->school->toPayAmount()}}元。<br><br>
                    您共有两种缴费方式可以选择：<br>
                <section class="panel">
                <header class="panel-heading bg-light">
                  <ul class="nav nav-tabs nav-justified">
                    <li class="active"><a href="#methods" data-toggle="tab" aria-expanded="true">缴费方式</a></li>
                    <li class=""><a href="#individual" data-toggle="tab" aria-expanded="false">个人缴费</a></li>
                    <li class=""><a href="#group" data-toggle="tab" aria-expanded="false">团体缴费</a></li>
                  </ul>
                </header>
                <div class="panel-body">
                  <div class="tab-content">
                    <div class="tab-pane active" id="methods">您当前选择的缴费方式是：{{Reg::current()->school->payment_method == 'individual' ? '个人缴费':'团体缴费'}}<br><br>在另外两个选项卡中可以查看具体缴费方式说明，也可以更换缴费方式。</div>
                    <div class="tab-pane" id="individual">我们坚信我们不仅要服务于每一位代表，也要服务于各校管理层，简化其报名工作。从帮助学校收集报名信息开始，再到帮助学校收钱。选用此种方式，贵校代表可自行登录MUNPANEL系统，线上通过微信支付、支付宝直接支付会费，系统实时自动确认收费并更新代表状态，省去学校社团管理层中间收钱步骤，便捷高效。<br><br><a href="{{secure_url('/school/pay/change/individual')}}" class="btn btn-s-md btn-success btn-rounded pull-right">选用此种方式</a></div>
                    <div class="tab-pane" id="group">您亦可选择传统的整校费用收齐后银行转帐的模式。<br><br>转账信息：<br>户名：朱淇惠<br>账号：6212 2602 0010 3912 990<br>开户行：中国工商银行 – 北京珠市口大栅栏支行<br><br>请成员校负责人将学校所有参会人员会费收齐后统一转账，并在备注处注明 BJMUN 会费字样学校名称，代表人数和住宿志愿者人数。<br><br><a href="{{secure_url('/school/pay/change/group')}}" class="btn btn-s-md btn-success btn-rounded pull-right">选用此种方式</a></div>
                  </div>
                </div>
              </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
