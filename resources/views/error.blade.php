@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row"><br/><br/><br/></div>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Sorry</div>

                <div class="panel-body">
                      {{$msg}}<br>
                    <!-- TODO: 点击返回上一页 --> 
                    <a href="" class="btn btn-sm btn-success text-uc m-t-n-xs"><i class="fa fa-arrow-left"></i> 返回上一页</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
