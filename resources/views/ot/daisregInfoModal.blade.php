@php
$i = 0;
$regInfo = json_decode($reg->reginfo);
$isOtOrDais = Reg::current()->type == 'ot' && Reg::current()->specific()->status == 'success';
$isot = Reg::current()->type == 'ot';
@endphp
<div class="modal-dialog">
      <div class="modal-content">
        <header class="header bg-dark bg-gradient">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#info" data-toggle="tab" aria-expanded="true">信息</a></li>
            <li class=""><a href="#form" data-toggle="tab" aria-expanded="false">申请题</a></li>
            <li class=""><a href="#events" data-toggle="tab" aria-expanded="false">事件</a></li>
            <li class=""><a href="#interview" data-toggle="tab" aria-expanded="false">面试</a></li>
            @if ($isOtOrDais)
            <li class=""><a href="#operations" data-toggle="tab" aria-expanded="false">操作</a></li>
            @endif
          </ul>
        </header>
      <div class="tab-content">
        <section class="tab-pane active" id="info">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
              @if ($isOtOrDais)
                @if ($allRegs->count() > 1)
                <p>{{$reg->user->name}}在本次会议中共包含以下 {{$allRegs->count()}} 个身份。</p>
                <ul>
                  @foreach ($allRegs as $aReg)
                  <li><strong>{{$aReg->id}}</strong>: {{$aReg->regText()}}</li>
                  @endforeach
                </ul>
                @else
                <p>{{$reg->user->name}}已提交本次会议{{$isot ? '会务' : '学术'}}团队申请。</p>
                <p>报名 ID: {{$reg->id}}
                <br>状态: {{$reg->enabled ? $reg->specific()->statusText() : '已禁用'}}</p>
                @endif
              @else
                <p>{{$reg->user->name}}，您已成功提交{{Reg::currentConference()->fullname}}{{$isot ? '会务' : '学术'}}团队申请。</p>
              @endif
              @if (isset($regInfo))
                @include('components.regInfoShow')
              @endif
              </div>
            </div>
          </div>
        </section>
        <section class="tab-pane" id="form">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
              @if (!empty($formContent))
              <p>{{$isOtOrDais ? '该用户' : '您'}}已经填写了{{$formTitle}}。</p>
              @include('components.formAnswer')
              @else
              <p>该用户仍未填写申请表单！</p>
              @endif
              </div>
            </div>
          </div>
        </section>
        <section class="tab-pane" id="events">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                @if ($reg->events->count() == 0)
                <p>该用户暂无任何事件。</p>
                @else
                <ul class="timeline timeline-small">
                  @foreach($reg->events as $event)
                  <li>
                    <div class="timeline-badge {{$event->eventtype->level}}"><i class="fa fa-{{$event->eventtype->icon}} fa-fw"></i></div>
                    <div class="timeline-panel">
                      <div class="timeline-heading">
                        <h4 class="timeline-title">{{$event->eventtype->title}}<small class="text-muted"><i class="fa fa-clock-o fa-fw"></i>{{nicetime($event->created_at)}}</small></h4>
                      </div>
                      <div class="timeline-body">
                        {{$event->text()}}
                      </div>
                    </div>
                  </li>
                  @endforeach
                </ul>
                @endif
              </div>
            </div>
          </div>          
        </section>
        <section class="tab-pane" id="interview">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
              @if ($reg->interviews->count() == 0)
                <p>暂无任何对{{$isOtOrDais ? '该用户' : '您'}}分配的面试。</p>
              @else
              @foreach ($reg->interviews()->orderBy('created_at', 'dsc')->get() as $interview)
              <h3 class="m-t-sm">{{$interview->id == $reg->currentInterviewID() ? '当前面试信息' : '早前面试信息'}}</h3>
              <table class="table table-bordered table-striped table-hover">
              <tbody>
              <tr>
              <td>状态</td>
              <td>{{$interview->status}}</td>
              </tr>
              <tr>
              <td>面试官</td>
              <td>{{$interview->interviewer->nicename()}}</td>
              </tr>
              <tr>
              <td>分配时间</td>
              <td>{{nicetime($interview->created_at)}}</td>
              </tr>
              </tbody>
              </table>
              @endforeach
              @endif
              </div>
            </div>
          </div>
        </section>
        @if ($isOtOrDais)
        <section class="tab-pane" id="operations">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
              @if (isset($operations) && count($operations)>0)
              @foreach ($operations as $operation)
              @include('operations.'.$operation)
              @endforeach
              @else
              您暂无任何可执行的操作！
              @endif
              </div>
            </div>
          </div>          
        </section>
        @endif
        </div>
      </div><!-- /.modal-content -->
</div>
<script type="text/javascript">
$(document).ready(function() {
//      $(".interviewer-list").select2();
});
</script>
