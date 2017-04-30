@php
$canInterview = false;
foreach(Auth::user()->regs->where('conference_id', Reg::currentConferenceID())->where('enabled', true) as $reg)
    if ($reg->type == 'interviewer')
        $canInterview = true;
@endphp
@extends('layouts.app')
@section('home_active', 'active')
@push('scripts')
  <script src="{{cdn_url('js/nestable/jquery.nestable.js')}}"></script>
  <script src="{{cdn_url('js/nestable/demo.js')}}"></script>
  <script src="{{cdn_url('js/charts/sparkline/jquery.sparkline.min.js')}}"></script>
  <script src="{{cdn_url('js/charts/easypiechart/jquery.easy-pie-chart.js')}}"></script>
@endpush
@push('css')
  <link href="{{cdn_url('js/nestable/nestable.css')}}" rel="stylesheet" type="text/css" cache="false">
@endpush
@section('content')
<section class="vbox">
  <header class="header bg-white b-b">
    <p>欢迎{{is_null(Reg::current()->dais->position)?'学术团队成员':Reg::current()->dais->position}} {{Reg::current()->name()}}</p>
  </header>
  <section class="scrollable wrapper">
    <div class="row">
      @if (Reg::currentConference()->status == 'daisreg')
      <div class="col-md-8 col-md-offset-2">
        @include('components.otTodoStatDaisreg')
      </div>
      @elseif (in_array(Reg::currentConference()->status, ['reg', 'regstop']))
      <div class="col-md-8 {{!(Reg::current()->can('view-regs') && Reg::current()->type == 'ot') ? 'col-md-offset-2' : ''}}">
        @if ($canInterview)
          @include('components.otTodoStatInterview')
        @endif
      </div>
      @endif
    </div>  
  </section>
</section>
@endsection
