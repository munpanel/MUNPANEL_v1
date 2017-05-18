@php
$i = 0;
$regInfo = json_decode($reg->reginfo);
$isOtOrDais = in_array(Reg::current()->type, ['ot', 'dais', 'interviewer']);
$handins = $reg->handins->where('confirm', true);
$events = $reg->events()->orderBy('created_at', 'dsc')->get();
$notes = $reg->notes()->orderBy('created_at', 'dsc')->get();
$interviewers = $reg->interviews()->orderBy('created_at', 'dsc')->get();
$active = request()->active;
if (empty($active))
    $active = 'info';
@endphp
<link href="{{cdn_url('css/jquery.atwho.css')}}" rel="stylesheet">
<div class="modal-dialog">
      <div class="modal-content">
        <header class="header bg-dark bg-gradient">
          <ul class="nav nav-tabs">
            <li id="infoTab"><a href="#info" data-toggle="tab" aria-expanded="false">信息</a></li>
            <li id="handinsTab"><a href="#handins" data-toggle="tab" aria-expanded="false">作业</a></li>
            <li id="eventsTab"><a href="#events" data-toggle="tab" aria-expanded="false">事件</a></li>
            <li id="interviewTab"><a href="#interview" data-toggle="tab" aria-expanded="false">面试</a></li>
            @if ($reg->type == 'delegate')
            <li id="seatsTab"><a href="#seats" data-toggle="tab" aria-expanded="false">席位</a></li>
            @endif
            @if ($isOtOrDais)
            <li id="operationsTab"><a href="#operations" data-toggle="tab" aria-expanded="false">操作</a></li>
            <li id="notesTab"><a href="#notes" data-toggle="tab" aria-expanded="false">笔记</a></li>
            @endif
          </ul>
        </header>
      <div class="tab-content">
        <section class="tab-pane" id="info">
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
                <p>{{$reg->user->name}}以<strong>{{ $reg->type == 'delegate' ? '代表' : ($reg->type == 'observer' ? '观察员' : '志愿者') }}</strong>身份报名参加本次会议。</p>
                <p>报名 ID: {{$reg->id}}
                  @if ($reg->type == 'delegate')
                  <br>委员会: {{$reg->specific()->committee->name}}<br>代表组: {{$reg->specific()->scopeDelegateGroup()}}
                  @endif
                <br>状态: {{$reg->enabled ? $reg->specific()->statusText() : '已禁用'}}</p>
                @endif
              @else
                <p>{{$reg->user->name}}，您已以<strong>{{ $reg->type == 'delegate' ? '代表' : ($reg->type == 'observer' ? '观察员' : '志愿者') }}</strong>身份报名参加{{Reg::currentConference()->fullname}}。</p>
              @endif
              @if (isset($regInfo))
                @include('components.regInfoShow')
              @endif
              </div>
            </div>
          </div>
        </section>
        <section class="tab-pane" id="handins">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
              @if (count($handins) == 0)
              <p>{{$isOtOrDais ? '该用户' : '您'}}还没有提交任何学术作业。</p>
              @else
              <p>{{$isOtOrDais ? '该用户' : '您'}}提交了以下 {{count($handins)}} 项学术作业。</p>
              <table class="table table-striped m-b-none">
                <thead>
                  <tr>
                    <td>学术作业标题</td>
                    <td width="64px">操作</td>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($handins as $handin)
                  <tr>
                    <td>{{$isOtOrDais ? $handin->nameAndInfo() : $handin->assignment->title}}</td>
                    @if ($handin->assignment->handin_type == 'form')
                    <td><a href="JavaScript:newPopup('{{mp_url('/formHandinWindow/'.$handin->id)}}');" class="btn btn-xs btn-info"><i class="fa fa-search-plus"></i> 查看</a></td>
                    @elseif ($handin->assignment->handin_type == 'upload')
                    <td><a href="{{mp_url('/assignment/'.$handin->assignment->id.'/download')}}" class="btn btn-xs btn-success"><i class="fa fa-download"></i> 下载</a></td>
                    @endif
                  </tr>
                  @endforeach
                </tbody>
              </table>
              @endif
              </div>
            </div>
          </div>
        </section>
        <section class="tab-pane" id="events">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                @if ($events->count() == 0)
                <p>该用户暂无任何事件。</p>
                @else
                <ul class="timeline timeline-small">
                  @foreach($events as $event)
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
              @if ($interviewers->count() == 0)
                <p>暂无任何对{{$isOtOrDais ? '该用户' : '您'}}分配的面试。</p>
              @else
              @foreach ($interviewers as $interview)
              <h3 class="m-t-sm">{{$interview->id == $reg->currentInterviewID() ? '当前面试信息' : '早前面试信息'}}</h3>
              <table class="table table-bordered table-striped table-hover">
              <tbody>
              <tr>
              <td style="min-width:80px">状态</td>
              <td>{{$interview->statusText()}}</td>
              </tr>
              <tr>
              <td>面试官</td>
              <td>{{$interview->interviewer->nicename()}}</td>
              </tr>
              <tr>
              <td>分配时间</td>
              <td>{{nicetime($interview->created_at)}}</td>
              </tr>
              @if (!is_null($interview->arranged_at))
              <tr>
              <td>面试时间</td>
              <td>{{nicetime($interview->created_at)}}</td>
              </tr>
              @endif
              @if (!is_null($interview->finished_at))
              <tr>
              <td>完成时间</td>
              <td>{{nicetime($interview->finished_at)}}</td>
              </tr>
              @endif
              @if (in_array($interview->status, ['passed', 'failed', 'undecided']))
              <tr>
              <td>面试评价</td>
              <td>{{$interview->public_fb ?? '无'}}</td>
              </tr>
              @if (Reg::current()->can('view-all-interviews') || Reg::current()->type == 'interviewer')
              <tr>
              <td>面试评分</td>
              <td>{!!$interview->scoreHTML()!!}</td>
              </tr>
              <tr>
              <td>内部评价</td>
              <td>{{$interview->internal_fb ?? '无'}}</td>
              </tr>
              @endif
              @endif
              </tbody>
              </table>
              @endforeach
              @endif
              </div>
            </div>
          </div>
        </section>
        @if ($reg->type == 'delegate')
        <section class="tab-pane" id="seats">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
              <h3 class="m-t-sm">当前席位</h3>
              @if (isset($reg->delegate->nation_id))
              <p>{{$isOtOrDais ? '该用户' : '您'}}已{{$reg->delegate->seat_locked ? '锁定':'选择'}}席位<strong>{{$reg->delegate->nation->name}}</strong>。</p>
              @else
              <p>{{$isOtOrDais ? '该用户' : '您'}}还没有选择任何席位。</p>
              @endif
              @if ($reg->delegate->assignedNations->count() > 0)
              <h3>可供选择席位列表</h3>
              <form method="post" id="updateSeatForm">
              <input type="hidden" name="id" value="{{$reg->id}}">
              <table class="table table-bordered table-striped table-hover">
              <tbody>
              <tr>
              <td style="width:30px">ID</td>
              <td>席位名称</td>
                @if ($isOtOrDais)
                <td>席位组</td>
                <td width="45px">保留</td>
                @endif
                @if (!$reg->delegate->seat_locked && Reg::currentID() == $reg->id)
                <td width="45px">选择</td>
                @endif
              </tr>
              @php
              $nations = $reg->delegate->assignedNations;
              $i = 0;
              @endphp
              @foreach($nations as $nation)
              <tr>
              <td><center>{{++$i}}</center></td>
              <td>{{$nation->name}}</td>
                @if ($isOtOrDais)
                <td>{{isset($nation->nationgroups) ? $nation->scopeNationGroup(true, 2) : '无'}}</td>
                <td><center><input type="checkbox" name="seats[]" value="{{$nation->id}}" checked></center></td>
                @endif
                @if (!$reg->delegate->seat_locked && Reg::currentID() == $reg->id)
                <td><center><input type="radio" name="seatSelect" value="{{$nation->id}}" {{$reg->delegate->nation_id == $nation->id ? 'checked':''}}></center></td>
                @endif
              </tr>
              @endforeach
              </tbody>
              </table>
              <button type="submit" class="btn btn-success" onclick="loader(this)">保存更改</button>
              </form>
              @endif
              </div>
            </div>
          </div>
        </section>
        @endif
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
        <section class="tab-pane" id="notes">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                <ul class="timeline timeline-small">
                  <li>
                    <div class="timeline-badge"><i class="fa fa-user fa-fw"></i></div>{{--TODO: 插入笔记者 gravatar 头像--}}
                    <div class="timeline-panel">
                      <form method="post" action="{{mp_url('/newNote')}}" id="add_notes_form">
                        <div class="timeline-heading">
                          <button class="btn btn-sm btn-success pull-right m-b-xs m-t-n-xs" type="submit" onclick="loader(this)">添加笔记</button>
                          <h4 class="timeline-title">{{Reg::current()->name()}}&nbsp;<small class="text-muted">{!!Auth::user()->identityHTML()!!}</small></h4>
                        </div>
                        <div class="timeline-body">
                          {{csrf_field()}}
                          <input type="hidden" name="reg_id" value="{{$reg->id}}">
                          <textarea name="text" id="add_notes" class="form-control" type="text" data-required="true" data-trigger="change" style="width:100%" placeholder="添加对{{$reg->user->name}}的笔记..." autocomplete="off"></textarea>
                        </div>
                      </form>
                    </div>
                  </li>
                  @foreach($notes as $note)
                  <li>
                    <div class="timeline-badge"><i class="fa fa-user fa-fw"></i></div>{{--TODO: 插入笔记者 gravatar 头像--}}
                    <div class="timeline-panel">
                      <div class="timeline-heading">
                        <h4 class="timeline-title">{{$note->noter->name}}&nbsp;<small class="text-muted">{!!$note->noter->identityHTML()!!}<br><i class="fa fa-clock-o fa-fw"></i>{{nicetime($note->created_at)}}</small></h4>
                      </div>
                      <div class="timeline-body">
                        <p>{{$note->content}}</p>
                      </div>
                    </div>
                  </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>
        </section>
        @endif
        </div>
      </div><!-- /.modal-content -->
