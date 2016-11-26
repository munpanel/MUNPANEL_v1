@extends('layouts.app')
@section('home_active', 'active')
@section('content')
<div class="container">
    <div class="row"><br/><br/><br/></div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Welcome</div>

                <div class="panel-body">
                    欢迎组织团队成员 {{Auth::user()->name}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
