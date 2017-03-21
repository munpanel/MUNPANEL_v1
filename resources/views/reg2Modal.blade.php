@php
$isExperience = isset($customTable->experience) && in_array($regType, $customTable->experience->uses)
@endphp
<div class="modal-dialog">
  <div class="modal-content">
    <div class="panel wizard" id="reg2Wizard">
      <div class="wizard-steps clearfix">
        <ul class="steps">
          <li class="active" data-target="#step1"><span class="badge badge-info">1</span>个人信息</li>
          @if ($isExperience)
          <li data-target="#step2"><span class="badge">2</span>参会经历</li>
          <li data-target="#step3"><span class="badge">3</span>会议信息</li>
          <!--li data-target="#step4"><span class="badge">4</span>确认</li>
          <li data-target="#step5"><span class="badge">5</span>完成</li-->
          @else
          <li data-target="#step2"><span class="badge">2</span>会议信息</li>
          <!--li data-target="#step3"><span class="badge">3</span>确认</li>
          <li data-target="#step4"><span class="badge">4</span>完成</li-->
          @endif
        </ul>
      </div>
      <div class="step-content clearfix">
        <form id="reg2Form" class="m-b-sm" action="{{ mp_url('/saveReg2') }}" method="post">
          <div class="step-pane active" id="step1">
            {{csrf_field()}}
            <input type="hidden" name="reg_id" value="{{Reg::currentID()}}">
            <input type="hidden" name="conference_id" value="{{Reg::current()->conference_id}}">
            <input type="hidden" name="type" value="{{ $regType }}">
            <div class="form-group">
              <label>姓名 *</label>
              @if (Auth::check())
              <input type="hidden" name="user_id" value="{{ Auth::id() }}">
              <input name="name" disabled="" class="form-control" type="text" value="{{ Auth::user()->name }}" data-required="true" data-trigger="change">
              <span class="help-block m-b-none">如需编辑请退出登录或联系客服</span>
              @else
              <input name="name" class="form-control" type="text" data-required="true" data-trigger="change">
              @endif
            </div>
            <div class="form-group">
              <label>邮箱 *</label>
              @if (Auth::check())
              <input name="email" disabled="" class="form-control" type="text" value="{{ Auth::user()->email }}" data-type="email" data-required="true" data-trigger="change">
              <span class="help-block m-b-none">如需编辑请退出登录或联系客服</span>
              @else
              <input name="email" class="form-control" type="text" data-type="email" data-required="true" data-trigger="change">
              @endif
            </div>
            <div class="form-group form-inline">
              <label>性别 *</label>
              <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-sm btn-info" onclick="$('input#male').checked = true;">
                  <input name="gender" id="male" type="radio" value="male" data-required="true"> <i class="fa fa-check text-active"></i>男
                </label>
                <label class="btn btn-sm btn-success" onclick="$('input#female').checked = true;">
                  <input name="gender" id="female" type="radio" value="female"> <i class="fa fa-check text-active"></i>女
                </label>
              </div>
              <label>　出生日期 *</label>
              <input name="dateofbirth" class="datepicker-input form-control input" type="text" size="16" placeholder="yyyy-mm-dd" data-type="dateIso" data-required="true">
              <label>　省份 *</label>
              {!!provinceSelect()!!}
            </div>
            <div class="form-group pull-in clearfix">
              <div class="col-sm-9">
              <label>学校 *</label>
                <input name="school" class="form-control" type="text" data-required="true">
              </div>
              <div class="col-sm-3">
                <label>毕业年份 *</label>
                <input name="yearGraduate" class="form-control" type="text" data-required="true">
              </div>
            </div>
            <div class="form-group pull-in clearfix">
              <div class="col-sm-4">
              <label>证件类型及号码 *</label>
               <select id="select-typeID" name="typeDocument" class="form-control" data-required="true">
                 <option value="1" selected="">居民身份证</option>
                 <option value="2">护照 (Passport)</option>
                 <option value="3">港澳回乡证</option>
                 <option value="4">台胞证</option>
                 <option value="5">Social Security Number</option>
               </select>
              </div>
              <div class="col-sm-8">
                <label>&nbsp;</label>
                <input id="text-sfz" name="sfz" class="form-control" type="text" placeholder="中国大陆 18 位身份证号（末尾 X 大写）" data-required="true" pattern="^(\d{6})(\d{4})(\d{2})(\d{2})(\d{3})([0-9]|X)$">
              </div>
            </div>
            <div class="form-group">
              <label>电话 *</label>
              <input name="tel" class="form-control" type="text" data-required="true">
            </div>
            @if (isset($customTable->info->contact->alt_phone))
            <div class="form-group">
              <label>备用电话{{$customTable->info->contact->alt_phone == 'mandatory' ? ' *' : ''}}</label>
              <input name="tel2" class="form-control" type="text" placeholder="选填；如果您的主电话号码无法使用，我们将通过备用电话联系您" {{$customTable->info->contact->alt_phone == 'mandatory' ? 'data-required="true"' : ''}}>
            </div>
            @endif
            @if (isset($customTable->info->contact->qq))
            <div class="form-group">
              <label>QQ{{$customTable->info->contact->qq == 'mandatory' ? ' *' : ''}}</label>
              <input name="qq" class="form-control" type="text" {{$customTable->info->contact->qq == 'mandatory' ? 'data-required="true"' : ''}}>
            </div>
            @endif
            @if (isset($customTable->info->contact->skype))
            <div class="form-group">
              <label>Skype{{$customTable->info->contact->skype == 'mandatory' ? ' *' : ''}}</label>
              <input name="skype" class="form-control" type="text" {{$customTable->info->contact->skype == 'mandatory' ? 'data-required="true"' : ''}}>
            </div>
            @endif
            @if (isset($customTable->info->contact->wechat))
            <div class="form-group">
              <label>微信{{$customTable->info->contact->wechat == 'mandatory' ? ' *' : ''}}</label>
              <input name="wechat" class="form-control" type="text" {{$customTable->info->contact->wechat == 'mandatory' ? 'data-required="true"' : ''}}>
            </div>
            @endif
            <div class="form-group pull-in clearfix">
              <div class="col-sm-6">
              <label>紧急联络人 *</label>
                <input name="parentname" class="form-control" type="text" data-required="true">
              </div>
              <div class="col-sm-6">
                <label>与紧急联络人关系 *</label>
                <input name="parentrelation" class="form-control" type="text" data-required="true">
              </div>
            </div>
            <div class="form-group">
              <label>紧急联络人电话 *</label>
              <input name="parenttel" class="form-control" type="text" data-required="true">
            </div>
          </div>
          @if ($isExperience){{--(isset($customTable->experience) && in_array($regType, $customTable->experience->uses))--}}
          <div class="step-pane" id="step2">
            @if (in_array($regType, $customTable->experience->startYear))
            <div class="form-group">
              <label>首次参加模拟联合国活动的年份 *</label>
              <input name="startYear" class="form-control" type="text" placeholder="您在哪一年第一次参会呢？请回答 4 位数年份" data-required="true">
            </div>
            @endif
            @if (in_array($regType, $customTable->experience->select))
            <div class="form-group">
              <label>请选择您希望展示的参会经历 (限选 3 项)</label>
              <table class="table table-striped b-t text-sm">
              <thead>
                <tr>
                  <th width="20"><i class="fa fa-check-circle-o"></i></th>
                  <th>会议</th>
                  <th>委员会</th>
                  <th>席位</th>
                  <th>奖项</th>
                  <th>参会日期</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><input name="post[]" type="checkbox" value="2"></td>
                  <td>BJMUNC2017</td>
                  <td>LOCARNO (en)</td>
                  <td>munpanel test</td>
                  <td>无</td>
                  <td>2017年2月</td>
                </tr>
              </tbody>
            </table>
            </div>
            @endif
            @if (in_array($regType, $customTable->experience->custom))
            <div class="form-group">
              <label>请添加未在 MUNPANEL 收录的参会经历 (最多 3 项)</label>
              <div class="form-group pull-in clearfix">
                <div class="col-sm-6">
                 <select name="level1" class="form-control">
                   <option value="" selected="">请选择会议级别</option>
                   <option value="1">全国及以上级别会议</option>
                   <option value="2">地区级会议</option>
                   <option value="3">校际会</option>
                   <option value="4">校内会</option>
                 </select>
                </div>
                <div class="col-sm-6">
                  <input name="date1" class="form-control" type="text" placeholder="会议的举办时间 (例：2017年2月)">
                </div>
              </div>
              <div class="form-group">
                <input name="name1" class="form-control" type="text" placeholder="会议名称">
              </div>
              <div class="form-group pull-in clearfix">
                <div class="col-sm-6">
                  <input name="role1" class="form-control" type="text" placeholder="您的角色">
                </div>
                <div class="col-sm-6">
                  <input name="award1" class="form-control" type="text" placeholder="所获奖项 (如果有)">
                </div>
              </div>
            <div class="form-group">
              <input name="others1" class="form-control" type="text" placeholder="备注">
            </div>
            </div>
            <div class="form-group">
              <label>&nbsp;</label>
              <div class="form-group pull-in clearfix">
                <div class="col-sm-6">
                 <select name="level2" class="form-control">
                   <option value="" selected="">请选择会议级别</option>
                   <option value="1">全国及以上级别会议</option>
                   <option value="2">地区级会议</option>
                   <option value="3">校际会</option>
                   <option value="4">校内会</option>
                 </select>
                </div>
                <div class="col-sm-6">
                  <input name="date2" class="form-control" type="text" placeholder="会议的举办时间 (例：2017年2月)"> 
                </div>
              </div>
              <div class="form-group">
                <input name="name2" class="form-control" type="text" placeholder="会议名称">
              </div>
              <div class="form-group pull-in clearfix">
                <div class="col-sm-6">
                  <input name="role2" class="form-control" type="text" placeholder="您的角色">
                </div>
                <div class="col-sm-6">
                  <input name="award2" class="form-control" type="text" placeholder="所获奖项 (如果有)">
                </div>
              </div>
            <div class="form-group">
              <input name="others2" class="form-control" type="text" placeholder="备注">
            </div>
            </div>
            <div class="form-group">
              <label>&nbsp;</label>
              <div class="form-group pull-in clearfix">
                <div class="col-sm-6">
                 <select name="level3" class="form-control">
                   <option value="" selected="">请选择会议级别</option>
                   <option value="1">全国及以上级别会议</option>
                   <option value="2">地区级会议</option>
                   <option value="3">校际会</option>
                   <option value="4">校内会</option>
                 </select>
                </div>
                <div class="col-sm-6">
                  <input name="date3" class="form-control" type="text" placeholder="会议的举办时间 (例：2017年2月)">
                </div>
              </div>
              <div class="form-group">
                <input name="name3" class="form-control" type="text" placeholder="会议名称">
              </div>
              <div class="form-group pull-in clearfix">
                <div class="col-sm-6">
                  <input name="role3" class="form-control" type="text" placeholder="您的角色">
                </div>
                <div class="col-sm-6">
                  <input name="award3" class="form-control" type="text" placeholder="所获奖项 (如果有)">
                </div>
              </div>
            <div class="form-group">
              <input name="others3" class="form-control" type="text" placeholder="备注">
            </div>
            </div>
            @endif
          </div>
          @endif
          <div class="step-pane" id="{{$isExperience ? 'step3' : 'step2'}}">
            {!!$confForm!!}{{--
                <!--?php
                switch ($item->type)
                {
                  // 自定义的表单项
                  case 'select': echo '
                  <select name="'.$item->name.'" class="form-control m-b">
                    <option value="" selected="">请选择</option>';
                    foreach ($item->options as $option)
                      echo '<option value="'.$option->value.'">'.$option->text.'</option>';
                  echo '</select> ';
                  break;
                  case 'checkbox': echo '<br><input name="'.$item->name.'" type="checkbox">'.$item->text;
                  break;
                  case 'text': echo '<input name="'.$item->name.'" class="form-control m-b" type="text" value="">';
                  break;
                  case 'selectCommittee': echo '
              <label>'.$item->title.'</label>
              <select name="committee1" class="form-control"'. isset($item->data_required) ? 'data-required="true"' : '' .'>
                <option value="" selected="">请选择</option>';
                  foreach ($committees as $committee)
                    if (is_null($committee->father_committee_id) && $committee->option_limit >= 1)
                      echo '<option value="'. $committee->id .'">'. $committee->name .'</option>';
                  echo '</select>';
                  break;
                  // 预设的表单项
                  case 'preGroupOptions': echo'
            <div class="form-group">
              <label>团队报名选项</label>
              <div>
                  <input name="groupOption" value="personal" type="radio" checked="checked">
                  我以个人身份报名<br>
                  <input name="groupOption" value="group" type="radio">
                  我跟随团队报名<br>
                  <input name="groupOption" value="leader" type="radio">
                  我是团队报名的领队<br>
              </div>
            </div>';
                  break;
                  case 'preRemarks': echo'
            <div class="form-group">
              <label>备注</label>
              <textarea name="others" class="form-control" placeholder="任何其他说明" type="text"></textarea>
            </div>';
                  break;
                }
                ?-->
          <!--/div>
          <div class="step-pane" id="{{$isExperience ? 'step4' : 'step3'}}">
            <label>确认您的报名信息</label>
            <p>千反田える，您将以<strong>{{ $regType == 'delegate' ? '代表' : ($regType == 'observer' ? '观察员' : '志愿者') }}</strong>身份报名参加2017年环梦模拟联合国年度会议。<br>请确认以下报名信息是否准确无误。</p>
            <section class="panel text-sm">
              <div class="panel-body">
                <label>个人信息</label>
                <p><i>姓名</i><br>&emsp;&emsp;千反田える</p>
                <p><i>邮箱</i><br>&emsp;&emsp;info@munpanel.com</p>
                <p><i>性别及出生日期</i><br>&emsp;&emsp;女 / 1996-07-26</p>
                <p><i>省份</i><br>&emsp;&emsp;海外</p>
                <p><i>学校及毕业年份</i><br>&emsp;&emsp;岐阜县立斐太高等学校 / 2015</p>
                <p><i>证件类型及号码</i><br>&emsp;&emsp;护照 (Passport) / MA1234567</p>
                <p><i>电话</i><br>&emsp;&emsp;0081577355678</p>
                <p><i>QQ</i><br>&emsp;&emsp;123456789</p>                      
                <p><i>Skype</i><br>&emsp;&emsp;chitanda-eru</p>
                <p><i>微信</i><br>&emsp;&emsp;chitanda-eru</p>
                <p><i>紧急联络人</i><br>&emsp;&emsp;千反田铁吾</p>
                <p><i>与紧急联络人关系</i><br>&emsp;&emsp;父亲</p>
                <p><i>紧急联络人电话</i><br>&emsp;&emsp;0081577357000</p>
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
          </section-->--}}
                <input name="correct" type="checkbox" data-required="true">
                <!--i class="fa fa-square-o"></i-->
                我确认以上报名信息准确无误<br>
                <!--input name="agreement" type="checkbox" data-required="true">
                <i class="fa fa-square-o"></i>
                我同意环梦模拟联合国参会协议和 MUNPANEL 使用协议 (虽然并没有这两样东西)<br--><br>
            <!--div class="checkbox">
              <label class="checkbox-custom">
              </label>
            </div>
            <div class="checkbox">
              <label class="checkbox-custom">
              </label>
            </div-->
            @if (!Auth::check())
            <div class="form-group">
              <label>MUNPANEL 密码</label>
              <input name="password2" class="form-control" type="password" placeholder="输入密码以创建您的 MUNPANEL 账号" data-required="true" autocomplete="new-password">
            </div>
            @endif
          </div>
        </form>
        {{--<!--div class="step-pane" id="{{$isExperience ? 'step5' : 'step4'}}">
          <p>您的报名已成功完成</p>
        </div-->--}}
        <div class="actions pull-left">
          <button class="btn btn-white btn-sm btn-prev" disabled="" type="button">Prev</button>
          <button class="btn btn-white btn-sm btn-next" type="button" data-last="Finish">Next</button>
        </div>
      </div>
    </div>
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<script>
$('#delform').submit(function(e){
    e.preventDefault();
    if ($( '#reg2Form' ).parsley( 'validate' )) {
        $.post("{{ mp_url('/saveReg2') }}", $('#reg2Form').serialize(), function(receivedData){
            //if (receivedData == "success")
            $('#ajaxModal').modal('hide');
            @if (Reg::current()->type != 'ot' && Reg::current()->type != 'school')
            location.reaload();
            @endif
            //useTheResponseData(receivedData);
        });
    }
});
$('#select-typeID').change(function(e){
    var e1 = document.getElementById("text-sfz");
    switch (document.getElementById("select-typeID").value)
    {
        case "1":
            e1.setAttribute("pattern", "^(\\d{6})(\\d{4})(\\d{2})(\\d{2})(\\d{3})([0-9]|X)$");
            e1.setAttribute("placeholder", "中国大陆 18 位身份证号 (末位 X 大写)");
            break;
        case "2":
            e1.setAttribute("pattern", "^[A-Z0-9]{1,9}$");
            e1.setAttribute("placeholder", "不多于 9 位的数字和大写字母组合");
            break;
        case "3":
            e1.setAttribute("pattern", "^(H|M)\\d{8}$");
            e1.setAttribute("placeholder", "H 或 M 后接 8 位纯数字 (不含换证次数)");
            break;
        case "4":
            e1.setAttribute("pattern", "^\\d{8}$");
            e1.setAttribute("placeholder", "8 位纯数字 (不含换证次数)");
            break;
        case "5":
            e1.setAttribute("pattern", "^\\d{3}-\\d{2}-\\d{4}$");
            e1.setAttribute("placeholder", "形如 AAA-GG-SSSS");
    }
    $("form").parsley('destroy');
    $("form").parsley();
});  
$(document).ready(function() {
    $('#reg2Wizard')
       // Call the wizard plugin
       .wizard()

        // Triggered when clicking the Complete button
        .on('finished.fu.wizard', function(e) {
        $('#reg2Form').submit();
    });
});
</script>
@if (isset($customTable->scripts))
<script>{!!$customTable->scripts!!}</script>
@endif
