@extends('layouts.app')
@section('home_active', 'active')
@push('scripts')
  <script src="js/nestable/jquery.nestable.js" cache="false"></script>
  <script src="js/nestable/demo.js" cache="false"></script>
  <script src="js/charts/sparkline/jquery.sparkline.min.js"></script>
  <script src="js/charts/easypiechart/jquery.easy-pie-chart.js"></script>
@endpush
@push('css')
  <link href="js/nestable/nestable.css" rel="stylesheet" type="text/css" cache="false">
@endpush
@section('content')
<section class="vbox">
  <header class="header bg-white b-b">
    <p>欢迎{{Reg::current()->type == 'ot' ? '组织团队' : '学术团队'}}成员 {{Auth::user()->name}}</p>
    @if (Reg::current()->type == 'ot')
      @include('components.otHomeSparklineStat')
    @endif
  </header>
  <section class="scrollable wrapper">
    <div class="row">
      @if (Reg::currentConference()->status == 'daisreg')
      <div class="col-md-8 col-md-offset-2">
        @include('components.otTodoStatDaisreg')
      </div>
      @elseif (in_array(Reg::currentConference()->status, ['reg', 'regstop']))
        @if (Reg::current()->can('view-regs') && Reg::current()->type == 'ot')
        <div class="col-md-4">
          @include('components.otRegStat')
        </div>
        @endif
      <div class="col-md-8 {{!Reg::current()->can('view-regs') ? 'col-md-offset-2' : ''}}">
        @if (Reg::current()->can('view-regs'))
          @include('components.otTodoStatReg')
        @endif
        @if (Reg::current()->can('edit-interviews') || Reg::current()->type == 'interviewer')
          @include('components.otTodoStatInterview')
        @endif
      </div>
      @endif
    </div>  
  </section>
</section>
@endsection
