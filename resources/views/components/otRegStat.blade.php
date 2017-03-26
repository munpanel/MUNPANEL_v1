<div class="panel">
  <div class="panel-heading">会议报名情况<span class="pull-right">代表总数: {{$del}}</span>
    @if ($hasChildComm)
    <button class="btn btn-xs btn-white m-l active" id="nestable-menu" data-toggle="class:show">
      <i class="fa fa-plus text"></i>
      <span class="text">全部展开</span>
      <i class="fa fa-minus text-active"></i>
      <span class="text-active">全部折叠</span>
    </button>
    @endif
  </div>
  <div class="panel-body">
    {!!regStat($committees, $obs, $vol)!!}
  </div>
</div>
