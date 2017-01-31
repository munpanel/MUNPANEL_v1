<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-body">
            <h4>{{$delegate->user->name}}</h4>
            <p>{{$delegate->user->school->name}}
            <br>{{$delegate->committee->display_name}}
            @if ($delegate->committee->language == 'ChineseS')
                &nbsp;{{$delegate->nation->name}}代表
            @else
                , delegate of {{$delegate->nation->name}}
            @endif
            </p>
            <p>
            @if (isset($delegate->qq))
                <i class="fa fa-qq"></i>&nbsp;{{$delegate->qq}}
            @endif
            @if (isset($delegate->wechat))
                <i class="fa fa-wechat"></i>&nbsp;{{$delegate->wechat}}
            @endif
            <i class="fa fa-phone"></i>&nbsp;{{$delegate->tel}}
            <i class="fa fa-email"></i>&nbsp;{{$delegate->user->email}}
        </div>
    </div>
</div>