@extends('layouts.app')
@section('hide_aside', 'hidden')
@push('scripts')
    <script src="{{mp_url('/js/fuelux/fuelux.js')}}"></script>
    <script src="{{mp_url('js/sortable/jquery.sortable.js')}}'"></script>
    <script>
    $(document).ready(function() {{
      if (screenfull.enabled) {
        screenfull.request();
      }
    });
    </script>
@endpush
@push('css')
    <link rel="stylesheet" href="{{mp_url('/js/fuelux/fuelux.css')}}" type="text/css" />
@endpush
@section('content')
  <section class="vbox">
    <header class="header bg-white b-b">
      <p class="pull-center">{{$assignment->title}}</p>
    </header>
    <section class="scrollable wrapper">
      <div class="container">
        {!!$formContent!!}
      </div>
    </section>
  </section>
@endsection