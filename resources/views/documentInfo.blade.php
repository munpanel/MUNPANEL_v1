@extends('layouts.app')
@section('document_active', 'active')
@push('scripts')
    <script src="{{secure_url('/js/fuelux/fuelux.js')}}"></script>
    <script src="{{secure_url('/js/file-input/bootstrap.file-input.js')}}"></script>
@endpush
@push('css')
    <link rel="stylesheet" href="{{secure_url('/js/fuelux/fuelux.css')}}" type="text/css" />
@endpush
@section('content')
      <section class="vbox">
        <!-- TODO: 为上一个/下一个按钮构建功能（如果是首尾项，则隐藏它们） -->
        <header class="header bg-white b-b">
          <p>{{$document->title}}</p>
          <a href="{{secure_url('/document/'.$document->id.'/download')}}" class="btn btn-sm btn-success details-modal pull-right"><i class="fa fa-download"></i> 下载该文件</a>
          <a href="{{secure_url('/document/'.$document->id.'/info.modal')}}" class="btn btn-sm btn-white details-modal pull-right"><i class="fa fa-info"></i> 信息</a>
        </header>
        <section class="scrollable">
          <embed src="{{secure_url('/document/'.$document->id.'/raw')}}" width="100%" height="100%"></embed>
        </section>
      </section>
      <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
    </section>
    <!-- /.vbox -->

@endsection
