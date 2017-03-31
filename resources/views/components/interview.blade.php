<section class="panel text-sm bg-white">
  <div class="panel-body">
    @if (isset($interview->score))
    <h4 class="pull-right {{$interview->status == 'passed' ? 'text-success' : 'text-danger'}}"><strong>{{$interview->score}}</strong></h4>
    @endif
    <h4><a href="{{mp_url('/ot/regInfo.modal/'.$interview->reg->id)}}" class="" data-toggle="ajaxModal">{{$interview->reg->user->name}}</a></h4>
      <p>报名 ID: {{$interview->reg->id}}<br>
      委员会: {{isset($interview->interviewer->committee) ? $interview->interviewer->committee->name : $interview->reg->specific()->committee->name}}<br>
      面试官: {{$interview->interviewer->reg->user->name}}<br>
      @if (in_array($interview->status, ['arranged', 'undecided', 'passed', 'failed']))
      面试时间: {{date('Y-m-d H:i', strtotime($interview->arranged_at))}}<br>
      @endif
      @if (in_array($interview->status, ['undecided', 'passed', 'failed']))
      完成于: {{date('Y-m-d H:i', strtotime($interview->finished_at))}}<br>
      @endif
      状态: {{$interview->statusText()}}<br>
      @if (in_array($interview->status, ['arranged', 'undecided', 'exempted', 'cancelled']))      
      面试备注: {{$interview->arranging_notes or '无'}}
      @endif
      @if (in_array($interview->status, ['passed', 'failed']))
      评分: {{$interview->score}}<br>
      反馈: {{$interview->public_fb or '无'}}
        @if (!empty($interview->internal_fb))
        <br>内部反馈: {{$interview->internal_fb or '无'}}
        @endif
      @endif
    </p>
    @if (Reg::current()->type == 'interviewer')
        @if ($interview->status == 'assigned')
        <a href="{{mp_url('/interview/'.$interview->id.'/arrangeModal')}}" class="btn btn-xs btn-warning pull-right" data-toggle="ajaxModal">安排面试</a>
        <a href="{{mp_url('/interview/'.$interview->id.'/exemptModal')}}" class="btn btn-xs btn-info pull-right m-r-xs" data-toggle="ajaxModal">免试通过</a>
        <a href="{{mp_url('/interview/'.$interview->id.'/rollBackModal')}}" class="btn btn-xs btn-white pull-right m-r-xs" data-toggle="ajaxModal"><span class='text-danger'>退回面试</span></a>
        @elseif ($interview->status == 'arranged')
          @if (strtotime(date('Y-m-d H:i:s')) < strtotime($interview->arranged_at) - 1800)
          <a href="{{mp_url('/interview/'.$interview->id.'/cancelModal')}}" class="btn btn-xs btn-danger pull-right" data-toggle="ajaxModal">取消面试</a>
          @elseif (strtotime(date('Y-m-d H:i:s')) > strtotime($interview->arranged_at))
          <a href="{{mp_url('/interview/'.$interview->id.'/rateModal')}}" class="btn btn-xs btn-info pull-right" data-toggle="ajaxModal">评分</a>
          @else
          <div class="btn btn-xs btn-danger pull-right mp-disabled" data-original-title="距离面试已不足 30 分钟，不允许取消面试！" data-toggle="tooltip" data-placement="left">取消面试</div>
          @endif
        @elseif (in_array($interview->status, ['passed', 'exempted']))
        <a href="" class="btn btn-xs btn-success pull-right" data-toggle="ajaxModal">分配席位</a>
        @endif
    @endif
  </div>
</section>
