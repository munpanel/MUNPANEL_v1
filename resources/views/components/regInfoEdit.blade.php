{{-- JSONIZE!!!! TODO!!!! --}}
@php
$type = $reg->type;
$customTable = json_decode(Reg::currentConference()->option('reg_tables'))->regTable; 
@endphp
<section class="text-sm">
  <div>
    <table id="reg-{{$reg->id??''}}" class="table table-bordered table-striped" style="clear: both">
        <thead>
            <tr>
                <td colspan="2"><strong>个人信息</strong></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="35%">姓名</td>
                <td width="65%">{{$reg->user->name??''}}</td>
            </tr>
            <tr>
                <td width="35%">email</td>
                <td width="65%">{{$reg->user->email??''}}</td>
            </tr>
            <tr>
                <td width="35%">电话</td>
                <td width="65%">{{$reg->user->tel??''}}</td>
            </tr>
            <tr>
                <td width="35%">性别</td>
                <td width="65%"><a href="#" id="reg.gender" data-type="select" data-pk="{{$reg->id??''}}" data-url="{{mp_url('/ot/update/reg/'.$reg->id)??''}}" data-title="reg.gender" data-value='{{$reg->gender??''}}' data-source="[{'value':'male', 'text':'男'},{'value':'female', 'text':'女'}]" class="editable"></a></td>
            </tr>
@if (isset($customTable->info->dateofbirth))
            <tr>
                <td width="35%">出生日期</td>
                <td width="65%"><a href="#" id="personinfo.dateofbirth" data-type="text" data-pk="{{$reg->id??''}}" data-url="{{mp_url('/ot/update/reg/'.$reg->id)??''}}" data-title="personinfo.dateofbirth" class="editable">{{$regInfo->personinfo->dateofbirth??''}}</a></td>
            </tr>
@endif
@if (isset($customTable->info->province))
            <tr>
                <td width="35%">省份</td>
                <td width="65%"><a href="#" id="personinfo.province" data-type="select" data-pk="{{$reg->id??''}}" data-url="{{mp_url('/ot/update/reg/'.$reg->id)??''}}" data-title="personinfo.province" data-value='{{$regInfo->personinfo->province??''}}' data-source='[{"value":11,"text":"\u5317\u4eac"},{"value":12,"text":"\u5929\u6d25"},{"value":13,"text":"\u6cb3\u5317"},{"value":14,"text":"\u5c71\u897f"},{"value":15,"text":"\u5185\u8499\u53e4"},{"value":21,"text":"\u8fbd\u5b81"},{"value":22,"text":"\u5409\u6797"},{"value":23,"text":"\u9ed1\u9f99\u6c5f"},{"value":31,"text":"\u4e0a\u6d77"},{"value":32,"text":"\u6c5f\u82cf"},{"value":33,"text":"\u6d59\u6c5f"},{"value":34,"text":"\u5b89\u5fbd"},{"value":35,"text":"\u798f\u5efa"},{"value":36,"text":"\u6c5f\u897f"},{"value":37,"text":"\u5c71\u4e1c"},{"value":41,"text":"\u6cb3\u5357"},{"value":42,"text":"\u6e56\u5317"},{"value":43,"text":"\u6e56\u5357"},{"value":44,"text":"\u5e7f\u4e1c"},{"value":45,"text":"\u5e7f\u897f"},{"value":46,"text":"\u6d77\u5357"},{"value":50,"text":"\u91cd\u5e86"},{"value":51,"text":"\u56db\u5ddd"},{"value":52,"text":"\u8d35\u5dde"},{"value":53,"text":"\u4e91\u5357"},{"value":54,"text":"\u897f\u85cf"},{"value":61,"text":"\u9655\u897f"},{"value":62,"text":"\u7518\u8083"},{"value":63,"text":"\u9752\u6d77"},{"value":64,"text":"\u5b81\u590f"},{"value":65,"text":"\u65b0\u7586"},{"value":71,"text":"\u53f0\u6e7e"},{"value":81,"text":"\u9999\u6e2f"},{"value":82,"text":"\u6fb3\u95e8"},{"value":99,"text":"\u6d77\u5916"}]' class="editable"></a></td>
            </tr>
