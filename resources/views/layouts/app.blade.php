<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{Reg::currentConference()->name}} | MUNPANEL</title>

    <link rel="icon" href="{{secure_url('/images/favicon.ico')}}" type="image/x-icon" />
    <link rel="shortcut icon" href="{{secure_url('/images/favicon.ico')}}" type="image/x-icon" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{secure_url('/css/bootstrap.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{secure_url('/css/animate.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{secure_url('/css/font-awesome.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{secure_url('/css/font.css')}}" type="text/css" cache="false" />
    <link rel="stylesheet" href="{{secure_url('/css/plugin.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{secure_url('/css/app.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="{{secure_url('/css/munpanel.css')}}" type="text/css" />
    @stack('css')

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <!--[if lt IE 9]>
      <script src="{{secure_url('/js/ie/respond.min.js')}}" cache="false"></script>
      <script src="{{secure_url('/js/ie/html5.js')}}" cache="false"></script>
      <script src="{{secure_url('/js/ie/excanvas.js')}}" cache="false"></script>
      <script src="{{secure_url('/js/ie/fix.js')}}" cache="false"></script>
    <![endif]-->
@if (Config::get('analytics.enabled'))
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', "{{Config::get('analytics.trackingID')}}", 'auto');
      ga('set', 'userId', {{Auth::user()->id}});
      ga('send', 'pageview');

    </script>
@endif
</head>
<body>
  <section class="hbox stretch">
    <!-- .aside -->
    <aside class="bg-info aside-sm" id="nav">
      <section class="vbox">
        <header class="dker nav-bar nav-bar-fixed-top">
          <a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen" data-target="#nav">
            <i class="fa fa-bars"></i>
          </a>
          <a href="#" class="nav-brand" data-toggle="fullscreen">{{Reg::currentConference()->shortname}}</a>
          <a class="btn btn-link visible-xs" data-toggle="class:show" data-target=".nav-user">
            <i class="fa fa-comment-o"></i>
          </a>
        </header>
        <section>
          <!-- user -->
          <div class="bg-success nav-user hidden-xs pos-rlt">
            <div class="nav-avatar pos-rlt">
              <a href="#" class="thumb-sm avatar animated rollIn" data-toggle="dropdown">
                <img src="{{ 'https://www.gravatar.com/avatar/' . md5( strtolower( trim( Auth::user()->email ) ) ) . '?d='.secure_url('images/avatar.jpg').'&s=320' }}" alt="" class="">
                <span class="caret caret-white"></span>
              </a>
              <ul class="dropdown-menu m-t-sm animated fadeInLeft">
              	<span class="arrow top"></span>
                <!--li>
                  <a href="#">Settings</a>
                </li>
                <li>
                  <a href="profile.html">Profile</a>
                </li>
                <li>
                  <a href="#">
                    <span class="badge bg-danger pull-right">3</span>
                    Notifications
                  </a>
                </li>
                <li class="divider"></li>
                <li>
                  <a href="help.html">Help</a>
                </li-->
                <li>
                  <a href="{{ secure_url('/changePwd.modal') }}" data-toggle="ajaxModal">修改密码</a>
                  <a href="{{ secure_url('/help.html') }}">帮助</a>
                  <a href="{{ secure_url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">注销</a>
                </li>
              </ul>
              <div class="visible-xs m-t m-b">
                <a href="#" class="h3">{{ Auth::user()->name }}</a>
              </div>
            </div>
            <!--div class="nav-msg">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <b class="badge badge-white count-n">2</b>
              </a>
              <section class="dropdown-menu m-l-sm pull-left animated fadeInRight">
                <div class="arrow left"></div>
                <section class="panel bg-white">
                  <header class="panel-heading">
                    <strong>You have <span class="count-n">2</span> notifications</strong>
                  </header>
                  <div class="list-group">
                    <a href="#" class="media list-group-item">
                      <span class="pull-left thumb-sm">
                        <img src="images/avatar.jpg" alt="John said" class="img-circle">
                      </span>
                      <span class="media-body block m-b-none">
                        MUNPANEL is awesome<br>
                        <small class="text-muted">30 Jan 16</small>
                      </span>
                    </a>
                    <a href="#" class="media list-group-item">
                      <span class="media-body block m-b-none">
                        Hello World!<br>
                        <small class="text-muted">30 Jan 16</small>
                      </span>
                    </a>
                  </div>
                  <footer class="panel-footer text-sm">
                    <a href="#" class="pull-right"><i class="fa fa-cog"></i></a>
                    <a href="#">See all the notifications</a>
                  </footer>
                </section>
              </section>
            </div-->
          </div>
          <!-- / user -->
          <!-- nav -->
          <nav class="nav-primary hidden-xs">
            <ul class="nav">
                @if (Reg::current()->type == 'school')
                @include('layouts.school')
                @elseif (Reg::current()->type == 'ot')
                @include('layouts.ot')
                @elseif (Reg::current()->type == 'dais')
                @include('layouts.dais')
                @else
                @include('layouts.delegate')
                @endif
            </ul>
          </nav>
          <!-- / nav -->
          <!-- note -->
          <div class="bg-primary wrapper hidden-vertical animated fadeInUp text-sm">
              <!--a href="#" data-dismiss="alert" class="pull-right m-r-n-sm m-t-n-sm"><i class="fa fa-times"></i></a-->
              Proudly Powered and Copyrighted by 2016-2017 Console iT.
          </div>
          <!-- / note -->
        </section>
        <footer class="footer bg-gradient hidden-xs">
          <a href="modal.lockme.html" data-toggle="ajaxModal" class="btn btn-sm btn-link m-r-n-xs pull-right">
            <i class="fa fa-power-off"></i>
          </a>
          <a href="#nav" data-toggle="class:nav-vertical" class="btn btn-sm btn-link m-l-n-sm">
            <i class="fa fa-bars"></i>
          </a>
        </footer>
      </section>
    </aside>
    <!-- /.aside -->
    <!-- .vbox -->
    <section id="content">
        @yield('content')
      </section>
      <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>
    </section>
    <!-- /.vbox -->
  </section>
  <form id="logout-form" action="{{ secure_url('/logout') }}" method="POST" style="display: none;"> {{ csrf_field() }} </form>
	<script src="{{secure_url('/js/jquery.min.js')}}"></script>
  <!-- Bootstrap -->
  <script src="{{secure_url('/js/bootstrap.js')}}"></script>
 <!-- App -->
  <script src="{{secure_url('/js/app.js')}}"></script>
  <script src="{{secure_url('/js/app.plugin.js')}}"></script>
  <script src="{{secure_url('/js/app.data.js')}}"></script>
  <!-- Parsley -->
  <script src="{{secure_url('/js/parsley/parsley.min.js')}}"></script>
  <script src="{{secure_url('/js/parsley/parsley.extend.js')}}"></script>
  @stack('scripts')
  @if (isset($notice_msg))
  <script>
                var $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"><div class="row"><div class="col-sm-12 b-r"><div class="alert alert-danger"><b>{{$notice_msg}}</b></div></div></div></div></div></div></div></div>');
                $('body').append($modal);
                $modal.modal();
  </script>
  @endif
</body>
</html>

