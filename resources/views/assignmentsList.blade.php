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
          <p>BJMUNC2017&nbsp;&nbsp;{{$committee->display_name}}&nbsp;&nbsp;学术作业清单</p>
      </header>
      <section class="scrollable wrapper w-f">
        <section class="panel">
          <div class="table-responsive">
            <table class="table table-striped m-b-none" id="assignment-table">
              <thead>
                <tr>
                  <th width="30"></th>
                  <th width="30">#</th>
                  <th>学术作业标题</th>
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
