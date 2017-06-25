@extends('layouts.app')
@section('teams_active', 'active')
@push('scripts')
    <script src="{{cdn_url('/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{cdn_url('/js/datatables/fnReloadAjax.js')}}"></script>
    <script src="{{cdn_url('/js/editable/bootstrap-editable.js')}}"></script>
    <script src="{{mp_url('/js/portal.teamConferences.js')}}"></script>
@endpush
@push('css')
    <link rel="stylesheet" href="{{cdn_url('/css/jquery.dataTables.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{cdn_url('/css/bootstrap-editable.css')}}" type="text/css" />
@endpush
@section('content')
<section class="vbox">
            <header class="header bg-white b-b clearfix">
              <div class="row m-t-sm">
                <div class="col-sm-6 m-b-xs">
                  <!--a href="#subNav" data-toggle="class:hide" class="btn btn-sm btn-info"><i class="fa fa-caret-right text fa fa-large"></i><i class="fa fa-caret-left text-active fa fa-large"></i></a-->
                  <a href="{{mp_url('/teams/'.$team->id.'/admin')}}" class="btn btn-sm btn-success">BACK</a>
                  <a href="{{mp_url('/teams/'.$team->id.'/admin/conferences/add.modal')}}" class="btn btn-sm btn-info" data-toggle="ajaxModal">允许参加新的会议</a>
                </div>
                <div class="col-sm-6 m-b-xs">
                  <div class="input-group">
                    <input type="text" class="input-sm form-control" placeholder="搜索" id="searchbox">
                    <span class="input-group-btn">
                      <button class="btn btn-sm btn-white" type="button" id="searchButton">Go!</button>
                    </span>
                  </div>
                </div>
              </div>
            </header>
            <section class="scrollable wrapper w-f">
              <section class="panel">
                <div class="table-responsive">
                  <table class="table table-striped m-b-none" id="conference-table">
                    <thead>
                      <tr>
                        <th>名称</th>
                        <th>参与人数</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </section>
            </section>
            <footer class="footer bg-white b-t">
              <div class="row m-t-sm text-center-xs">
                <div class="col-sm-4">
                  <div class="dataTables_length" id="conference-table_length_new"><label>每页 <select name="conference-table_length" id="conference-length-select"aria-controls="conference-table" class=""><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> 条记录</label></div>
                </div>
                <div class="col-sm-4 text-center">
                  <small class="text-muted inline m-t-sm m-b-sm" id="conference-pageinfo"></small>
                </div>
                <div class="col-sm-4 text-right text-center-xs">                
                  <ul class="pagination pagination-sm m-t-none m-b-none" id="conference-pagnination">
                  </ul>
                </div>
              </div>
            </footer>
          </section>
@endsection
