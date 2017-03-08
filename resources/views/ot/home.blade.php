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
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">会议报名情况<span class="pull-right">代表总数: {{$del}}</span></div>

                <div class="panel-body">
                  {{regStat($committees, $obs, $vol}}
                  {{--  欢迎组织团队成员 {{Auth::user()->name}}<br/><br/>目前报名人数：<br/>
                    @foreach ($committees as $committee)
                    {{$committee->name}} {{$committee->delegates->count()}}<br/>
                    @endforeach
                    志愿者{{$vol --}}
                </div>
            </div>
        </div>
    </div>  
  </section>
</section>
@endsection
