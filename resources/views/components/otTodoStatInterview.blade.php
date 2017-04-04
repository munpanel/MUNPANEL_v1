@php
$myself = Reg::current();
foreach(Auth::user()->regs->where('conference_id', Reg::currentConferenceID())->where('enabled', true) as $reg)
    if ($reg->type == 'interviewer')
        $interviews = interviewStat(Reg::currentConferenceID(), $reg->id);
@endphp
<div class="panel">
  <div class="panel-heading">
    待办事项统计 (我的面试)
  </div>
  <div class="panel-body">
    <div class="text-center col-sm-3">
      <a href="{{mp_url('/interviews')}}">
      <small class="text-muted block">未安排面试</small>
      <h4>{{$interviews['unarranged']}}</h4>
      <div class="inline">
        <div class="easypiechart easyPieChart" data-size="100" data-line-width="4" data-percent="{{$interviews['all'] > 0 ? ($interviews['arranged'] * 100 / $interviews['all']) : 0}}" data-loop="false">
          <span class="h3">0</span>%
          <div class="easypie-text">已安排</div>
          <canvas></canvas>
        </div>
      </div></a>
    </div>
    <div class="text-center col-sm-3">
      <a href="{{mp_url('/interviews')}}">
      <small class="text-muted block">等待面试队列</small>
      <h4>{{$interviews['unfinished']}}</h4>
      <div class="inline">
        <div class="easypiechart easyPieChart" data-size="100" data-line-width="4" data-percent="{{$interviews['arranged'] > 0 ? ($interviews['finished'] * 100 / ($interviews['unfinished'] + $interviews['finished'])) : 0}}" data-loop="false">
          <span class="h3">0</span>%
          <div class="easypie-text">面试完成</div>
          <canvas></canvas>
        </div>
      </div></a>
    </div>
    <div class="text-center col-sm-3">
      <small class="text-muted block">等待分配席位</small>
      <h4>{{$interviews['passed']}}</h4>
      <div class="inline">
        <div class="easypiechart easyPieChart" data-size="100" data-line-width="4" data-percent="0" data-loop="false">
          <span class="h3">0</span>%
          <div class="easypie-text">已分配</div>
          <canvas></canvas>
        </div>
      </div>
    </div>
    <div class="text-center col-sm-3">
      <small class="text-muted block">未选择席位</small>
      <h4>0</h4>
      <div class="inline">
        <div class="easypiechart easyPieChart" data-size="100" data-line-width="4" data-percent="0" data-loop="false">
          <span class="h3">0</span>%
          <div class="easypie-text">已选席位</div>
          <canvas></canvas>
        </div>
      </div>
    </div>
  </div>
 </div>
