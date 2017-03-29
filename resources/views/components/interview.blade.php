<section class="panel text-sm bg-white">
  <div class="panel-body">
    @if (isset($interview->score))
    <h4 class="pull-right {{$interview->status == 'passed' ? 'text-success' : 'text-danger'}}"><strong>{{$interview->score}}</strong></h4>
    @endif
    <h4><a href="{{mp_url('/ot/regInfo.modal/'.$interview->reg->id)}}" class="details-modal" data-toggle="ajaxModal">{{$interview->reg->user->name}}</a></h4>
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
      @if (in_array($interview->status, ['passed', 'failed']))
      评分: {{$interview->score}}<br>
      反馈: {{$interview->feedback or '无'}}<br>
      @endif
    </p>
    @if ($interview->status == 'assigned')
    <a href="" class="btn btn-xs btn-warning details-modal pull-right">安排面试</a>
    <a href="" class="btn btn-xs btn-white details-modal pull-right m-r-xs"><span class='text-danger'>退回面试</span></a>
    @elseif ($interview->status == 'arranged')
      @if (strtotime(date('Y-m-d H:i:s')) < strtotime($interview->arranged_at) - 1800)
      <a href="" class="btn btn-xs btn-danger details-modal pull-right">取消面试</a>
      @elseif (strtotime(date('Y-m-d H:i:s')) > strtotime($interview->arranged_at))
      <a href="" class="btn btn-xs btn-info details-modal pull-right">评分</a>
      @else
      <div class="btn btn-xs btn-danger pull-right mp-disabled" data-original-title="距离面试已不足 30 分钟，不允许取消面试！" data-toggle="tooltip" data-placement="left">取消面试</div>
      @endif
    @elseif (in_array($interview->status, ['passed', 'exempted']))
    <a href="" class="btn btn-xs btn-success details-modal pull-right">分配席位</a>
    @endif
  </div>
</section>
