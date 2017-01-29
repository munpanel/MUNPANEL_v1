@extends('layouts.app')
@section('roleAlloc_active', 'active')
@push('scripts')
    <script src="{{secure_url('/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{secure_url('/js/datatables/fnReloadAjax.js')}}"></script>
    <script src="{{secure_url('/js/editable/bootstrap-editable.js')}}"></script>
    <script src="{{secure_url('/js/dais.roleAllocDelegates.js')}}"></script>
    <script src="{{secure_url('/js/dais.roleAllocNations.js')}}"></script>
</script>

@endpush
@push('css')
    <link rel="stylesheet" href="{{secure_url('/css/jquery.dataTables.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{secure_url('/css/bootstrap-editable.css')}}" type="text/css" />
@endpush
@section('content')

      <section class="vbox">
            <header class="header bg-white b-b clearfix">
              <a href="{{secure_url('dais/lockAlloc')}}" class="btn btn-sm btn-success {{$mustAlloc > 0 ? 'disabled ': ''}}pull-right" id="ra-confirm"><i class="fa fa-check"></i> 完成并锁定</a> 
              <p>BJMUNC2017 {{$committee->display_name}} 席位分配</p>
            </header><section class="scrollable wrapper">
          <div class="tab-content">
            <div class="tab-pane active" id="static">
              <div class="row">
                <div class="col-sm-6">
                  <section class="panel">
                    <header class="panel-heading">
                      @if ($mustAlloc > 0)
                        <span class="badge bg-danger pull-right">剩余 {{$mustAlloc}} 人必须分配</span>
                      @endif
                      所有代表</header>
                    <div class="row text-sm wrapper">
                  <div class="col-sm-12 m-b-xs">本委员会有 {{$committee->delegates->count()}} 人报名，其中 {{$verified}} 人已通过组织团队审核，{{$committee->delegates->where('status', 'paid')->count()}} 人已缴费。已缴费代表中，当前仍有 {{$committee->delegates->where('status', 'paid')->where('nation_id', null)->count()}} 人未分配席位。<br>
                  @if ($mustAlloc > 0)
                    已缴费代表中，当前仍有 {{$committee->delegates->where('status', 'paid')->where('nation_id', null)->count()}} 人未分配席位。<br>
                    <strong class="text-danger">您必须为仍无席位的已缴费的 {{$mustAlloc}} 人分配席位，否则席位分配无法完成。</strong>
                  @else
                    <br><strong class="text-success">所有已缴费的代表均已分配席位，请点击“完成并锁定”按钮以完成席位分配。</strong>
                  @endif
                  </div>
                    
                  <div class="col-sm-9 m-b-xs">
                    <div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-sm btn-white active">
                        <input name="view-delegate" id="paid" type="radio"> 报名成功
                      </label>
                      <!--label class="btn btn-sm btn-white">
                        <input name="view-delegate" id="verified" type="radio"> 已审核通过
                      </label-->
                      <label class="btn btn-sm btn-white">
                        <input name="view-delegate" id="no-alloc" type="radio"> 待分配
                      </label>
                      <label class="btn btn-sm btn-white">
                        <input name="view-delegate" id="must-alloc" type="radio"> 必须分配
                      </label>
                      <label class="btn btn-sm btn-white">
                        <input name="view-delegate" id="all" type="radio"> 全部
                      </label>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="input-group">
                      <input class="input-sm form-control" type="text" id="delegate-searchBox" placeholder="搜索">
                      <span class="input-group-btn">
                        <button class="btn btn-sm btn-white" type="button" id="delegate-searchButton">Go!</button>
                      </span>
                    </div>
                  </div>
                </div><table class="table table-striped m-b-none text-sm" id="delegate-table">
                      <thead>
                        <tr>
                          <th>姓名</th>
                          <th>学校</th>
                          <th>席位</th>
                          <th width="70">操作</th>
                        </tr>
                      </thead>
                    </table>
                    <footer class="footer bg-white b-t" id="delegate-table_length_new">
              <div class="row m-t-sm text-center-xs">
                <div class="col-sm-4">
                  <div class="dataTables_length" id="delegate-table_length_new"><label>每页 <select name="delegate-table_length" id="delegate-length-select" aria-controls="delegate-table"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> 项</label></div>
                </div>
                <div class="col-sm-4 text-center">
                  <small class="text-muted inline m-t-sm m-b-sm" id="delegate-pageinfo"></small>
                </div>
                <div class="col-sm-4 text-right text-center-xs">                
                  <ul class="pagination pagination-sm m-t-none m-b-none" id="delegate-pagnination"><li><a tabindex="0" class="paginate_button previous disabled" id="delegate-table_previous" aria-controls="delegate-table" href="#" data-dt-idx="0"><i class="fa fa-chevron-left"></i></a></li><li><a tabindex="0" class="paginate_button current" aria-controls="delegate-table" href="#" data-dt-idx="1">1</a></li><li><a tabindex="0" class="paginate_button next disabled" id="delegate-table_next" aria-controls="delegate-table" href="#" data-dt-idx="2"><i class="fa fa-chevron-right"></i></a></li></ul>
                </div>
              </div>
            </footer>
                  </section>
                </div>
                <div class="col-sm-6">
                  <section class="panel">
                    <header class="panel-heading">
                  <a href="{{secure_url('/dais/nationDetails.modal/new')}}" class="btn btn-xs btn-white details-modal pull-right">添加席位</a>
                  @if ($emptyNations > 0)
                    <span class="pull-right">&nbsp;</span><span class="badge bg-warning pull-right">{{$emptyNations}} 个空席位</span>
                  @endif
                  所有席位</header><div class="row text-sm wrapper">
                  <div class="col-sm-12 m-b-xs">本委员会有 {{$committee->nations->count()}} 个席位，剩余 {{$emptyNations}} 个席位未分配代表。<br>
                  @if ($mustAlloc > 0 && $emptyNations > 0)
                    <strong class="text-info">当前剩余 {{$emptyNations}} 个空席位可分配给已报名成功的 {{$mustAlloc}} 名无席位代表。</strong>
                  @elseif ($emptyNations > 0)
                    <strong class="text-warning">如果您选择锁定席位分配，这 {{$emptyNations}} 个空席位将会删除。</strong>
                  @elseif ($mustAlloc > 0)
                    <strong class="text-danger">所有席位均已分配代表，但仍有 {{$mustAlloc}} 名已缴费的代表需要分配席位。请添加更多的席位。</strong>
                  @else
                    <strong class="text-success">所有席位均已分配代表，请点击“完成并锁定”按钮以完成席位分配。</strong>
                  @endif
                  </div> 
                  <div class="col-sm-9 m-b-xs">
                    <div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-sm btn-white active">
                        <input name="view-nation" id="empty" type="radio"> 空席位
                      </label>
                      <!-- @if($isDouble) -->
                      <label class="btn btn-sm btn-white">
                        <input name="view-nation" id="single" type="radio"> 单人席位
                      </label>
                      <!-- @endif -->
                      <label class="btn btn-sm btn-white">
                        <input name="view-nation" id="all" type="radio"> 全部
                      </label>
                    </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="input-group">
                      <input class="input-sm form-control" type="text" id="nation-searchBox" placeholder="搜索">
                      <span class="input-group-btn">
                        <button class="btn btn-sm btn-white" type="button" id="nation-searchButton">Go!</button>
                      </span>
                    </div>
                  </div>
                </div>
                <form id="seatform">
                    {{ csrf_field() }}
                    <table class="table table-striped m-b-none text-sm" id="nation-table">
                      <thead>
                        <tr>
                          <th width="20"><i class="fa fa-check-circle-o"></i></th>
                          <th>席位</th>
                          <th>国家组</th>
                          <th>代表</th> 
                          <th width="150">操作</th>
                        </tr>
                      </thead></table>
              </form>
                    <footer class="footer bg-white b-t">
              <div class="row m-t-sm text-center-xs">
                <div class="col-sm-4">
                  <div class="dataTables_length" id="nation-table_length_new"><label>每页 <select name="nation-table_length" id="nation-length-select" aria-controls="nation-table"><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="100">100</option></select> 项</label></div>
                </div>
                <div class="col-sm-4 text-center">
                  <small class="text-muted inline m-t-sm m-b-sm" id="nation-pageinfo"></small>
                </div>
                <div class="col-sm-4 text-right text-center-xs">                
                  <ul class="pagination pagination-sm m-t-none m-b-none" id="nation-pagnination"><li><a tabindex="0" class="paginate_button previous disabled" id="nation-table_previous" aria-controls="nation-table" href="#" data-dt-idx="0"><i class="fa fa-chevron-left"></i></a></li><li><a tabindex="0" class="paginate_button current" aria-controls="nation-table" href="#" data-dt-idx="1">1</a></li><li><a tabindex="0" class="paginate_button next disabled" id="nation-table_next" aria-controls="nation-table" href="#" data-dt-idx="2"><i class="fa fa-chevron-right"></i></a></li></ul>
                </div>
              </div>
            </footer>
                  </section>
                </div>
              </div>
            </div>
          </div>
        </section>
      </section>
@endsection
