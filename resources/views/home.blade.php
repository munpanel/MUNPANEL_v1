@extends('layouts.app')
@section('home_active', 'active')
@push('scripts')
    <script src="{{secure_url('js/charts/easypiechart/jquery.easy-pie-chart.js')}}"></script>
    <script src="{{secure_url('/js/fuelux/fuelux.js')}}"></script>
    <script src="{{secure_url('/js/datepicker/bootstrap-datepicker.js')}}"></script>
@endpush
@push('css')
    <link href="{{secure_url('/js/fuelux/fuelux.css')}}" rel="stylesheet">
@endpush
@section('content')
      <section class="vbox">
        <header class="header bg-white b-b">
          <p>Welcome to BJMUNC 2017</p>
        </header>
        <section class="scrollable wrapper">
          <div class="row">
            <div class="col-lg-8">
              <section class="panel no-borders hbox">
                <aside class="bg-info lter r-l text-center v-middle">
                  <div class="wrapper">
                    <i class="fa fa-dribbble fa fa-4x"></i>
                    <p class="text-muted"><em>关于 BJMUNC</em></p>
                  </div>
                </aside>
                <aside>
                  <div class="pos-rlt">
                    <span class="arrow left hidden-xs"></span>
                    <div class="panel-body">
                      <p>
                        北京市高中生模拟联合国协会简称北京模联(BJMUN)，是一个完全由在校高中生创办、运营的模拟联合国组织。协会旨在提升中学生对于时政的认识与理解，提高演讲、辩论与写作能力，同时推广创新与合作精神，自2010年协会成立至今，北京模联已经在全市范围内举办模联会议十余次，每年参加会议的代表达600人次。北京模联正逐渐成为北京市内最有影响力的模联会议之一。BJMUNC为其冬季会议。
                      </p>
                    </div>
                    <!--footer class="panel-footer">
                      <p>This is a Slogan.</p>
                    </footer-->
                  </div>
                </aside>
              </section>
              <section class="panel no-borders hbox">
                <aside>
                  <div class="pos-rlt">
                    <span class="arrow right hidden-xs"></span>
                    <div class="panel-body">
                      <p>
                        点击右侧四字在新窗口中查看一轮通告
                      </p>
                    </div>
                  </div>
                </aside>
                <aside class="bg-primary clearfix lter r-r text-right v-middle">
                  <div class="wrapper">
                    <p class="text-muted h3 font-thin">
                      <a href="https://bjmun.org/bulletin/bjmunc-2017-announcement1/" target="_blank">一轮通告</a>
                    </p>
                  </div>
                </aside>
              </section>
              <section class="panel no-borders hbox">
                <aside class="bg-success lter r-l text-center v-middle">
                  <div class="wrapper">
                    <i class="fa fa-users fa fa-4x"></i>
                    <p class="text-muted"><em>组织团队</em></p>
                  </div>
                </aside>
                <aside>
                  <div class="pos-rlt">
                    <span class="arrow left hidden-xs"></span>
                    <div class="panel-body">
                      <p>
                        <b>秘书处:</b><br>秘书长，朱淇惠，北京师范大学附属中学<br>副秘书长，王靖之，中国人民大学附属中学<br>副秘书长，张亦弛，清华大学附属中学<br><br><b>核心学术团队:</b><br>中文学术总监，李潇涵，北京市第二中学<br>中文学术总监，姚楚州，北京市第十五中学<br>英文学术总监，易轩，北京市一零一中学<br>英文学术总监，熊亚馨，北京市第四中学<br><br><b>会务团队:</b><br>艺术总监，徐德尘<br>技术总监，杨昊燃，中国人民大学附属中学分校<br>会务总监，于浩然，北京市一零一中学<br>会务总监，衡莹嘉，北京市第二中学<br>会务总监，潘皓辰，北京市第五中学<br>财务总监，王煦彤，北京市一零一中学
                      </p>
                    </div>
                    <!--footer class="panel-footer">
                      <p>This is a Slogan.</p>
                    </footer-->
                  </div>
                </aside>
              </section>
              <!--div class="text-center m-b">
                <i class="fa fa-spinner fa fa-spin"></i>
              </div-->
            </div>
            <div class="col-lg-4">
               <section class="panel bg-danger lter no-borders">
                <div class="panel-body">
                  <span class="h4">{{ Reg::current()->name() }}</span>
                  <div class="text-center padder m-t">
                    <i class="fa fa-heart fa fa-4x"></i>
                  </div>
                </div>
                <footer class="panel-footer lt">
                  <!--center><b>Welcome to BJMUNC2017!</b></center><br>Please check the following information. If any of them is wrong, please send a feedback so that we can correct it.<br><b>Name:</b> Adam Yi<br><b>Gender:</b> Male<br><b>Telephone:</b> 18610713116<br><b>Email:</b> yixuan@procxn.org<br><b>Country:</b> NOT ASSIGNED YET<-->
                 <!--center><b>Welcome to BJMUNC2017!</b></center><br>您的报名信息如下，如有任何问题，请重新进入报名表单修改。如有任何其他问题，请联系official@bjmun.org<br><br><b>报姓名：</b>易轩<br><b>性别：</b>男<br><b>委员会：</b>ICAO<br><b>搭档：</b>Yassi<br><b>室友：</b>不住宿<br><b>身份证：</b>123456789012345678<br><b>电话：</b>18610713116<!-->
                 @if (Config::get('munpanel.registration_enabled'))
                 <center><b>Welcome to BJMUNC2017!</b></center><br>您的报名类型为<b> {{ Reg::current()->type == 'unregistered' ? '未注册' : (Reg::current()->type == 'delegate' ? '代表' : (Reg::current()->type == 'volunteer' ? '志愿者':'观察员')) }} </b>，如需查看当前报名信息或修改信息，请点击下方的表单按钮。<br>请注意，如您已通过审核，重新编辑信息将导致您回到待审核状态。<br>如有任何其他问题，请联系official@bjmun.org。<br>再次感谢您对北京市高中生模拟联合国协会的关注与支持。
                 @else
                  <center><b>Welcome to BJMUNC2017!</b></center><br>您的报名类型为<b> {{ Reg::current()->type == 'unregistered' ? '未注册' : (Reg::current()->type == 'delegate' ? '代表' : (Reg::current()->type == 'volunteer' ? '志愿者':'观察员')) }} </b>。目前已结束报名环节。
                 @endif
                </footer>
              </section>
              @if (Reg::current()->type == 'delegate')
                    <section class="panel bg-warning no-borders">
                      <div class="row">
                        <div class="col-xs-6">
                          <div class="wrapper">
                            <p>席位分配</p>
                            <p class="h4 font-bold">{{ Reg::current()->delegate->committee->is_allocated ? Reg::current()->delegate->nation->name : '未分配' }}</p>
                            <!--div class="progress progress-xs progress-striped active m-b-sm">
                              <div class="progress-bar progress-bar-warning" data-toggle="tooltip" data-original-title="{{ $percent }}%" style="width: {{ $percent }}%"></div>
                            </div-->
                              <div class="text-sm">点击下方按钮查看完整席位分配：</div>
                              <a href="{{ secure_url('/reg2.modal/delegate') }}" data-toggle="ajaxModal" class="btn btn-danger">预览 reg2.modal (代表)</a>
                              <a href="{{ secure_url('/reg2.modal/observer') }}" data-toggle="ajaxModal" class="btn btn-danger">预览 reg2.modal (观察员)</a>
                              <a href="{{ secure_url('/reg2.modal/volunteer') }}" data-toggle="ajaxModal" class="btn btn-danger">预览 reg2.modal (志愿者)</a>
                          </div>
                        </div>
                        <div class="col-xs-6 wrapper text-center">
                          <div class="inline m-t-sm">
                            <div class="easypiechart" data-percent="{{ Reg::current()->delegate->committee->is_allocated ? '100' : '0' }}" data-line-width="8" data-bar-color="#ffffff" data-track-Color="#c79d43" data-scale-Color="false" data-size="100">
                              <span class="h2">{{ Reg::current()->delegate->committee->is_allocated ? '100' : '0' }}</span>%
                            </div>
                          </div>
                        </div>
                      </div>
                    </section>
              @endif
              <section class="panel clearfix">
                <div class="panel-body">
                  <div class="clear">
                    Copyright 2016 BJMUN.<br>Proudly Powered by MUNPANEL.<br>
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