</div>
<script type="text/javascript">
$('#{{$active}}').addClass('active');
$('#{{$active}}Tab').addClass('active');
$('#{{$active}}Tab > a').attr('aria-expanded', 'true');
function newPopup(url) {
  popupWindow = window.open(
    url,'popUpWindow','height=750,width=600,left=10,top=10,resizable=no,scrollbars=yes,toolbar=no,menubar=no,location=no,directories=no,status=yes')
}
$('#updateSeatForm').submit(function(e){
    e.preventDefault();
    $.post('{{mp_url('/ot/updateSeat')}}', $('#updateSeatForm').serialize()).done(function(data) {
        $("#ajaxModal").load("{{mp_url('/ot/regInfo.modal/'.$reg->id.'?active=seats')}}");
    });
});
</script>
@if ($isOtOrDais)
<!--script src="{{cdn_url('js/jquery.caret.js')}}"></script>
<script src="{{cdn_url('js/jquery.atwho.js')}}"></script-->
<script>
$(document).ready(function(){
    $(".interviewer-list").select2();
    $.getScript( "{{cdn_url('js/jquery.caret.js')}}", function( data, textStatus, jqxhr  ) {
        $.getScript( "{{cdn_url('js/jquery.atwho.js')}}", function( data, textStatus, jqxhr  ) {
            $('#add_notes').atwho({
                at: "@",
                data: "{{mp_url('ajax/atwhoList')}}",
                displayTpl: "<li>${name} <small>${position}</small></li>",
                insertTpl: "@(${id})${name}",
            });
        });
    });
});
$('#add_notes_form').submit(function(e){
    e.preventDefault();
    $.post('{{mp_url('/newNote')}}', $('#add_notes_form').serialize()).done(function(data) {
        $("#ajaxModal").load("{{mp_url('/ot/regInfo.modal/'.$reg->id.'?active=notes')}}");
    });
});
</script>
@endif
