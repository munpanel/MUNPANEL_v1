<section class="panel text-sm bg-white">
  <div class="panel-body">
    @if (isset($interview->score))
    <h4 class="pull-right {{$interview->status == 'passed' ? 'text-success' : 'text-danger'}}"><strong>{{$interview->score}}</strong></h4>
    @endif
    <h4><a href="{{mp_url('/ot/regInfo.modal/'.$interview->reg->id.'?active=interview')}}" class="" data-toggle="ajaxModal">{{$interview->reg->user->name}}</a></h4>
      <p>代表状态: {{$interview->reg->statusText()}}<br>
      报名 ID: {{$interview->reg->id}}<br>
      委员会: {{isset($interview->interviewer->committee) ? $interview->interviewer->committee->name : $interview->reg->specific()->committee->name}}<br>
      面试官: <a href="{{mp_url('ot/regInfo.modal/'.$interview->interviewer->reg_id)}}" data-toggle="ajaxModal">{{$interview->interviewer->nicename()}}<i class="fa fa-search-plus"></i></a><br>
      @if (in_array($interview->status, ['arranged', 'undecided', 'passed', 'failed']))
      面试时间: {{isset($interview->arranged_at)?nicetime($interview->arranged_at):'未经系统安排'}}<br>
      @endif
      @if (in_array($interview->status, ['undecided', 'passed', 'failed']))
      完成于: {{nicetime($interview->finished_at)}}<br>
      @endif
      状态: {{$interview->statusText()}}<br>
      @if (!empty($interview->arranging_notes))
      面试备注: {{$interview->arranging_notes}}<br>
      @endif
      @if (in_array($interview->status, ['passed', 'failed']))
      评分: {!!$interview->scoreHTML()!!}<br>
    </p>
      反馈: <div class="readmore">{{$interview->public_fb or '无'}}</div>
      @if (!empty($interview->internal_fb))
        <br>内部反馈: <div class="readmore">{{$interview->internal_fb or '无'}}</div>
      @endif
      @endif
    @if (Reg::current()->can('view-all-interviews') && Reg::current()->type == 'ot')
        <a href="{{mp_url('/interview/'.$interview->id.'/editModal')}}" class="btn btn-xs btn-white pull-right m-r-xs" data-toggle="ajaxModal"><span class='text-danger'>编辑面试</span></a>
    @endif
    @if ($interview->interviewer_id == Reg::currentID())
        @if ($interview->status == 'assigned')
        <a href="{{mp_url('/interview/'.$interview->id.'/arrangeModal')}}" class="btn btn-xs btn-warning pull-right" data-toggle="ajaxModal">安排面试</a>
        <a href="{{mp_url('/interview/'.$interview->id.'/exemptModal')}}" class="btn btn-xs btn-success pull-right m-r-xs" data-toggle="ajaxModal">免试通过</a>
        <a href="{{mp_url('/interview/'.$interview->id.'/rateModal')}}" class="btn btn-xs btn-info pull-right m-r-xs" data-toggle="ajaxModal">直接评分</a>
        <a href="{{mp_url('/interview/'.$interview->id.'/rollBackModal')}}" class="btn btn-xs btn-white pull-right m-r-xs" data-toggle="ajaxModal"><span class='text-danger'>退回面试</span></a>
        @elseif ($interview->status == 'arranged')
{{--      @if (strtotime(date('Y-m-d H:i:s')) < strtotime($interview->arranged_at) - 1800)
          <a href="{{mp_url('/interview/'.$interview->id.'/cancelModal')}}" class="btn btn-xs btn-danger pull-right" data-toggle="ajaxModal">取消面试</a>
          @elseif (strtotime(date('Y-m-d H:i:s')) > strtotime($interview->arranged_at))
          <a href="{{mp_url('/interview/'.$interview->id.'/rateModal')}}" class="btn btn-xs btn-info pull-right" data-toggle="ajaxModal">评分</a>
          @else
          <div class="btn btn-xs btn-danger pull-right mp-disabled" data-original-title="距离面试已不足 30 分钟，不允许取消面试！" data-toggle="tooltip" data-placement="left">取消面试</div>
          @endif--}}
          <a href="{{mp_url('/interview/'.$interview->id.'/rateModal')}}" class="btn btn-xs btn-info pull-right" data-toggle="ajaxModal">评分</a>
          <a href="{{mp_url('/interview/'.$interview->id.'/cancelModal')}}" class="btn btn-xs btn-danger pull-right m-r-xs" data-toggle="ajaxModal">取消面试</a>
        @elseif ($interview->status == 'undecided')
          <a href="{{mp_url('/interview/'.$interview->id.'/rateModal')}}" class="btn btn-xs btn-info pull-right" data-toggle="ajaxModal">决定结果</a>
        {{--@elseif (in_array($interview->status, ['passed', 'exempted']))
        <a href="" class="btn btn-xs btn-success pull-right" data-toggle="ajaxModal">分配席位</a>--}}
        @endif
    @endif
  </div>
</section>
