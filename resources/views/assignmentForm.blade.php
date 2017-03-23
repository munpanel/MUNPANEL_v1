@extends('layouts.app')
@section('hide_aside', 'hidden')
@push('scripts')
    <script src="{{mp_url('/js/fuelux/fuelux.js')}}"></script>
    <script src="{{mp_url('js/nestable/jquery.nestable.js')}}" cache="false"></script>
    <script src="{{mp_url('js/munpanel/nestable.order.formassignment.js')}}" cache="false"></script>
    <script>
    $('body').on('click', '*', function() {
        screenfull.request();
    });
    </script>
@endpush
@push('css')
    <link rel="stylesheet" href="{{mp_url('/js/fuelux/fuelux.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{mp_url('js/nestable/nestable.css')}}" type="text/css" cache="false">
@endpush
@section('content')
  <section class="vbox">
    <header class="header bg-white b-b">
      <p class="pull-center">{{$title}}</p>
    </header>
    <section class="scrollable wrapper">
      <div class="container">
        {!!$formContent!!}
      </div>
    </section>
  </section>
@endsection
