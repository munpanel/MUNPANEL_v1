@extends('layouts.app')
@if (Reg::current()->type == 'delegate')
    @section('home_active', 'active')
@else
    @section('roles_active', 'active')
@endif
@push('scripts')
    <script src="{{mp_url('/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{mp_url('/js/datatables/fnReloadAjax.js')}}"></script>
    <script src="{{mp_url('/js/roleList.js')}}"></script>
@endpush
@push('css')
    <link rel="stylesheet" href="{{mp_url('/css/jquery.dataTables.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{mp_url('/css/bootstrap-editable.css')}}" type="text/css" />
@endpush
@section('content')
<section class="vbox">
            <header class="header bg-white b-b clearfix">
              <div class="row m-t-sm">
                <div class="col-sm-6 m-b-xs">
                  @if (Reg::current()->type == 'delegate')
                  <a href="{{mp_url('/home')}}" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> 返回首页</a>
                  @else
                  <p>BJMUNC2017 {{Reg::current()->specific()->committee->display_name}} 席位分配表</p>
                  @endif
                  <!--a href="{{mp_url('/ot/nationDetails.modal/new')}}" class="btn btn-sm btn-white details-modal"><i class="fa fa-plus"></i> 添加国家</a>
                  @if ($view == 'nation')
                  <div class="btn-group">
                    <a class="btn btn-sm btn-white details-modal"><i class="fa fa-address-book"></i> 查看代表名单</a>
                    <a class="btn btn-sm btn-white dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="#">以代表视图显示</a></li>
                    </ul>
                  </div>
                  @else
                  <div class="btn-group">
                    <a class="btn btn-sm btn-white details-modal"><i class="fa fa-wheelchair"></i> 查看席位列表</a>
                    <a class="btn btn-sm btn-white dropdown-toggle" aria-expanded="false" data-toggle="dropdown"><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                      <li><a href="#">以席位视图显示</a></li>
                    </ul>
                  </div>
                  @endif-->                  
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
                        <!--th width="20"></th-->
                        <!--th class="th-sortable" data-toggle="class">Project
                          <span class="th-sort">
                            <i class="fa fa-sort-down text"></i>
                            <i class="fa fa-sort-up text-active"></i>
                            <i class="fa fa-sort"></i>
                          </span>
                        </th-->
                        <!--th width="20">#</th-->
                        <th>名称</th>
                        <!--th width="20">C</th>
                        <th width="20">VP</th-->
                        <th>所属国家组</th>
                        <th>代表</th>
                        <!--th>学校</th-->
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
