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
                <p>{{$reg->user->name}}在本次会议中共包含以下{{ $allRegs->count() }}个身份。</p>
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

              @foreach ($reg->interviews as $interview)
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
                <div id="pre_select" style="display: block;">
                    <h3>安排面试</h3>

                    {{-- <p><span class="label label-warning">注意</span> 这是二次面试分配。</p> --}}
                    <p>此代表已经通过审核，将需要为其安排面试。</p>
                    <p>点击<strong>分配面试</strong>按钮后，将会出现可选择的面试官列表，您可以分配一位面试官面试此代表。</p>
                    <p>如果此代表具有规定的免试资格，可以以免试通过方式完成此代表的面试流程。点击<strong>免试通过</strong>按钮后，将会出现可选择的面试官列表，您需要分配一位面试官为此代表分配席位。</p>

                    {{-- <p><span class="label label-warning">注意</span> 这位代表的面试安排曾被join("、", $rollback_data); 回退，请在笔记中了解回退原因。</p>--}}

                    <button name="" type="button" class="btn btn-info" onclick="$('#do_assign').show(); $('#pre_select').hide();">分配面试</button>
                    <button name="" type="button" class="btn btn-info" onclick="$('#do_exempt').show(); $('#pre_select').hide();">免试通过</button>

                </div>

                <div id="do_assign" style="display: none;">
                    <h3>分配面试官</h3>

                    {{-- <p><span class="label label-warning">注意</span> 这是二次面试分配。</p>--}}

                    <p>请在此列表中选择面试官，面试官姓名右侧显示了面试官当前分配的未完成面试数量。</p>

                    <form action="{{secure_url('/ot/assignInterview/'.$reg->id)}}" method="post">
                    {{csrf_field()}}

                          <div class="m-b">
                            <select style="width:260px" class="interviewer-list" name="interviewer">
                                @foreach (\App\Interviewer::list() as $name => $group)
                                <optgroup label="{{$name}}">
                                    @foreach ($group as $iid => $iname)
                                    <option value="{{$iid}}">{{$iname}}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                          </div>

                        <p>分配完成之后，MUNPANEL 将自动通知代表和面试官。</p>

                   <button name="submit" type="submit" class="btn btn-info">分配面试官</button>
                   <button name="cancel" type="button" class="btn btn-link" onclick="$('#do_assign').hide(); $('#pre_select').show();">取消</button>

                   </form>

                </div>

                <div id="do_exempt" style="display: none;">
                    <h3>免试指派席位</h3>

                    <p>将会以免试通过方式完成此代表的面试流程，请在此列表中选择面试官，选定的面试官将可以直接为此代表分配席位。</p>

                    <form action="{{secure_url('/ot/exemptInterview/'.$reg->id)}}" method="post">
                    {{csrf_field()}}

                          <div class="m-b">
                            <select style="width:260px" class="interviewer-list" name="interviewer">
                                @foreach (\App\Interviewer::list() as $name => $group)
                                <optgroup label="{{$name}}">
                                    @foreach ($group as $iid => $iname)
                                    <option value="{{$iid}}">{{$iname}}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                          </div>

                        <p>指派之后，MUNPANEL 将会自动通知代表和面试官。</p>
                        <button name="submit" type="submit" class="btn btn-info" onclick="">免试通过并分配面试官</button>
                        <button name="cancel" type="button" class="btn btn-link" onclick="$('#do_exempt').hide(); $('#pre_select').show();">取消</button>

                        </form>

                </div>
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
