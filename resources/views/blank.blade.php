@push('scripts')
    <script src="{{cdn_url('/js/fuelux/fuelux.js')}}"></script>
    <script src="{{cdn_url('js/sortable/jquery.sortable.js')}}"></script>
    <script src="{{cdn_url('js/nestable/jquery.nestable.js')}}" cache="false"></script>
    <script src="{{cdn_url('js/munpanel/nestable.order.formassignment.js')}}" cache="false"></script>
@endpush
@push('css')
    <link href="{{cdn_url('/js/fuelux/fuelux.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{cdn_url('js/nestable/nestable.css')}}" type="text/css" cache="false">
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
