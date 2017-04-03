@extends('layouts.app')
@section('nationManage_active', 'active')
@push('scripts')
    <script src="{{cdn_url('/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{cdn_url('/js/datatables/fnReloadAjax.js')}}"></script>
    <script src="{{cdn_url('/js/editable/bootstrap-editable.js')}}"></script>
    <script src="{{cdn_url('/js/ot.nationManage.js')}}"></script>
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
                  <a href="{{mp_url('/ot/nationDetails.modal/new')}}" class="btn btn-sm btn-success details-modal"><i class="fa fa-plus"></i> 新建</a>
                  <a href="{{mp_url('/ot/nationDetails.modal/bulkAdd')}}" class="btn btn-sm btn-success details-modal"><i class="fa fa-plus"></i> 批量创建</a>
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
                  <table class="table table-striped m-b-none" id="nation-table">
                    <thead>
                      <tr>
                        <!--th width="20"><input type="checkbox"></th-->
                        <th width="20"></th>
                        <!--th class="th-sortable" data-toggle="class">Project
                          <span class="th-sort">
                            <i class="fa fa-sort-down text"></i>
                            <i class="fa fa-sort-up text-active"></i>
                            <i class="fa fa-sort"></i>
                          </span>
                        </th-->
                        <th width="20">ID</th>
                        <th width="150">委员会</th>
                        <th width="150">名称</th>
                        <th width="20"><span data-toggle="tooltip" data-original-title="投票权重" data-placement="bottom">C</span></th>
                        <th width="20"><span data-toggle="tooltip" data-original-title="一票否决权" data-placement="bottom">VP</span></th>
                        <th>所属国家组</th>
                        <th>代表</th>
                      </tr>
                    </thead>
                  </table>
                </div>
              </section>
            </section>
            <footer class="footer bg-white b-t">
              <div class="row m-t-sm text-center-xs">
                <div class="col-sm-4">
                  <div class="dataTables_length" id="nation-table_length_new"><label>每页 <select name="nation-table_length" id="nation-length-select"aria-controls="nation-table" class=""><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> 条记录</label></div>
                </div>
                <div class="col-sm-4 text-center">
                  <small class="text-muted inline m-t-sm m-b-sm" id="nation-pageinfo"></small>
                </div>
                <div class="col-sm-4 text-right text-center-xs">                
                  <ul class="pagination pagination-sm m-t-none m-b-none" id="nation-pagnination">
                  </ul>
                </div>
              </div>
            </footer>
          </section>
@endsection
