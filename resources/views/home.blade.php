@php
$hasRegAssignment = false;
if (Reg::current()->type == 'delegate' && isset(Reg::current()->delegate))
{
    if (Reg::current()->delegate->hasRegAssignment() > 0) $hasRegAssignment = true;
}
@endphp
@extends('layouts.app')
@section('home_active', 'active')
@push('scripts')
    <script src="{{cdn_url('js/charts/easypiechart/jquery.easy-pie-chart.js')}}"></script>
    <script src="{{cdn_url('/js/fuelux/fuelux.js')}}"></script>
    <script src="{{cdn_url('/js/datepicker/bootstrap-datepicker.js')}}"></script>
    @if ((!Auth::user()->verified()) || (Reg::currentConference()->status == 'reg' && Reg::current()->type == 'unregistered') || (!Reg::selectConfirmed()) || (!Reg::current()->enabled) || (null!==(Reg::current()->specific()) && Reg::current()->specific()->status == 'fail') || ($hasRegAssignment) || (Reg::current()->type != 'unregistered' && is_null(Reg::current()->specific())) || (!isset(Reg::current()->accomodate)))
    <script src="{{cdn_url('/js/reg.firsttime.js')}}"></script>
    @endif
@endpush
@push('css')
    <link href="{{cdn_url('/js/fuelux/fuelux.css')}}" rel="stylesheet">
