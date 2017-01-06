﻿@extends('layouts.app')
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
          <div class="row m-t-sm">
            <div class="col-sm-4 m-b-xs">
              <a href="" class="btn btn-sm btn-success details-modal"><i class="fa fa-arrow-left"></i> 上一个</a>
            </div>
            <div class="col-sm-4 m-b-xs">
              <p class="pull-center">{{$document->title}}</p>
            </div>
            <div class="col-sm-4 m-b-xs">
              <a href="" class="btn btn-sm btn-success details-modal pull-right">下一个 <i class="fa fa-arrow-left"></i></a>
            </div>
          </div>
        </header>
        <section class="scrollable wrapper">
          <div class="row">
            <div class="col-lg-8">
              <p>TODO: 在此处构建 PDF 阅读器</p>
            </div>
            <div class="col-lg-4">
              <!-- .accordion -->
              <div class="panel-group m-b" id="accordion2">
                <div class="panel">
                  <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
                      文件概况
                    </a>
                  </div>
                  <div id="collapseOne" class="panel-collapse in">
                    <div class="panel-body text-sm">
                      <b>文件标题: </b>{{$document->title}}<br><b>分发对象: </b>{{$document->scope()}}<br><b>发布日期: </b>{{$document->created_at}}<br>
                    </div>
                  </div>
                </div>
                <div class="panel">
                  <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                      文件信息
                    </a>
                  </div>
                  <div id="collapseTwo" class="panel-collapse collapse">
                    <div class="panel-body text-sm">
                      {!!$document->description!!}
                    </div>
                  </div>
                </div>
                <div class="panel">
                  <div class="panel-heading">
                    <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                      文件下载
                    </a>
                  </div>
                  <div id="collapseTwo" class="panel-collapse collapse">
                    <div class="panel-body text-sm">
                      点击这里下载该学术文件。<br><a href="{{secure_url('/document/'.$document->id.'/download')}}"}" class="btn btn-sm btn-success details-modal"><i class="fa fa-download"></i> 下载{{$document->title}}</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </section>
      <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
    </section>
    <!-- /.vbox -->

@endsection