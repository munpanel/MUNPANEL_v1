@extends('layouts.app')
@section('assignment_active', 'active')
@push('scripts')
    <script src="{{secure_url('/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{secure_url('/js/assignmentsList.js')}}"></script>
@endpush
@push('css')
    <link rel="stylesheet" href="{{secure_url('/css/jquery.dataTables.min.css')}}" type="text/css" />
@endpush
@section('content')
<section class="vbox">
      <header class="header b-b bg-white">
        @if ($type == 'dais') 
          <a href="{{secure_url('/dais/documentDetails.modal/new')}}" class="btn btn-sm btn-success details-modal"><i class="fa fa-plus"></i> 新建</a> 
        @else 
          <p>您的学术作业列表</p>
        @endif 
      </header>
      <section class="scrollable wrapper w-f">
        <section class="panel">
          <div class="table-responsive">
            <table class="table table-striped m-b-none" id="assignment-table">
              <thead>
                <tr>
                  <th width="{{$type == 'dais' ? '40' : '20'}}">{{$type == 'dais' ? '操作' : ''}}</th> 
                  <th width="20">#</th>
                  <th width="55%">学术作业标题</th>
                  <th>提交期限</th>
                  <!--th width="30"></th-->
                </tr>
              </thead>
            </table>
          </div>
        </section>
      </section>
            <footer class="footer bg-white b-t">
              <div class="row m-t-sm text-center-xs">
                <div class="col-sm-12">
                  <small class="text-muted inline m-t-sm m-b-sm" id="assignment-pageinfo"></small>
                </div>
              </div>
            </footer>
          </section>
@endsection
