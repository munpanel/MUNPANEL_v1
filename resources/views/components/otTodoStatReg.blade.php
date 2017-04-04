@php
$regs = oVerifyStat(Reg::currentConferenceID());
$interviews = interviewStat(Reg::currentConferenceID(), -1);
@endphp
<div class="panel">
  <div class="panel-heading">
    待办事项统计 (报名)
  </div>
  <div class="panel-body">
    <div class="text-center col-sm-3">
      <a href="{{mp_url('/regManage')}}">
      <small class="text-muted block">报名待审核</small>
      <h4>{{$regs['oUnverified']}}</h4>
      <div class="inline">
        <div class="easypiechart easyPieChart" data-size="100" data-line-width="4" data-percent="{{$regs['all'] > 0 ? ($regs['oVerified'] * 100 / $regs['all']) : 0}}" data-loop="false">
          <span class="h3">0</span>%
          <div class="easypie-text">已审核</div>
          <canvas></canvas>
        </div>
      </div></a>
    </div>
    @if (Reg::current()->can('view-all-interviews'))
    <div class="text-center col-sm-3">
      <a href="{{mp_url('/regManage')}}">
      <small class="text-muted block">未分配面试代表</small>
      <h4>{{$regs['delOVerify'] - $regs['interviews']}}</h4>
      <div class="inline">
        <div class="easypiechart easyPieChart" data-size="100" data-line-width="4" data-percent="{{$regs['delOVerify'] > 0 ? ($regs['interviews'] * 100 / $regs['delOVerify']) : 0}}" data-loop="false">
          <span class="h3">0</span>%
          <div class="easypie-text">已分配</div>
          <canvas></canvas>
        </div>
      </div></a>
    </div>
    @endif
    <div class="text-center col-sm-3">
      <small class="text-muted block">未安排面试代表</small>
      <h4>{{$interviews['unarranged']}}</h4>
      <div class="inline">
        <div class="easypiechart easyPieChart" data-size="100" data-line-width="4" data-percent="{{$interviews['all'] > 0 ? ($interviews['arranged'] * 100 / $interviews['all']) : 0}}" data-loop="false">
          <span class="h3">0</span>%
          <div class="easypie-text">已安排</div>
          <canvas></canvas>
        </div>
      </div>
    </div>
    <div class="text-center col-sm-3">
      <small class="text-muted block">未选择席位代表</small>
      <h4>{{$interviews['passed']}}</h4>
      <div class="inline">
        <div class="easypiechart easyPieChart" data-size="100" data-line-width="4" data-percent="0" data-loop="false">
          <span class="h3">0</span>%
          <div class="easypie-text">已选席位</div>
          <canvas></canvas>
        </div>
      </div>
    </div>
    @if (Reg::current()->can('approve-regs-pay'))
    <div class="text-center col-sm-3">
      <small class="text-muted block">未缴费人员</small>
      <h4>0</h4>
      <div class="inline">
        <div class="easypiechart easyPieChart" data-size="100" data-line-width="4" data-percent="0" data-loop="false">
          <span class="h3">0</span>%
          <div class="easypie-text">已缴费</div>
          <canvas></canvas>
        </div>
      </div>
    </div>
    @endif
  </div>
 </div>
