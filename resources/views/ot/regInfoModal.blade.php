@php
$i = 0;
$regInfo = json_decode($reg->reginfo);
$isExperience = isset($customTable->experience) && in_array($regType, $customTable->experience->uses)
@endphp
<div class="modal-dialog">
      <div class="modal-content">
<header class="header bg-dark bg-gradient">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#info" data-toggle="tab" aria-expanded="true">信息</a></li>
            <li class=""><a href="#events" data-toggle="tab" aria-expanded="false">事件</a></li>
            <li class=""><a href="#interview" data-toggle="tab" aria-expanded="false">面试</a></li>
          </ul>
        </header>
      <div class="tab-content">
        <section class="tab-pane active" id="info">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
              <section class="panel text-sm">
              <div class="panel-body">
                <label>个人信息</label>
                <p><i>姓名</i><br>&emsp;&emsp;{{$reg->user->name}}</p>
                <p><i>邮箱</i><br>&emsp;&emsp;{{$reg->user->email}}</p>
                <p><i>性别及出生日期</i><br>&emsp;&emsp;{{$reg->gender == 'male' ? '男' : '女'}} / {{$regInfo->personinfo->dateofbirth}}</p>
                <p><i>省份</i><br>&emsp;&emsp;海外</p>
                <p><i>学校及毕业年份</i><br>&emsp;&emsp;{{$regInfo->personinfo->school}} / {{$regInfo->personinfo->yearGraduate}}</p>
                <p><i>证件类型及号码</i><br>&emsp;&emsp;护照 (Passport) / {{$regInfo->personinfo->sfz}} </p>
                <p><i>电话</i><br>&emsp;&emsp;{{$regInfo->personinfo->tel}}</p>
                <p><i>QQ</i><br>&emsp;&emsp;123456789</p>                      
                <p><i>Skype</i><br>&emsp;&emsp;chitanda-eru</p>
                <p><i>微信</i><br>&emsp;&emsp;chitanda-eru</p>
                <p><i>紧急联络人</i><br>&emsp;&emsp;{{$regInfo->personinfo->parentname}}</p>
                <p><i>与紧急联络人关系</i><br>&emsp;&emsp;{{$regInfo->personinfo->parentrelation}}</p>
                <p><i>紧急联络人电话</i><br>&emsp;&emsp;{{$regInfo->personinfo->parenttel}}</p>
                <label>参会经历</label>
                <p><i>首次参加模拟联合国活动的年份</i><br>&emsp;&emsp;2012</p>
                <p><i>参会经历 1</i><br>&emsp;&emsp;BJMUNC2017 (地区级会议, 2017年2月)<br>&emsp;&emsp;munpanel test, 无奖项</p>
                <label>会议信息</label>
                <p><i>委员会意向 1</i><br>&emsp;&emsp;危机联动体系<br>&emsp;&emsp;会场意向: 外长团 Foreign Ministers Group, 联合国安全理事会 United Nations Security Council</p>
                <p><i>委员会意向 2</i><br>&emsp;&emsp;東晉縱橫<br>&emsp;&emsp;会场意向: 前秦朝廷</p>
                <p><i>委员会意向 3</i><br>&emsp;&emsp;联合国大会社会、人道主义和文化委员会</p>
                <p><i>面试联络方式</i><br>&emsp;&emsp;Skype</p>
                <p><i>面试选项</i><br>&emsp;&emsp;开通面试短信提醒服务</p>
                <p><i>团队报名选项</i><br>&emsp;&emsp;我以个人身份报名</p>
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
                <ul class="timeline timeline-small">
                @if ($reg->events->count() == 0)
                <p>该用户暂无任何事件。</p>
                @else
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
                @endif
              </div>
            </div>
          </div>          
        </section>
        <section class="tab-pane" id="interview">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
              面试
              </div>
            </div>
          </div>          
        </section>
        </div>
      </div><!-- /.modal-content -->
</div>
