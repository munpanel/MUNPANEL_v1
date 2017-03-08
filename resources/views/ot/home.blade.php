@extends('layouts.app')
@section('home_active', 'active')
@push('scripts')
  <script src="js/sortable/jquery.sortable.js"></script>
  <script src="js/nestable/jquery.nestable.js" cache="false"></script>
  <script src="js/nestable/demo.js" cache="false"></script>
@endpush
@push('css')
  <link href="js/nestable/nestable.css" rel="stylesheet" type="text/css" cache="false">
@endpush
@section('content')
<section class="vbox">
  <header class="header bg-white b-b">
    <p>欢迎组织团队成员 {{Auth::user()->name}}</p>
  </header>
  <section class="scrollable wrapper">
    <div class="row">
        <div class="col-md-6 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">会议报名情况<span class="pull-right">代表总数: {{$del}}</span>
@if ($hasChildComm)
<button class="btn btn-xs btn-white m-l active" id="nestable-menu" data-toggle="class:show">
              <i class="fa fa-plus text"></i>
              <span class="text">全部展开</span>
              <i class="fa fa-minus text-active"></i>
              <span class="text-active">全部折叠</span>
            </button>
@endif
                </div>

                <div class="panel-body">
                  {!!regStat($committees, $obs, $vol)!!}
                </div>
            </div>
        </div>
    </div>  
  </section>
</section>
@endsection
