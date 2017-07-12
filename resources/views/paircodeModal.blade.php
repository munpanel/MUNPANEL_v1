@php
$type = "";
if ($ispartner)
    $type .= '<strong>搭档</strong>';
if ($ispartner && $isroommate)
    $type .= '和';
if ($isroommate)
    $type .= '<strong>室友</strong>';
@endphp
<div class="modal-dialog">
      <div class="modal-content">
      <header class="header bg-dark bg-gradient">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#choose" class="pay-tabs" data-toggle="tab" aria-expanded="true">输入配对码</a></li>
            <li><a href="#gencode" class="pay-tabs tee-tabs" data-toggle="tab" channel="alipay" aria-expanded="false">生成配对码</a></li>
          </ul>
        </header>
        <div class="tab-content">
        <section class="tab-pane active" id="choose">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                <div class="m-b-sm">
                  <form method="post" action="{{mp_url('/doPair')}}">
                    {{csrf_field()}}
                    <input type="hidden" name="reg_id" value="{{Reg::currentID()}}">
                    <div class="form-group">
                      <label>输入配对码</label>
                      <input name="code" class="form-control" type="text" placeholder="12345678" data-required="true">
                    </div>
                    <div class="form-group">
                      <label class="m-r-sm">请选择配对类型</label>
                      <div class="btn-group" data-toggle="buttons">
                        @if (Reg::currentConference()->isPartnerAutopaired() && Reg::current()->type == 'delegate')
                        <label class="btn btn-sm btn-white{{Reg::current()->delegate->committee->is_dual ? '' : ' disabled'}}">
                          <input name="partner" id="partner" type="checkbox"><i class="fa fa-users"></i> 搭档
                        </label>
                        @endif
                        @if (Reg::currentConference()->isRoommateAutopaired())
                        <label class="btn btn-sm btn-white{{Reg::current()->accomodate ? '' : ' disabled'}}">
                          <input name="roommate" id="roommate" type="checkbox"><i class="fa fa-bed"></i> 室友
                        </label>
                        @endif
                      </div>
                    </div>
                    <button type="submit" class="btn btn-success" href="#modal-form">配对</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </section>
        <section class="tab-pane" id="gencode">
          <div class="modal-body">
            <div class="row">
              <div class="col-sm-12 b-r">
                @if (!empty($mycode))
                <div class="form-group">
                  <label>我的配对码</label>
                  <p>您的配对码为：</p>
                  <center id="hiddenCode"><h3><code>********</code></h3></center>
                  <center id="shownCode" style="display: none;"><h3><code>{{$mycode}}</code></h3></center>
                  <p>该配对码适用于{!!$type!!}配对。请将此邀请码告知您的搭档或室友，对方将可通过“输入配对码”选项卡与您配对为搭档或室友。</p>
                  <button class="btn btn-info" id="showButton" onclick="$('#showButton').hide(); $('#hideButton').show(); $('#hiddenCode').hide(); $('#shownCode').show();" type="button">显示配对码</button>
                  <button class="btn btn-info" id="hideButton" style="display: none;"onclick="$('#showButton').show(); $('#hideButton').hide(); $('#hiddenCode').show(); $('#shownCode').hide();" type="button">隐藏配对码</button>
                  <a class="btn btn-danger" href="{{mp_url('/deletePaircode/'.$mycode)}}">删除配对码</a>
                </div>
                @else
                <div class="form-group">
                  <label>我的配对码</label>
                  <p>您还没有生成配对码。<br>
                  请选择您希望配对的类型，并点击“生成配对码”按钮。</p>
                </div>
                <form method="post" action="{{mp_url('/genPaircode')}}">
                  {{csrf_field()}}
                  <input type="hidden" name="reg_id" value="{{Reg::currentID()}}">
                  <div class="form-group">
                    <label class="m-r-sm">请选择配对码类型</label>
                    <div class="btn-group" data-toggle="buttons">
                      @if (Reg::currentConference()->isPartnerAutopaired() && Reg::current()->type == 'delegate')
                      <label class="btn btn-sm btn-white{{Reg::current()->delegate->committee->is_dual ? '' : ' disabled'}}">
                        <input name="partner" id="partner" type="checkbox"><i class="fa fa-users"></i> 搭档
                      </label>
                      @endif
                      @if (Reg::currentConference()->isRoommateAutopaired())
                      <label class="btn btn-sm btn-white{{Reg::current()->accomodate ? '' : ' disabled'}}">
                        <input name="roommate" id="roommate" type="checkbox"><i class="fa fa-bed"></i> 室友
                      </label>
                      @endif
                    </div>
                  </div>
                  <button type="submit" class="btn btn-success" href="#modal-form">生成配对码</button>
                </form>
                @endif
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
</div>
