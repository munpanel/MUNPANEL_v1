@extends('layouts.app')
@section('hide_aside', 'hidden')
@push('scripts')
    <script src="{{cdn_url('/js/fuelux/fuelux.js')}}"></script>
    <script src="{{cdn_url('js/nestable/jquery.nestable.js')}}" cache="false"></script>
    <script src="{{cdn_url('js/munpanel/nestable.order.formassignment.js')}}" cache="false"></script>
    <script>
    $('body').on('click', '*', function() {
        screenfull.request();
    });
    $('textarea').blur(function() {
        jQuery.post('{{mp_url($target)}}', $('#assignmentForm').serialize());
    });
    $('#confirm').click(function() {
        loader(this);
        jQuery.post('{{mp_url($target."/true")}}', $('#assignmentForm').serialize());
    });
    </script>
@endpush
@push('css')
    <link rel="stylesheet" href="{{cdn_url('/js/fuelux/fuelux.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{cdn_url('js/nestable/nestable.css')}}" type="text/css" cache="false">
@endpush
@section('content')
  <section class="vbox">
    <header class="header bg-white b-b">
      <p>{{$title}}</p>
    </header>
    <section class="scrollable wrapper">
      <div class="container">
        {!!$formContent!!}
      </div>
    </section>
  </section>
@endsection
