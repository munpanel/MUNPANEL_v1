<div class="modal-dialog">
      <div class="modal-content">
<header class="header bg-dark bg-gradient">
          <ul class="nav nav-tabs">
            <li class="{{ $user->type == 'delegate' || $user->type == 'unregistered' ? 'active' : '' }}"><a href="#delegate" data-toggle="tab" aria-expanded="true">代表</a></li>
            <li class="{{ $user->type == 'volunteer' ? 'active' : '' }}"><a href="#volunteer" data-toggle="tab" aria-expanded="false">志愿者</a></li>
          </ul>
        </header>
      <div class="tab-content">
        <section class="tab-pane {{ $user->type == 'delegate' || $user->type == 'unregistered' ? 'active' : '' }}" id="delegate">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
              <div class="alert alert-warning"><b>代表、志愿者，任选一项。保存任何一项将自动清空另一项信息。保存将自动重置报名状态为等待学校审核。</b></div>
              <form role="form" id="delform"><!--action="{{ secure_url('/saveRegDel') }}" method="post"-->
                {{ csrf_field() }}
                @if (!is_null($id))
                  <input type="hidden" name="id" value="{{ $id }}">
                @endif
                <div class="form-group">
                  <label>姓名</label>
                  <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                  <span class="help-block m-b-none">如要编辑请重新注册或联系微信adamyi</span>
                </div>
                <div class="form-group">
                  <label>电子邮箱</label>
                  <input type="text" class="form-control" value="{{ $user->email }}" disabled>
                  <span class="help-block m-b-none">如要编辑请重新注册或联系微信adamyi</span>
                </div>
                <div class="form-group">
                  <label>委员会</label>
                  <select name="committee" class="form-control m-b">
                    @foreach ($committees as $committee)
                      @if (isset($delegate))
                        @if ($committee->id == $delegate->committee_id)
                          <option value="{{ $committee->id }}" selected>{{ $committee->name }}</option>
                          @continue
                        @endif
                      @endif
                      <option value="{{ $committee->id }}">{{ $committee->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>学校</label>
                  <select name="school" class="form-control m-b">
                    @foreach ($schools as $school)
                      @if (isset($delegate))
                        @if ($school->id == $delegate->school_id)
                          <option value="{{ $school->id }}" selected>{{ $school->name }}</option>
                          @continue
                        @endif
                      @endif
                      <option value="{{ $school->id }}">{{ $school->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>年级</label>
                  <select name="grade" class="form-control m-b">
                    <option value="1" {{ isset($delegate) && $delegate->grade == 1 ? 'selected' : '' }}>小学及以下</option>
                    <option value="2" {{ isset($delegate) && $delegate->grade == 2 ? 'selected' : '' }}>初一</option>
                    <option value="3" {{ isset($delegate) && $delegate->grade == 3 ? 'selected' : '' }}>初二</option>
                    <option value="4" {{ isset($delegate) && $delegate->grade == 4 ? 'selected' : '' }}>初三</option>
                    <option value="5" {{ isset($delegate) && $delegate->grade == 5 ? 'selected' : '' }}>高一</option>
                    <option value="6" {{ isset($delegate) && $delegate->grade == 6 ? 'selected' : '' }}>高二</option>
                    <option value="7" {{ isset($delegate) && $delegate->grade == 7 ? 'selected' : '' }}>高三</option>
                    <option value="8" {{ isset($delegate) && $delegate->grade == 8 ? 'selected' : '' }}>本科及以上</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>身份证号</label>
                  <input type="text" name="sfz" class="form-control" value="{{ isset($delegate) ? $delegate->sfz : '' }}">
                </div>
                <div class="form-group">
                  <label>QQ</label>
                  <input type="text" name="qq" class="form-control" value="{{ isset($delegate) ? $delegate->qq : '' }}">
                </div>
                <div class="form-group">
                  <label>微信</label>
                  <input type="text" name="wechat" class="form-control" value="{{ isset($delegate) ? $delegate->wechat : '' }}">
                </div>
                <div class="form-group">
                  <label>搭档姓名（无则空）</label>
                  <input type="text" name="partnername" class="form-control" value="{{ isset($delegate) ? $delegate->partnername : '' }}">
                </div>
                <div class="form-group">
                  <label>室友姓名（无则空）</label>
                  <input type="text" name="roommatename" class="form-control" value="{{ isset($delegate) ? $delegate->roommatename : '' }}">
                </div>
                <div class="form-group">
                  <label>电话</label>
                  <input type="text" name="tel" class="form-control" value="{{ isset($delegate) ? $delegate->tel : '' }}">
                </div>
                <div class="form-group">
                  <label>家长电话</label>
                  <input type="text" name="parenttel" class="form-control" value="{{ isset($delegate) ? $delegate->parenttel : '' }}">
                </div>
                <div class="form-group">
                  <label>性别</label>
                  <div class="btn-group" data-toggle="buttons">
                    @if (isset($delegate) && $delegate->gender == 'male')
                      <label class="btn btn-sm btn-info active">
                        <input type="radio" name="gender" id="male" value="male" checked> <i class="fa fa-check text-active"></i>男
                       </label>
                    @else
                      <label class="btn btn-sm btn-info">
                        <input type="radio" name="gender" id="male" value="male"> <i class="fa fa-check text-active"></i>男
                      </label>
                    @endif
                    @if (isset($delegate) && $delegate->gender == 'female')
                      <label class="btn btn-sm btn-success active">
                        <input type="radio" name="gender" id="female" value="female" checked> <i class="fa fa-check text-active"></i>女
                      </label>
                    @else
                      <label class="btn btn-sm btn-success">
                        <input type="radio" name="gender" id="female" value="female"> <i class="fa fa-check text-active"></i>女
                      </label>
                    @endif
                  </div>
                  <label>是否住宿</label>
                  <div class="btn-group" data-toggle="buttons">
                    @if (isset($delegate) && $delegate->accomodate == 1)
                      <label class="btn btn-sm btn-info active">
                        <input type="radio" name="accomodate" id="accomodateChoice" value="1" checked> <i class="fa fa-check text-active"></i>是
                       </label>
                    @else
                      <label class="btn btn-sm btn-info">
                        <input type="radio" name="accomodate" id="accomodateChoice" value="1"> <i class="fa fa-check text-active"></i>是
                      </label>
                    @endif
                    @if (isset($delegate) && $delegate->accomodate == 0)
                      <label class="btn btn-sm btn-success active">
                        <input type="radio" name="accomodate" id="noAccomodateChoice" value="0" checked> <i class="fa fa-check text-active"></i>否
                      </label>
                    @else
                      <label class="btn btn-sm btn-success">
                        <input type="radio" name="accomodate" id="noAccomodateChoice" value="0"> <i class="fa fa-check text-active"></i>否
                      </label>
                    @endif
                  </div>
                </div>
                <div class="checkbox m-t-lg">
                  <button type="submit" class="btn btn-sm btn-success pull-right text-uc m-t-n-xs"><strong>保存</strong></button>
                </div>                
              </form>
              </div>
            </div>
          </div>          
        </section>
        <section class="tab-pane {{ $user->type == 'volunteer' ? 'active' : '' }}" id="volunteer">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
              <div class="alert alert-warning"><b>代表、志愿者，任选一项。保存任何一项将自动清空另一项信息。保存将自动重置报名状态为等待学校审核。</b></div>
              <form role="form" id="volform"><!--action="{{ secure_url('/saveRegDel') }}" method="post"-->
                {{ csrf_field() }}
                @if (!is_null($id))
                  <input type="hidden" name="id" value="{{ $id }}">
                @endif
                <div class="form-group">
                  <label>姓名</label>
                  <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                  <span class="help-block m-b-none">如要编辑请重新注册或联系微信adamyi</span>
                </div>
                <div class="form-group">
                  <label>电子邮箱</label>
                  <input type="text" class="form-control" value="{{ $user->email }}" disabled>
                  <span class="help-block m-b-none">如要编辑请重新注册或联系微信adamyi</span>
                </div>
                <div class="form-group">
                  <label>学校</label>
                  <select name="school" class="form-control m-b">
                    @foreach ($schools as $school)
                      @if (isset($volunteer))
                        @if ($school->id == $volunteer->school_id)
                          <option value="{{ $school->id }}" selected>{{ $school->name }}</option>
                          @continue
                        @endif
                      @endif
                      <option value="{{ $school->id }}">{{ $school->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>年级</label>
                  <select name="grade" class="form-control m-b">
                    <option value="1" {{ isset($volunteer) && $volunteer->grade == 1 ? 'selected' : '' }}>小学及以下</option>
                    <option value="2" {{ isset($volunteer) && $volunteer->grade == 2 ? 'selected' : '' }}>初一</option>
                    <option value="3" {{ isset($volunteer) && $volunteer->grade == 3 ? 'selected' : '' }}>初二</option>
                    <option value="4" {{ isset($volunteer) && $volunteer->grade == 4 ? 'selected' : '' }}>初三</option>
                    <option value="5" {{ isset($volunteer) && $volunteer->grade == 5 ? 'selected' : '' }}>高一</option>
                    <option value="6" {{ isset($volunteer) && $volunteer->grade == 6 ? 'selected' : '' }}>高二</option>
                    <option value="7" {{ isset($volunteer) && $volunteer->grade == 7 ? 'selected' : '' }}>高三</option>
                    <option value="8" {{ isset($volunteer) && $volunteer->grade == 8 ? 'selected' : '' }}>本科及以上</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>身份证号</label>
                  <input type="text" name="sfz" class="form-control" value="{{ isset($volunteer) ? $volunteer->sfz : '' }}">
                </div>
                <div class="form-group">
                  <label>QQ</label>
                  <input type="text" name="qq" class="form-control" value="{{ isset($volunteer) ? $volunteer->qq : '' }}">
                </div>
                <div class="form-group">
                  <label>微信</label>
                  <input type="text" name="wechat" class="form-control" value="{{ isset($volunteer) ? $volunteer->wechat : '' }}">
                </div>
                <div class="form-group">
                  <label>室友姓名（无则空）</label>
                  <input type="text" name="roommatename" class="form-control" value="{{ isset($volunteer) ? $volunteer->roommatename : '' }}">
                </div>
                <div class="form-group">
                  <label>电话</label>
                  <input type="text" name="tel" class="form-control" value="{{ isset($volunteer) ? $volunteer->tel : '' }}">
                </div>
                <div class="form-group">
                  <label>家长电话</label>
                  <input type="text" name="parenttel" class="form-control" value="{{ isset($volunteer) ? $volunteer->parenttel : '' }}">
                </div>
                <div class="form-group">
                  <label>性别</label>
                  <div class="btn-group" data-toggle="buttons">
                    @if (isset($volunteer) && $volunteer->gender == 'male')
                      <label class="btn btn-sm btn-info active">
                        <input type="radio" name="gender" id="male" value="male" checked> <i class="fa fa-check text-active"></i>男
                       </label>
                    @else
                      <label class="btn btn-sm btn-info">
                        <input type="radio" name="gender" id="male" value="male"> <i class="fa fa-check text-active"></i>男
                      </label>
                    @endif
                    @if (isset($volunteer) && $volunteer->gender == 'female')
                      <label class="btn btn-sm btn-success active">
                        <input type="radio" name="gender" id="female" value="female" checked> <i class="fa fa-check text-active"></i>女
                      </label>
                    @else
                      <label class="btn btn-sm btn-success">
                        <input type="radio" name="gender" id="female" value="female"> <i class="fa fa-check text-active"></i>女
                      </label>
                    @endif
                  </div>
                  <label>是否住宿</label>
                  <div class="btn-group" data-toggle="buttons">
                    @if (isset($volunteer) && $volunteer->accomodate == 1)
                      <label class="btn btn-sm btn-info active">
                        <input type="radio" name="accomodate" id="accomodateChoice" value="1" checked> <i class="fa fa-check text-active"></i>是
                       </label>
                    @else
                      <label class="btn btn-sm btn-info">
                        <input type="radio" name="accomodate" id="accomodateChoice" value="1"> <i class="fa fa-check text-active"></i>是
                      </label>
                    @endif
                    @if (isset($volunteer) && $volunteer->accomodate == 0)
                      <label class="btn btn-sm btn-success active">
                        <input type="radio" name="accomodate" id="noAccomodateChoice" value="0" checked> <i class="fa fa-check text-active"></i>否
                      </label>
                    @else
                      <label class="btn btn-sm btn-success">
                        <input type="radio" name="accomodate" id="noAccomodateChoice" value="0"> <i class="fa fa-check text-active"></i>否
                      </label>
                    @endif
                  </div>
                </div>
                <div class="checkbox m-t-lg">
                  <button type="submit" class="btn btn-sm btn-success pull-right text-uc m-t-n-xs"><strong>保存</strong></button>
                </div>
              </form>
              </div>
            </div>
          </div>
        </section>
        <section class="tab-pane {{ $user->type == 'observer' ? 'active' : '' }}" id="observer">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
              <div class="alert alert-warning"><b>代表、志愿者、观察员，三者任选一项。保存任何一项将自动清空其余两项信息。</b></div>
              <form role="form" id="obsform"><!--action="{{ secure_url('/saveRegDel') }}" method="post"-->
                {{ csrf_field() }}
                @if (!is_null($id))
                  <input type="hidden" name="id" value="{{ $id }}">
                @endif
                <div class="form-group">
                  <label>姓名</label>
                  <input type="text" class="form-control" value="{{ $user->name }}" disabled>
                  <span class="help-block m-b-none">如要编辑请重新注册或联系微信adamyi</span>
                </div>
                <div class="form-group">
                  <label>电子邮箱</label>
                  <input type="text" class="form-control" value="{{ $user->email }}" disabled>
                  <span class="help-block m-b-none">如要编辑请重新注册或联系微信adamyi</span>
                </div>
                <div class="form-group">
                  <label>学校</label>
                  <select name="school" class="form-control m-b">
                    @foreach ($schools as $school)
                      @if (isset($observer))
                        @if ($school->id == $observer->school_id)
                          <option value="{{ $school->id }}" selected>{{ $school->name }}</option>
                          @continue
                        @endif
                      @endif
                      <option value="{{ $school->id }}">{{ $school->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>年级</label>
                  <select name="grade" class="form-control m-b">
                    <option value="1" {{ isset($observer) && $observer->grade == 1 ? 'selected' : '' }}>小学及以下</option>
                    <option value="2" {{ isset($observer) && $observer->grade == 2 ? 'selected' : '' }}>初一</option>
                    <option value="3" {{ isset($observer) && $observer->grade == 3 ? 'selected' : '' }}>初二</option>
                    <option value="4" {{ isset($observer) && $observer->grade == 4 ? 'selected' : '' }}>初三</option>
                    <option value="5" {{ isset($observer) && $observer->grade == 5 ? 'selected' : '' }}>高一</option>
                    <option value="6" {{ isset($observer) && $observer->grade == 6 ? 'selected' : '' }}>高二</option>
                    <option value="7" {{ isset($observer) && $observer->grade == 7 ? 'selected' : '' }}>高三</option>
                    <option value="8" {{ isset($observer) && $observer->grade == 8 ? 'selected' : '' }}>本科及以上</option>
                  </select>
                </div>
                <div class="form-group">
                  <label>身份证号</label>
                  <input type="text" name="sfz" class="form-control" value="{{ isset($observer) ? $observer->sfz : '' }}">
                </div>
                <div class="form-group">
                  <label>QQ</label>
                  <input type="text" name="qq" class="form-control" value="{{ isset($observer) ? $observer->qq : '' }}">
                </div>
                <div class="form-group">
                  <label>微信</label>
                  <input type="text" name="wechat" class="form-control" value="{{ isset($observer) ? $observer->wechat : '' }}">
                </div>
                <div class="form-group">
                  <label>室友姓名（无则空）</label>
                  <input type="text" name="roommatename" class="form-control" value="{{ isset($observer) ? $observer->roommatename : '' }}">
                </div>
                <div class="form-group">
                  <label>电话</label>
                  <input type="text" name="tel" class="form-control" value="{{ isset($observer) ? $observer->tel : '' }}">
                </div>
                <div class="form-group">
                  <label>家长电话</label>
                  <input type="text" name="parenttel" class="form-control" value="{{ isset($observer) ? $observer->parenttel : '' }}">
                </div>
                <div class="form-group">
                  <label>性别</label>
                  <div class="btn-group" data-toggle="buttons">
                    @if (isset($observer) && $observer->gender == 'male')
                      <label class="btn btn-sm btn-info active">
                        <input type="radio" name="gender" id="male" value="male" checked> <i class="fa fa-check text-active"></i>男
                       </label>
                    @else
                      <label class="btn btn-sm btn-info">
                        <input type="radio" name="gender" id="male" value="male"> <i class="fa fa-check text-active"></i>男
                      </label>
                    @endif
                    @if (isset($observer) && $observer->gender == 'female')
                      <label class="btn btn-sm btn-success active">
                        <input type="radio" name="gender" id="female" value="female" checked> <i class="fa fa-check text-active"></i>女
                      </label>
                    @else
                      <label class="btn btn-sm btn-success">
                        <input type="radio" name="gender" id="female" value="female"> <i class="fa fa-check text-active"></i>女
                      </label>
                    @endif
                  </div>
                  <label>是否住宿</label>
                  <div class="btn-group" data-toggle="buttons">
                    @if (isset($observer) && $observer->accomodate == 1)
                      <label class="btn btn-sm btn-info active">
                        <input type="radio" name="accomodate" id="accomodateChoice" value="1" checked> <i class="fa fa-check text-active"></i>是
                       </label>
                    @else
                      <label class="btn btn-sm btn-info">
                        <input type="radio" name="accomodate" id="accomodateChoice" value="1"> <i class="fa fa-check text-active"></i>是
                      </label>
                    @endif
                    @if (isset($observer) && $observer->accomodate == 0)
                      <label class="btn btn-sm btn-success active">
                        <input type="radio" name="accomodate" id="noAccomodateChoice" value="0" checked> <i class="fa fa-check text-active"></i>否
                      </label>
                    @else
                      <label class="btn btn-sm btn-success">
                        <input type="radio" name="accomodate" id="noAccomodateChoice" value="0"> <i class="fa fa-check text-active"></i>否
                      </label>
                    @endif
                  </div>
                </div>
                <div class="checkbox m-t-lg">
                  <button type="submit" class="btn btn-sm btn-success pull-right text-uc m-t-n-xs"><strong>保存</strong></button>
                </div>
              </form>
              </div>
            </div>
          </div>
        </section>
        </div>
      </div><!-- /.modal-content -->
</div>
<script>
$('#delform').submit(function(e){
    e.preventDefault();
    $.post("{{ secure_url('/saveRegDel') }}", $('#delform').serialize(), function(receivedData){
        //if (receivedData == "success")
            $('#ajaxModal').modal('hide');
        //useTheResponseData(receivedData);
    });
});
$('#volform').submit(function(e){
    e.preventDefault();
    $.post("{{ secure_url('/saveRegVol') }}", $('#volform').serialize(), function(receivedData){
        //if (receivedData == "success")
            $('#ajaxModal').modal('hide');
        //useTheResponseData(receivedData);
    });
});
$('#obsform').submit(function(e){
    e.preventDefault();
    $.post("{{ secure_url('/saveRegObs') }}", $('#obsform').serialize(), function(receivedData){
        //if (receivedData == "success")
            $('#ajaxModal').modal('hide');
        //useTheResponseData(receivedData);
    });
});
</script>
