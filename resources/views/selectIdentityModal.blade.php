<div class="modal-over">
  <div class="modal-center animated flipInX" style="width:350px;margin:-30px 0 0 -175px;">
    <div class="pull-left thumb m-r"><img src="{{ 'https://www.gravatar.com/avatar/' . md5( strtolower( trim( Auth::user()->email ) ) ) . '?d='.mp_url('images/avatar.png').'&s=320' }}" class="img-thumbnail"></div>
    <div class="clear">
      <p class="text-white">{{Reg::current()->name()}} - 请选择您希望登录的身份或注销</p>
      <form action={{mp_url('/doSwitchIdentity')}} method="post">
       {{csrf_field()}}
       <div class="input-group input-m">
        <select name="reg" class="form-control m-b">
        @foreach(Auth::user()->regs->where('conference_id', Reg::currentConferenceID())->where('enabled', true) as $reg)
        <option value="{{$reg->id}}" {{ $reg->id == Reg::current()->id ? 'selected' : '' }}>{{$reg->regText()}}</option>
        @endforeach
        <option value="logout">注销用户</option>
        </select>
        <span class="input-group-btn">
          <button class="btn btn-success" type="submit"><i class="fa fa-arrow-right"></i></button>
          <!--button class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="fa fa-times"></i></button-->
        </span>
       </div>
      </form>
    </div>
  </div>
</div>
