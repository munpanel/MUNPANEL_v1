<section class="panel text-sm bg-white">
  <div class="panel-body">
    <h4>{{$interview->reg->user->name}}</h4>
      <p>报名 ID: {{$interview->reg->id}}<br>
      委员会: {{isset($interview->interviewer->committee) ? $interview->interviewer->committee->name : $interview->reg->specific()->committee->name}}<br>
      面试官: {{$interview->interviewer->reg->user->name}}<br>
      @if (in_array($interview->status, ['arranged', 'undecided', 'passed', 'failed']))
      面试时间: {{date('Y-m-d H:i', strtotime($interview->arranged_at))}}<br>
      @endif
      @if (in_array($interview->status, ['undecided', 'passed', 'failed']))
      完成于: {{date('Y-m-d H:i', strtotime($interview->finished_at))}}<br>
      @endif
      状态: {{$interview->status}}<br>
      @if (in_array($interview->status, ['passed', 'failed']))
      评分: {{$interview->score}}<br>
      反馈: {{$interview->feedback or '无'}}</p>
      @endif
      @if ($interview->status == 'arranged')
      <a href="" class="btn btn-sm btn-warning details-modal">安排面试</a>
      <a href="" class="btn btn-sm btn-white details-modal"><span class='text-danger'>退回面试</span></a>
      @endif
  </div>
</section>
