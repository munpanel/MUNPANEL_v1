@php
$otregs = otregStat(Reg::currentConferenceID());
@endphp
<div class="panel">
  <div class="panel-heading">
    待办事项统计 {{validateRegAvaliable('dais') ? '(会务团队申请)' : ''}}
  </div>
  <div class="panel-body">
    <div class="text-center col-sm-3">
      <small class="text-muted block">申请待审核</small>
      <h4>{{$otregs['oUnverified']}}</h4>
      <div class="inline">
        <div class="easypiechart easyPieChart" data-size="100" data-line-width="4" data-percent="{{$otregs['all'] > 0 ? ($otregs['oVerified'] * 100 / ($otregs['oVerified'] + $otregs['oUnverified'])) : 0}}" data-loop="false">
          <span class="h3">0</span>%
          <div class="easypie-text">已审核</div>
          <canvas></canvas>
        </div>
      </div>
    </div>
    <div class="text-center col-sm-3">
      <small class="text-muted block">未安排面试</small>
      <h4>0</h4>
      <div class="inline">
        <div class="easypiechart easyPieChart" data-size="100" data-line-width="4" data-percent="0" data-loop="false">
          <span class="h3">0</span>%
          <div class="easypie-text">已安排</div>
          <canvas></canvas>
        </div>
      </div>
    </div>
    <div class="text-center col-sm-3">
      <small class="text-muted block">申请待复核</small>
      <h4>0</h4>
      <div class="inline">
        <div class="easypiechart easyPieChart" data-size="100" data-line-width="4" data-percent="0" data-loop="false">
          <span class="h3">0</span>%
          <div class="easypie-text">已复核</div>
          <canvas></canvas>
        </div>
      </div>
    </div>
    <div class="text-center col-sm-3">
      <small class="text-muted block">申请已批准</small>
      <h4>{{$otregs['success']}}</h4>
      <div class="inline">
        <div class="easypiechart easyPieChart" data-size="100" data-line-width="4" data-percent="{{$otregs['all'] > 0 ? ($otregs['success'] * 100 / $otregs['all']) : 0}}" data-loop="false">
          <span class="h3">0</span>%
          <div class="easypie-text">通过率</div>
          <canvas></canvas>
        </div>
      </div>
    </div>
  </div>
</div>
