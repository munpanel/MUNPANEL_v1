@push('scripts')
    <script src="{{secure_url('/js/fuelux/fuelux.js')}}"></script>
    <script src="js/sortable/jquery.sortable.js"></script>
@endpush
@push('css')
    <link href="{{secure_url('/js/fuelux/fuelux.css')}}" rel="stylesheet">
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
