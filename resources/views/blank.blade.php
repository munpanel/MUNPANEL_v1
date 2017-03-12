@push('scripts')
    <script src="{{mp_url('/js/fuelux/fuelux.js')}}"></script>
    <script src="js/sortable/jquery.sortable.js"></script>
@endpush
@push('css')
    <link href="{{mp_url('/js/fuelux/fuelux.css')}}" rel="stylesheet">
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
