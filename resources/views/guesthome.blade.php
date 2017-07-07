@extends('layouts.app')
@section('home_active', 'active')
@push('scripts')
    <script src="{{cdn_url('js/charts/easypiechart/jquery.easy-pie-chart.js')}}"></script>
    <script src="{{cdn_url('/js/fuelux/fuelux.js')}}"></script>
    <script src="{{cdn_url('/js/datepicker/bootstrap-datepicker.js')}}"></script>
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
              <section class="panel dker">
                    <p>The following content is sponsored by Google based on machine learning algorithm. Neither Console iT nor the conference endorses it.</p>
                    @include('layouts.adsense')
               </section>
              <!--div class="text-center m-b">
                <i class="fa fa-spinner fa fa-spin"></i>
              </div-->
            </div>
            <div class="col-lg-4">
               <section class="panel bg-danger lter no-borders">
                <div class="panel-body">
                  <span class="h4"></span>
                  <div class="text-center padder m-t">
                    <i class="fa fa-heart fa fa-4x"></i>
                  </div>
                </div>
                <footer class="panel-footer lt">
                  <!--center><b>Welcome to {{Reg::currentConference()->name}}!</b></center><br>Please check the following information. If any of them is wrong, please send a feedback so that we can correct it.<br><b>Name:</b> Adam Yi<br><b>Gender:</b> Male<br><b>Telephone:</b> 18610713116<br><b>Email:</b> yixuan@procxn.org<br><b>Country:</b> NOT ASSIGNED YET<-->
                 <!--center><b>Welcome to {{Reg::currentConference()->name}}!</b></center><br>您的报名信息如下，如有任何问题，请重新进入报名表单修改。如有任何其他问题，请联系official@bjmun.org<br><br><b>报姓名：</b>易轩<br><b>性别：</b>男<br><b>委员会：</b>ICAO<br><b>搭档：</b>Yassi<br><b>室友：</b>不住宿<br><b>身份证：</b>123456789012345678<br><b>电话：</b>18610713116<!-->
                 <center><b>Welcome to {{Reg::currentConference()->name}}!</b></center>
                 如欲报名会议，或已报名会议，烦请登录账号查看详情。
                </footer>
              </section>              
            <section class="panel bg-warning no-borders">
            <div class="row">
                <div class="col-xs-6">
                <div class="wrapper">
                    <p>登录／注册</p>
                    <div class="text-sm">点击下方按钮登录或注册：</div>
                    <a href="{{ mp_url('/login') }}" class="btn btn-danger">登录／注册</a>
                </div>
                </div>
                <div class="col-xs-6 wrapper text-center">
                <div class="inline m-t-sm">
                    <div class="easypiechart" data-percent="0" data-line-width="8" data-bar-color="#ffffff" data-track-Color="#c79d43" data-scale-Color="false" data-size="100">
                    <span class="h2">0</span>%
                    </div>
                </div>
                </div>
            </div>
            </section>
              <section class="panel clearfix">
                <div class="panel-body">
                  <div class="clear">
                    This content is neither created nor endorsed by Console iT.<br>
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
