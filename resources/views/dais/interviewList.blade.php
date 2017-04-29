@php
$canInterview = Auth::user()->regs->where('conference_id', Reg::currentConferenceID())->where('type', 'interviewer')->where('enabled', true)->count();
@endphp
@extends('layouts.app')
@section('interview_active', 'active')
@push('scripts')
<script src="{{cdn_url('js/fuelux/fuelux.js')}}"></script>
<script src="{{cdn_url('js/moment.min.js')}}"></script>
<script src="{{cdn_url('js/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{cdn_url('js/markdown/epiceditor.js')}}"></script>  
<script src="{{cdn_url('/js/select2/select2.min.js')}}"></script>
<script src="{{cdn_url('js/readmore.min.js')}}"></script>
<script>
$('.readmore').readmore({
  collapsedHeight: 16,
  speed: 200
});
</script>
@endpush
@push('css')
<link href="{{cdn_url('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{cdn_url('js/select2/select2.css')}}" type="text/css" />
@endpush
@section('content')
<section class="vbox">
  <header class="header b-b bg-white">
    @if ($iid == -1)
      <p>{{Reg::currentConference()->name}} 的所有面试</p>
    @elseif ($iid == 0)
      <p>您的面试队列</p>
    @else
      <p>{{Reg::find($iid)->user->name}}的面试队列</p>
    @endif
    @permission('view-all-interviews')
    <div class="btn-group pull-right">
      <button class="btn btn-white btn-sm dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><i class="fa fa-eye"></i> 查看 <span class="caret"></span></button>
      <ul class="dropdown-menu">
        <li><a href="{{mp_url('/findInterviewer.modal')}}" data-toggle="ajaxModal">查看面试官的队列...</a></li>
        @if ($iid != -1 || $canInterview > 0)
        <li class="divider"></li>
        @endif
        @if ($iid != -1)
        <li><a href="{{mp_url('/interviews/-1')}}">查看所有面试</a></li>
        @endif
        @if ($iid != 0)
        @foreach(Auth::user()->regs->where('conference_id', Reg::currentConferenceID())->where('enabled', true) as $reg)
        @if ($reg->type == 'interviewer')
        <li><a href="{{mp_url('/doSwitchIdentity/'.$reg->id)}}">查看我的面试</a></li>
        @endif
        @endforeach
        @endif
      </ul>
    </div>
    @if ($interviews->where('status', 'cancelled')->count() > 0)
    <a href="" class="btn btn-white btn-sm pull-right m-r-xs"><i class="fa fa-deaf"></i> 查看已取消的面试</a>
    @endif
    @endpermission
  </header>
  <section class="scrollable wrapper">
    @if ($interviews->count() == 0)
    <div class="container">
      @if ($iid == -1)
      <p>本次会议并没有任何面试安排。</p>
      @else
      <p>{{$iid != 0 ? Reg::find($iid)->user->name : '您'}}的面试队列空空如也。</p>
      @endif
    </div>
    @else
    <div class="row">
      <div class="col-md-4">
        <section class="panel pos-rlt clearfix">
          <header class="panel-heading">
            <ul class="nav nav-pills pull-right">
              <li>
                <a class="panel-toggle text-muted" href="#"><i class="fa fa-caret-down text-active"></i><i class="fa fa-caret-up text"></i></a>
              </li>
            </ul>
            未安排的面试 ({{$interviews->where('status', 'assigned')->count()}})
          </header>
          <div class="panel-body clearfix">
            @if ($interviews->where('status', 'assigned')->count() > 0)
              @foreach ($interviews->where('status', 'assigned')->sortByDesc('updated_at') as $interview)
                @include('components.interview')
              @endforeach
            @else
              @if ($iid == -1)
              <p>本次会议目前没有未安排的面试。</p>
              @else
              <p>{{$iid != 0 ? Reg::find($iid)->user->name : '您'}}没有未安排的面试。</p>
              @endif
            @endif
          </div>
        </section>
      </div>
      <div class="col-md-4">
        <section class="panel pos-rlt clearfix">
          <header class="panel-heading">
            <ul class="nav nav-pills pull-right">
              <li>
                <a class="panel-toggle text-muted" href="#"><i class="fa fa-caret-down text-active"></i><i class="fa fa-caret-up text"></i></a>
              </li>
            </ul>
            未完成的面试 ({{$interviews->whereIn('status', ['arranged', 'undecided'])->count()}})
          </header>
          <div class="panel-body clearfix">
            @if ($interviews->whereIn('status', ['arranged', 'undecided'])->count() > 0)
              @foreach ($interviews->whereIn('status', ['arranged', 'undecided'])->sortByDesc('updated_at') as $interview)
                @include('components.interview')
              @endforeach
            @else
              @if ($iid == -1)
              <p>本次会议目前没有未完成的面试。</p>
              @else
              <p>{{$iid != 0 ? Reg::find($iid)->user->name : '您'}}没有未完成的面试。</p>
              @endif
            @endif
          </div>
        </section>
      </div>
      <div class="col-md-4">
        <section class="panel pos-rlt clearfix">
          <header class="panel-heading">
            <ul class="nav nav-pills pull-right">
              <li>
                <a class="panel-toggle text-muted" href="#"><i class="fa fa-caret-down text-active"></i><i class="fa fa-caret-up text"></i></a>
              </li>
            </ul>
            已完成的面试 ({{$interviews->whereIn('status', ['passed', 'failed', 'exempted'])->count()}})
          </header>
          <div class="panel-body clearfix">
            @if ($interviews->whereIn('status', ['passed', 'failed', 'exempted'])->count() > 0)
              @foreach ($interviews->whereIn('status', ['passed', 'failed', 'exempted'])->sortByDesc('updated_at') as $interview)
                @include('components.interview')
              @endforeach
            @else
              @if ($iid == -1)
              <p>本次会议目前没有已完成的面试。</p>
              @else
              <p>{{$iid != 0 ? Reg::find($iid)->user->name : '您'}}没有已完成的面试。</p>
              @endif
            @endif
          </div>
        </section>
      </div>
    </div>
    @endif
  </section>
</section>
@endsection