@endpush
@section('content')
      <section class="vbox">
        <header class="header bg-white b-b">
          <p>Welcome to {{Reg::currentConference()->name}}</p>
        </header>
        <section class="scrollable wrapper">
          <div class="row">
            <div class="col-lg-8">
              <section class="panel no-borders hbox">
                <aside class="bg-info lter r-l text-center v-middle">
                  <div class="wrapper">
                    <i class="fa fa-dribbble fa fa-4x"></i>
                    <p class="text-muted"><em>关于 {{Reg::currentConference()->shortname}}</em></p>
                  </div>
                </aside>
                <aside>
                  <div class="pos-rlt">
                    <span class="arrow left hidden-xs"></span>
                    <div class="panel-body">
                      <p>
                        {{Reg::currentConference()->description}}
                      </p>
                    </div>
                    <!--footer class="panel-footer">
                      <p>This is a Slogan.</p>
                    </footer-->
                  </div>
                </aside>
              </section>
              @if (Reg::currentConferenceID() == 2)
              <section class="panel no-borders hbox">
                <aside>
                  <div class="pos-rlt">
                    <span class="arrow right hidden-xs"></span>
                    <div class="panel-body">
                      <p>
                        点击右侧四字在新窗口中查看会议手册
                      </p>
                    </div>
                  </div>
                </aside>
                <aside class="bg-primary clearfix lter r-r text-right v-middle">
                  <div class="wrapper">
                    <p class="text-muted h3 font-thin">
                      <a href="https://romun.net/files/2017/03/ROMUNC2017会议手册1.1.0.pdf" target="_blank">会议手册</a>
                    </p>
                  </div>
                </aside>
              </section>
              @endif
              <section class="panel no-borders hbox">
                <aside>
                  <div class="pos-rlt">
                    <span class="arrow right hidden-xs"></span>
                    <div class="panel-body wrapper text-center">
                      <span>距离会议开始天数</span><p class="h1">{{date_create(date('Y-m-d'))->diff(date_create(Reg::currentConference()->date_start))->format('%a')}}</p>
                    </div>
                  </div>
                </aside>
                <aside class="bg-success r-l text-center v-middle">
                  <div class="wrapper">
                    <i class="fa fa-clock-o fa-4x"></i>                      
                    <p class="text-muted"><em>倒计时</em></p>
                  </div>
                </aside>
              </section>
              <!--div class="text-center m-b">
                <i class="fa fa-spinner fa fa-spin"></i>
              </div-->
              @if (is_object(Reg::current()->school))
              <section class="panel no-borders hbox">
                <aside class="bg-primary lter r-l text-center v-middle">
                  <div class="wrapper">
                    <i class="fa fa-users fa fa-4x"></i>
                    <p class="text-muted"><em>您作为 {{Reg::current()->school->name}} 成员参会</em></p>
                  </div>
                </aside>
                <aside>
                  <div class="pos-rlt">
                    <span class="arrow left hidden-xs"></span>
                    <div class="panel-body">
                      <p>
                        {{Reg::current()->school->description}}
                      </p>
                    </div>
                    <!--footer class="panel-footer">
                      <p>This is a Slogan.</p>
                    </footer-->
                  </div>
                </aside>
              </section>
              @endif
            </div>
            <div class="col-lg-4">
              @if (is_object(Reg::current()->school))
              @if (Reg::current()->school->isAdmin() && (!Reg::current()->school->isAdmin(Reg::currentConferenceID())))
              <section class="panel text-center bg-success dker">
                  <div class="panel-body">
                    <h4 class="text-uc">创建团队管理身份</h4>
                    <p>您已选择以{{Reg::current()->school->name}}成员参与会议。而您是该团队全局管理员，是否创建一个针对本会议的团队管理员身份以管理团队报名信息？</p>
                    <a href="{{mp_url('createTeamAdmin')}}" onclick="loader(this)" class="btn btn-info m-t-lg">创建</a>
                  </div>
               </section>
              @endif
              @elseif (Auth::user()->schools->count() == 0)
              <section class="panel text-center bg-success dker">
                  <div class="panel-body">
                    <h4 class="text-uc">加入团队</h4>
                    <p>个人报名可无视，创建新团队请前往 PORTAL 页</p>
                        <form id="joinTeamForm" class="m-t-lg m-l m-r m-b-sm" data-validate="parsley" action="{{route('doJoinTeam')}}" method="post">
                            {{csrf_field()}}
                            <input type="hidden" name="reg_id" value="{{Reg::currentID()}}">
                                <div class="input-group input-group-lg">
                                    <span class="input-group-addon lt no-border" style="color:white"><i class="fa fa-key" aria-hidden="true"></i></span>
                                    <input type="text" name="code" class="form-control input-lg no-border" placeholder="由您的团队管理员提供" autocomplete='off'>
                                </div>
                               <a onclick="if ($('#joinTeamForm').parsley('validate')){loader(this); $('#joinTeamForm').submit();}" class="btn btn-info m-t-lg">加入</a>
                         </form>
                  </div>
               </section>
               @else
              <section class="panel text-center bg-success dker">
                  <div class="panel-body">
                    <h4 class="text-uc">选择团队</h4>
                    <p>个人报名可无视，加入新团队请前往 PORTAL 页</p>
                        <form id="selectTeamForm" class="m-t-lg m-l m-r m-b-sm" data-validate="parsley" action="{{mp_url('/doSelectTeam')}}" method="post">
                            {{csrf_field()}}
                                <div class="input-group input-group-lg">
                                    <span class="input-group-addon lt no-border" style="color:white"><i class="fa fa-users" aria-hidden="true"></i></span>
                                    <select  name="team" class="form-control input-lg no-border">
                                    @foreach (Auth::user()->schools as $school)
                                    <option value="{{$school->id}}">{{$school->name}}</option>
                                    @endforeach
                                    </select>
                                </div>
                               <a onclick="if ($('#selectTeamForm').parsley('validate')){loader(this); $('#selectTeamForm').submit();}" class="btn btn-info m-t-lg">选择</a>
                         </form>
                  </div>
               </section>
               @endif
               @if (Auth::user()->orders()->where('conference_id', Reg::currentConferenceID())->where('status', 'unpaid')->count() > 0)
              <section class="panel text-center bg-warning dker">
                  <div class="panel-body">
                    <h4 class="text-uc">待缴费订单</h4>
                    <p>您有{{Auth::user()->orders()->where('conference_id', Reg::currentConferenceID())->where('status', 'unpaid')->count()}}个待缴费订单</p>
                    <a href="{{ mp_url('/store/orders') }}" class="btn btn-danger">查看详情</a>
                  </div>
               </section>
               @endif
               <section class="panel bg-danger lter no-borders">
                <div class="panel-body">
                  <span class="h4">{{ Reg::current()->name() }}</span>
                  <div class="text-center padder m-t">
                    <i class="fa fa-heart fa fa-4x"></i>
                  </div>
                </div>
                <footer class="panel-footer lt">
                  <!--center><b>Welcome to {{Reg::currentConference()->name}}!</b></center><br>Please check the following information. If any of them is wrong, please send a feedback so that we can correct it.<br><b>Name:</b> Adam Yi<br><b>Gender:</b> Male<br><b>Telephone:</b> 18610713116<br><b>Email:</b> yixuan@procxn.org<br><b>Country:</b> NOT ASSIGNED YET<-->
                 <!--center><b>Welcome to {{Reg::currentConference()->name}}!</b></center><br>您的报名信息如下，如有任何问题，请重新进入报名表单修改。如有任何其他问题，请联系official@bjmun.org<br><br><b>报姓名：</b>易轩<br><b>性别：</b>男<br><b>委员会：</b>ICAO<br><b>搭档：</b>Yassi<br><b>室友：</b>不住宿<br><b>身份证：</b>123456789012345678<br><b>电话：</b>18610713116<!-->
                 <center><b>Welcome to {{Reg::currentConference()->name}}!</b></center>
                 @if (in_array(Reg::current()->type, ['dais', 'ot']))
                 <br>您已成功提交{{Reg::current()->type == 'dais' ? '学术' : '会务'}}团队申请
                 @else
                 <br>您的报名类型为<b> {{ Reg::current()->type == 'unregistered' ? '未注册' : (Reg::current()->type == 'delegate' ? '代表' : (Reg::current()->type == 'volunteer' ? '志愿者':'观察员')) }}</b>
                 @endif
                 @if (validateRegDate(Reg::current()->type))
                 {!!Reg::current()->type == 'unregistered' ? '，请点击下方的报名按钮开始报名流程。' : '，如需查看当前报名信息或修改信息，请点击下方的表单按钮。<br>请注意，如您已通过审核，重新编辑信息将导致您回到待审核状态。'!!}<br>
                 @else
                  。当前报名已截止，您无法编辑报名信息，如需查看当前报名信息，请点击下方的表单按钮。
                 @endif
                </footer>
              </section>
              @if (Reg::currentConference()->status == 'daisreg')
                @include('components.daisregStatus')
              @elseif (Reg::currentConference()->status == 'reg')
                @include('components.regStatus')
              @endif
              <section class="panel clearfix">
                <div class="panel-body">
                  <div class="clear">
                    Proudly Powered by MUNPANEL.<br>Copyright {{config('munpanel.copyright_year')}} Console iT.
                    @if(null !== config('munpanel.icp_license'))
                    <br><a href="http://www.miibeian.gov.cn/" title="{{config('munpanel.icp_license')}}" rel="nofollow">{{config('munpanel.icp_license')}}</a>
                    @endif
                  </div>
                </div>
              </section>
            </div>
          </div>
        </section>
      </section>
      <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
    </section>
    <!-- /.vbox -->
@endsection
