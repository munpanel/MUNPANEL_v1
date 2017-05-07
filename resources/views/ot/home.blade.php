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
  @if ((!Auth::user()->verified()) || (Reg::currentConference()->status == 'reg' && Reg::current()->type == 'unregistered') || (!Reg::selectConfirmed()) || (!Reg::current()->enabled) || (null!==(Reg::current()->specific()) && Reg::current()->specific()->status == 'fail') || (Reg::current()->type != 'unregistered' && is_null(Reg::current()->specific())))
  <script src="{{cdn_url('/js/reg.firsttime.js')}}"></script>
  @endif
@endpush
@push('css')
  <link href="{{cdn_url('js/nestable/nestable.css')}}" rel="stylesheet" type="text/css" cache="false">
@endpush
@section('content')
<section class="vbox">
  <header class="header bg-white b-b">
    <p>欢迎{{is_null(Reg::current()->ot->position)?'组织团队成员':Reg::current()->ot->position}} {{Reg::current()->name()}}</p>
    @if (Reg::current()->type == 'ot')
      @include('components.otHomeSparklineStat')
    @endif
  </header>
  <section class="scrollable wrapper">
    <div class="row">
      @if (Reg::currentConference()->status == 'daisreg')
      <div class="col-md-8 col-md-offset-2">
        @if (validateRegAvaliable('ot'))
          @include('components.otTodoStatOtreg')
        @endif
        @if (validateRegAvaliable('dais'))
          @include('components.otTodoStatDaisreg')
        @endif
      </div>
      @elseif (in_array(Reg::currentConference()->status, ['reg', 'regstop']))
        @if (Reg::current()->can('view-regs') && Reg::current()->type == 'ot')
        <div class="col-md-4">
          @include('components.otRegStat')
        </div>
        @endif
      <div class="col-md-8 {{!(Reg::current()->can('view-regs') && Reg::current()->type == 'ot') ? 'col-md-offset-2' : ''}}">
        @if (Reg::current()->can('view-regs'))
          @include('components.otTodoStatReg')
        @endif
        @if ($canInterview || Reg::current()->type == 'interviewer')
          @include('components.otTodoStatInterview')
        @endif
      </div>
      @endif
    </div>  
  </section>
</section>
@endsection