@endif
            <tr>
                <td width="35%">学校</td>
                <td width="65%"><a href="#" id="personinfo.school" data-type="text" data-pk="{{$reg->id??''}}" data-url="{{mp_url('/ot/update/reg/'.$reg->id)??''}}" data-title="personinfo.school" class="editable">{{$regInfo->personinfo->school??''}}</a></td>
            </tr>
            <tr>
                <td width="35%">毕业年份</td>
                <td width="65%"><a href="#" id="personinfo.yearGraduate" data-type="number" data-pk="{{$reg->id??''}}" data-url="{{mp_url('/ot/update/reg/'.$reg->id)??''}}" data-title="personinfo.yearGraduate" class="editable">{{$regInfo->personinfo->yearGraduate??''}}</a></td>
            </tr>
            <tr>
            <td width="35%">证件类型</td>
            <td width="65%"><a href="#" id="personinfo.typeDocument" data-type="select" data-pk="{{$reg->id??''}}" data-url="{{mp_url('/ot/update/reg/'.$reg->id)??''}}" data-title="personinfo.typeDocument" data-value='{{$regInfo->personinfo->typeDocument??''}}' data-source="[{'value':1, 'text':'居民身份证'},{'value':2, 'text':'护照 (Passport)'},{'value':3, 'text':'港澳回乡证'},{'value':4, 'text':'台胞证'},{'value':5, 'text':'US Social Security Card'}]" class="editable"></a></td>
            </tr>
            <tr>
            <td width="35%">证件号</td>
            <td width="65%"><a href="#" id="personinfo.sfz" data-type="text" data-pk="{{$reg->id??''}}" data-url="{{mp_url('/ot/update/reg/'.$reg->id)??''}}" data-title="personinfo.sfz" class="editable">{{$regInfo->personinfo->sfz??''}}</a></td>
            </tr>
@if (isset($customTable->info->contact->alt_phone))
            <tr>
                <td width="35%">备用电话</td>
                <td width="65%"><a href="#" id="personinfo.alt_phone" data-type="text" data-pk="{{$reg->id??''}}" data-url="{{mp_url('/ot/update/reg/'.$reg->id)??''}}" data-title="personinfo.alt_phone" class="editable">{{$regInfo->personinfo->alt_phone??''}}</a></td>
            </tr>
@endif
@if (isset($customTable->info->contact->qq))
            <tr>
                <td width="35%">QQ</td>
                <td width="65%"><a href="#" id="personinfo.qq" data-type="text" data-pk="{{$reg->id??''}}" data-url="{{mp_url('/ot/update/reg/'.$reg->id)??''}}" data-title="personinfo.qq" class="editable">{{$regInfo->personinfo->qq??''}}</a></td>
            </tr>
@endif
@if (isset($customTable->info->contact->skype))
            <tr>
                <td width="35%">Skype</td>
                <td width="65%"><a href="#" id="personinfo.skype" data-type="text" data-pk="{{$reg->id??''}}" data-url="{{mp_url('/ot/update/reg/'.$reg->id)??''}}" data-title="personinfo.skype" class="editable">{{$regInfo->personinfo->skype??''}}</a></td>
            </tr>
            @endif
            @if (isset($customTable->info->contact->wechat))
            <tr>
                <td width="35%">微信</td>
                <td width="65%"><a href="#" id="personinfo.wechat" data-type="text" data-pk="{{$reg->id??''}}" data-url="{{mp_url('/ot/update/reg/'.$reg->id)??''}}" data-title="personinfo.wechat" class="editable">{{$regInfo->personinfo->wechat??''}}</a></td>
            </tr>
            @endif
            @if (isset($customTable->info->emergency))
            <tr>
                <td width="35%">紧急联系人姓名</td>
                <td width="65%"><a href="#" id="personinfo.parentname" data-type="text" data-pk="{{$reg->id??''}}" data-url="{{mp_url('/ot/update/reg/'.$reg->id)??''}}" data-title="personinfo.parentname" class="editable">{{$regInfo->personinfo->parentname??''}}</a></td>
            </tr>
            <tr>
                <td width="35%">紧急联系人关系</td>
                <td width="65%"><a href="#" id="personinfo.parentrelation" data-type="text" data-pk="{{$reg->id??''}}" data-url="{{mp_url('/ot/update/reg/'.$reg->id)??''}}" data-title="personinfo.parentrelation" class="editable">{{$regInfo->personinfo->parentrelation??''}}</a></td>
            </tr>
            <tr>
                <td width="35%">紧急联系人电话</td>
                <td width="65%"><a href="#" id="personinfo.parenttel" data-type="text" data-pk="{{$reg->id??''}}" data-url="{{mp_url('/ot/update/reg/'.$reg->id)??''}}" data-title="personinfo.parenttel" class="editable">{{$regInfo->personinfo->parenttel??''}}</a></td>
            </tr>
