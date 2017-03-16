@push('scripts')
    <script src="{{mp_url('/js/fuelux/fuelux.js')}}"></script>
    <script src="js/sortable/jquery.sortable.js"></script>
    <script src="{{mp_url('js/nestable/jquery.nestable.js')}}" cache="false"></script>
    <script src="{{mp_url('js/munpanel/nestable.order.formassignment.js')}}" cache="false"></script>
@endpush
@push('css')
    <link href="{{mp_url('/js/fuelux/fuelux.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{mp_url('js/nestable/nestable.css')}}" type="text/css" cache="false">
@endpush
@extends('layouts.app')
@section('content')
<div class="container">
    @if ($convert)
    {!!$testContent!!}
    @else
    {{$testContent}}
    @endif
</div>
@endsection
