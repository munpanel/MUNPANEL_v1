<section class="panel text-sm">
  <div class="panel-body">
    <label>个人信息</label>
    <p><i>姓名</i><br>&emsp;&emsp;{{$reg->user->name}}</p>
    <p><i>邮箱</i><br>&emsp;&emsp;{{$reg->user->email}}</p>
    <!-- dateofbirth -->
    <p><i>性别{{!empty($regInfo->personinfo->dateofbirth) ? '及出生日期' : ''}}</i><br>&emsp;&emsp;{{$reg->gender == 'male' ? '男' : '女'}}{{!empty($regInfo->personinfo->dateofbirth) ? ' / ' . $regInfo->personinfo->dateofbirth : ''}}</p>
    <!-- province -->
    @if (!empty($regInfo->personinfo->province))
    <p><i>省份</i><br>&emsp;&emsp;{{province($regInfo->personinfo->province)}}</p>
    @endif
    <p><i>学校及毕业年份</i><br>&emsp;&emsp;{{$regInfo->personinfo->school}} / {{$regInfo->personinfo->yearGraduate}}</p>
    <!-- sfz -->
    @if (!empty($regInfo->personinfo->sfz))
    <p><i>证件类型及号码</i><br>&emsp;&emsp;{{typeID($regInfo->personinfo->typeDocument)}} / {{$regInfo->personinfo->sfz}} </p>
    @endif
    <p><i>电话</i><br>&emsp;&emsp;{{$regInfo->personinfo->tel}}</p>
    <!-- alt_phone -->
    @if (!empty($regInfo->personinfo->alt_phone))                
    <p><i>备用电话</i><br>&emsp;&emsp;{{$regInfo->personinfo->alt_phone}}</p>
    @endif
    <!-- qq -->
    @if (!empty($regInfo->personinfo->qq)) 
    <p><i>QQ</i><br>&emsp;&emsp;{{$regInfo->personinfo->qq}}</p>
    @endif
    <!-- skype -->
    @if (!empty($regInfo->personinfo->skype)) 
    <p><i>Skype</i><br>&emsp;&emsp;{{$regInfo->personinfo->skype}}</p>
    @endif
    <!-- wechat -->
    @if (!empty($regInfo->personinfo->wechat)) 
    <p><i>微信</i><br>&emsp;&emsp;{{$regInfo->personinfo->wechat}}</p>
    @endif    
    <!-- emergency -->
    @if (!empty($regInfo->personinfo->parentname))
    <p><i>紧急联络人</i><br>&emsp;&emsp;{{$regInfo->personinfo->parentname}} ({{$regInfo->personinfo->parentrelation}}, {{$regInfo->personinfo->parenttel}})</p>
    @endif
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
    <p><i>面试联络方式</i><br>&emsp;&emsp;{{--typeInterview($regInfo->conference->typeInterview)</p>
    @if (isset($regInfo->conference->smsInterview)
	  <p><i>面试选项</i><br>&emsp;&emsp;{{isset($regInfo->conference->smsInterview) ? '开通面试短信提醒服务' : ''}}</p>
    @endif--}}
    @if (isset($regInfo->conference->groupOption))
    <p><i>团队报名选项</i><br>&emsp;&emsp;{{groupOption($regInfo->conference->groupOption)}}</p>
    @endif
    @if (isset($regInfo->conference->remarks))
    <p><i>备注</i><br>&emsp;&emsp;{{$regInfo->conference->remarks}}</p>
    @endif
  </div>
</section>