@endif
            @if (isset($regInfo->experience))
        </tbody>
        <thead>
            <tr>
                <td colspan="2"><strong>参会经历</strong></td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="35%">首次参加模拟联合国活动的年份</td>
                <td width="65%"><a href="#" id="experience.startYear" data-type="text" data-pk="{{$reg->id??''}}" data-url="{{mp_url('/ot/update/reg/'.$reg->id)??''}}" data-title="experience.startYear" class="editable">{{$regInfo->experience->startYear??''}}</a></td>
            </tr>
            @if (isset($regInfo->experience->item))
            <tr>
            <td width="35%">参会经历</td>
            <td width="65%">
            @foreach ($regInfo->experience->item as $item)
            <p><i>参会经历 {{++$i??''}}</i><br>&emsp;&emsp;{{$item->name??''}} ({{levelOfConfs($item->level)??''}}, {{$item->dates??''}})<br>&emsp;&emsp;{{$item->role??''}}, {{$item->award or '无奖项'??''}}</p>
            @endforeach
            </td>
            </tr>
            @endif
            @if (Reg::currentConferenceID() != 2)
            <!--committee-->
            @endif
            @endif
        </tbody>
        <thead>
            <tr>
                <td colspan="2"><strong>会议信息</strong></td>
            </tr>
        </thead>
        <tbody>
@if ($type == 'delegate')
            <tr>
                <td width="35%">委员会</td>
                <td width="65%">{{$reg->committee->display_name ?? ''}}</td>
            </tr>
@endif
            <tr>
                <td width="35%">搭档姓名</td>
                <td width="65%"><a href="#" id="conference.partnername" data-type="text" data-pk="{{$reg->id??''}}" data-url="{{mp_url('/ot/update/reg/'.$reg->id)??''}}" data-title="conference.partnername" class="editable">{{$regInfo->conference->partnername??''}}</a>{!!(isset($reg->delegate) && isset($reg->delegate->partner_reg_id)) ? '&nbsp;<i class="fa fa-check-circle"></i>' : ''!!}</td>
            </tr>
            <tr>
                <td width="35%">是否住宿</td>
                <td width="65%"><a href="#" id="reg.accomodate" data-type="select" data-pk="{{$reg->id??''}}" data-url="{{mp_url('/ot/update/reg/'.$reg->id)??''}}" data-title="reg.accomodate" data-value='{{$reg->accomodate??''}}' data-source="[{'value':'1', 'text':'是'},{'value':'0', 'text':'否'}]" class="editable">{{$reg->accomodate ? '是':'否'}}</a></td>
            </tr>
            <tr>
                <td width="35%">室友姓名</td>
                <td width="65%"><a href="#" id="conference.roommatename" data-type="text" data-pk="{{$reg->id??''}}" data-url="{{mp_url('/ot/update/reg/'.$reg->id)??''}}" data-title="conference.roommatename" class="editable">{{$regInfo->conference->roommatename??''}}</a>{!!isset($reg->roommate_user_id) ? '&nbsp;<i class="fa fa-check-circle"></i>' : ''!!}</td>
            </tr>
            <tr>
                <td width="35%">备注</td>
                <td width="65%">{{$regInfo->conference->remarks??''}}</td>
            </tr>
          </tbody>
    </table>
    
    @if (isset($regInfo->experience) && Reg::currentConferenceID() == 2)
    <label>会议信息</label>
    @if (isset($regInfo->conference->committee1))
    <p><i>委员会意向 1</i><br>&emsp;&emsp;{{App\Committee::find($regInfo->conference->committee1)->name??''}}
    @if (isset($regInfo->conference->branch1) || isset($regInfo->conference->branch2))
    <br>&emsp;&emsp;会场意向: {{isset($regInfo->conference->branch1) ? App\Committee::find($regInfo->conference->branch1)->name : ''??''}}{{isset($regInfo->conference->branch2) ? (', '.App\Committee::find($regInfo->conference->branch2)->name) : ''??''}}
    @endif
    </p>
    @endif
    @if (isset($regInfo->conference->committee2))
    <p><i>委员会意向 2</i><br>&emsp;&emsp;{{App\Committee::find($regInfo->conference->committee2)->name??''}}
    @if (isset($regInfo->conference->branch3) || isset($regInfo->conference->branch4))
    <br>&emsp;&emsp;会场意向: {{isset($regInfo->conference->branch3) ? App\Committee::find($regInfo->conference->branch3)->name : ''??''}}{{isset($regInfo->conference->branch4) ? (', '.App\Committee::find($regInfo->conference->branch4)->name) : ''??''}}
    @endif
    </p>
    @endif
    @if (isset($regInfo->conference->typeInterview))
    <p><i>面试联络方式</i><br>&emsp;&emsp;{{typeInterview($regInfo->conference->typeInterview)??''}}</p>
    @endif
    @endif
  </div>
</section>
<link rel="stylesheet" href="{{cdn_url('/css/bootstrap-editable.css')}}" type="text/css" />
<script>
$.getScript( "{{cdn_url('/js/editable/bootstrap-editable.js')}}", function( data, textStatus, jqxhr  ) {
$.fn.editable.defaults.mode = 'inline';
$('#reg-{{$reg->id}} .editable').editable();
});
</script>
