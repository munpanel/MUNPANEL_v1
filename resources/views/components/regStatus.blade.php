<section class="panel bg-warning no-borders">
  <div class="row">
    <div class="col-xs-6">
      <div class="wrapper">
        <p>报名</p>
        <p class="h4 font-bold">{{ $status }}</p>
        <div class="progress progress-xs progress-striped active m-b-sm">
          <div class="progress-bar progress-bar-warning" data-toggle="tooltip" data-original-title="{{ $percent }}%" style="width: {{ $percent }}%"></div>
        </div>
          @if (!Auth::user()->verified())
          <div class="text-sm">点击下方按钮激活账号：</div>
          <a href="{{mp_url('/verifyEmail')}}" class="btn btn-danger">激活我的账号</a>
          @elseif (Reg::current()->type == 'unregistered')
          <div class="text-sm">点击下方按钮报名：</div>
          <a href="{{ mp_url('/reg2.modal/select') }}" data-toggle="ajaxModal" class="btn btn-danger">报名</a>
          @elseif (!Reg::current()->enabled())
          <div class="text-sm">点击下方按钮重置报名状态：</div>
          <a href="{{ mp_url('/resetReg/true') }}" class="btn btn-danger">重置我的报名</a>
          @elseif (is_null(Reg::current()->specific()))
          <div class="text-sm">点击下方按钮重置报名状态：</div>
          <a href="{{ mp_url('/resetReg') }}" class="btn btn-danger">重置我的报名</a>
          @elseif ($hasRegAssignment)
          <div class="text-sm">点击下方按钮查看学术测试题：</div>
          <a href="{{ mp_url('/assignments') }}" class="btn btn-danger">查看学术作业</a>
          @else
          <div class="text-sm">点击下方按钮查看我的报名：</div>
          <a href="{{ mp_url('/ot/regInfo.modal/'.Reg::currentID()) }}" data-toggle="ajaxModal" class="btn btn-danger">查看我的报名</a>
          @endif
      </div>
    </div>
    <div class="col-xs-6 wrapper text-center">
      <div class="inline m-t-sm">
        <div class="easypiechart" data-percent="{{ $percent }}" data-line-width="8" data-bar-color="#ffffff" data-track-Color="#c79d43" data-scale-Color="false" data-size="100">
          <span class="h2">{{ $percent }}</span>%
        </div>
      </div>
    </div>
  </div>
</section>