@extends('layouts.app')
@section('home_active', 'active')
@section('content')
<div class="container">
    <div class="row"><br/><br/><br/></div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome {{Auth::user()->name}}</div>

                <div class="panel-body">
                    This is a portal page under development... Later, you will be able to modify your personal info like name, email, tel, etc., check all the conferences you have registered before, and even find some new conferences that may interest you.<br><br>
                    暂时，如需回到BJMUNSS 2017，请访问https://bjmun.munpanel.com；如需回到ROMUNC 2017，请访问https://romun.munpanel.com，感谢
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
