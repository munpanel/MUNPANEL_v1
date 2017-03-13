@php
$i = 0;
$regInfo = json_decode($reg->reginfo);
$isOtOrDais = in_array(Reg::current()->type, ['ot', 'dais']);
@endphp
<div class="modal-dialog">
      <div class="modal-content">
        <header class="header bg-dark bg-gradient">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#info" data-toggle="tab" aria-expanded="true">信息</a></li>
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
                <p>{{$reg->user->name}}在本次会议中共包含{{ $allRegs->count() }}个身份。</p>
                @else
                <p>{{$reg->user->name}}以<strong>{{ $reg->type == 'delegate' ? '代表' : ($reg->type == 'observer' ? '观察员' : '志愿者') }}</strong>身份报名参加本次会议。</p>
                @endif
              @else
                <p>{{$reg->user->name}}，您已以<strong>{{ $reg->type == 'delegate' ? '代表' : ($reg->type == 'observer' ? '观察员' : '志愿者') }}</strong>身份报名参加{{Reg::currentConference()->fullname}}。</p>
              @endif
                <section class="panel text-sm">
                  <div class="panel-body">
                    <label>个人信息</label>
                    <p><i>姓名</i><br>&emsp;&emsp;{{$reg->user->name}}</p>
                    <p><i>邮箱</i><br>&emsp;&emsp;{{$reg->user->email}}</p>
                    <p><i>性别及出生日期</i><br>&emsp;&emsp;{{$reg->gender == 'male' ? '男' : '女'}} / {{$regInfo->personinfo->dateofbirth}}</p>
                    <p><i>省份</i><br>&emsp;&emsp;{{province($regInfo->personinfo->province)}}</p>
                    <p><i>学校及毕业年份</i><br>&emsp;&emsp;{{$regInfo->personinfo->school}} / {{$regInfo->personinfo->yearGraduate}}</p>
                    <p><i>证件类型及号码</i><br>&emsp;&emsp;{{typeID($regInfo->personinfo->typeDocument)}} / {{$regInfo->personinfo->sfz}} </p>
                    <p><i>电话</i><br>&emsp;&emsp;{{$regInfo->personinfo->tel}}</p>
                    @if (!empty($regInfo->personinfo->alt_phone))                
                    <p><i>备用电话</i><br>&emsp;&emsp;{{$regInfo->personinfo->alt_phone}}</p>
                    @endif
                    @if (!empty($regInfo->personinfo->qq)) 
                    <p><i>QQ</i><br>&emsp;&emsp;{{$regInfo->personinfo->qq}}</p>
                    @endif
                    @if (!empty($regInfo->personinfo->skype)) 
                    <p><i>Skype</i><br>&emsp;&emsp;{{$regInfo->personinfo->skype}}</p>
                    @endif
                    @if (!empty($regInfo->personinfo->wechat)) 
                    <p><i>微信</i><br>&emsp;&emsp;{{$regInfo->personinfo->wechat}}</p>
                    @endif
                    <p><i>紧急联络人</i><br>&emsp;&emsp;{{$regInfo->personinfo->parentname}} ({{$regInfo->personinfo->parentrelation}}, {{$regInfo->personinfo->parenttel}})</p>
                    @if (isset($regInfo->experience))
                    <label>参会经历</label>
                      @if (isset($regInfo->experience->startYear))
                      <p><i>首次参加模拟联合国活动的年份</i><br>&emsp;&emsp;{{$regInfo->experience->startYear}}</p>
                      @endif
                      @if (isset($regInfo->experience->item))
                        @foreach ($regInfo->experience->item as $item)
                        <p><i>参会经历 {{++$i}}</i><br>&emsp;&emsp;{{$item->name}} ({{levelOfConfs($item->level)}}, {{$item->dates}})<br>&emsp;&emsp;{{$item->role}}, {{$item->award or '无奖项'}}</p>
                        @endforeach
                      @endif                
                    @endif
                    <label>会议信息</label>
                    <p><i>TODO: 报名信息</i><br>&emsp;&emsp;{{json_encode($regInfo->conference)}}</p>{{--
                    <p><i>委员会意向 1</i><br>&emsp;&emsp;{{App\Committee::find($regInfo->conference->committee)->name}}</p>
                    <p><i>委员会意向 2</i><br>&emsp;&emsp;{{App\Committee::find($regInfo->conference->committee2)->name}}</p>
                    <p><i>委员会意向 3</i><br>&emsp;&emsp;{{App\Committee::find($regInfo->conference->committee3)->name}}</p>
                    <p><i>面试联络方式</i><br>&emsp;&emsp;{{--typeInterview($regInfo->conference->typeInterview)--}}</p>
                    @if (isset($regInfo->conference->smsInterview) || isset($regInfo->conference->offlineInterview))
		    <p><i>面试选项</i><br>&emsp;&emsp;{{isset($regInfo->conference->smsInterview) ? '开通面试短信提醒服务' : ''}}{{isset($regInfo->conference->smsInterview) && isset($regInfo->conference->offlineInterview) ? ', ' : ''}}{{isset($regInfo->conference->offlineInterview) ? '接受线下面试' : ''}}</p>
                    @endif--}}
                    @if (isset($regInfo->conference->groupOption))
                    <p><i>团队报名选项</i><br>&emsp;&emsp;{{groupOption($regInfo->conference->groupOption)}}</p>
                    @endif
                    @if (isset($regInfo->conference->remarks))
                    <p><i>备注</i><br>&emsp;&emsp;{{$regInfo->conference->remarks}}</p>
                    @endif
                  </div>
                </section>
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
              <h3>{{$interview->id == $reg->currentInterviewID() ? '当前面试信息' : '早前面试信息'}}</h3>
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
      $(".interviewer-list").select2();
});
</script>
