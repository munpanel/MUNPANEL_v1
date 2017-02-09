            <p>{{$delegate->school->name}}
            <br>{{$delegate->committee->display_name}}
            @if ($delegate->committee->language == 'ChineseS')
                &nbsp;{{$delegate->nation->name}}代表
            @else
                , delegate of {{$delegate->nation->name}}
            @endif
            </p>
            <!--<h4>&nbsp;</h4>!-->
            <p>
            @if (isset($delegate->qq))
                <i class='fa fa-qq'></i>&nbsp;{{$delegate->qq}}<br>
            @endif
            @if (isset($delegate->wechat))
                <i class='fa fa-wechat'></i>&nbsp;{{$delegate->wechat}}<br>
            @endif
            {{-- <i class='fa fa-phone'></i>&nbsp;{{$delegate->tel}}<br> 不透露代表电话 To-Do 组织团队显示电话--}}
            <i class='fa fa-envelope'></i>&nbsp;{{$delegate->user->email}}
            </p>
