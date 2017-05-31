@extends('layouts.app')
@section('sudo_active', 'active')
@section('content')
<div class="container">
    <div class="row"><br/><br/><br/></div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">谨慎操作！</div>

                <div class="panel-body">
                    您已通过 SUDO 进入了并非属于您的身份。通常情况下，这是会议管理员在操作该会议中某一代表账号。<br>
                    请谨慎操作！因此模式中的错误操作造成的任何不便，MUNPANEL 概不负责。<br>
                    如需退出 SUDO 模式，请在左侧导航栏中切换身份。
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
