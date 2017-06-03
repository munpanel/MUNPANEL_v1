<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>{{Reg::currentConference()->name}} | MUNPANEL</title>
  <link rel="icon" href="{{cdn_url('/images/favicon.ico')}}" type="image/x-icon" />
  <link rel="shortcut icon" href="{{cdn_url('/images/favicon.ico')}}" type="image/x-icon" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <meta name="keywords" content="MUNPANEL,MUN,Model UN,Model United Nations,United Nations,UN,PANEL,模联,模拟联合国">
  <meta name="description" content="Your identity has been banned.">
  <meta name="copyright" content="Proudly Powered and Copyrighted by {{config('munpanel.copyright_year')}} MUNPANEL. A Product of Console iT.">
  <meta name="generator" content="MUNPANEL System">
  <meta name="author" content="Adam Yi">
  <link rel="stylesheet" href="{{cdn_url('css/bootstrap.css')}}" type="text/css" />
  <link rel="stylesheet" href="{{cdn_url('css/animate.css')}}" type="text/css" />
  <link rel="stylesheet" href="{{cdn_url('css/font-awesome.min.css')}}" type="text/css" />
  <link rel="stylesheet" href="{{cdn_url('css/font.css')}}" type="text/css" cache="false" />
  <link rel="stylesheet" href="{{cdn_url('css/plugin.css')}}" type="text/css" />
  <link rel="stylesheet" href="{{cdn_url('css/app.css')}}" type="text/css" />
  <!--[if lt IE 9]>
    <script src="{{cdn_url('js/ie/respond.min.js')}}" cache="false"></script>
    <script src="{{cdn_url('js/ie/html5.js')}}" cache="false"></script>
    <script src="{{cdn_url('js/ie/fix.js')}}" cache="false"></script>
  <![endif]-->
  @include('layouts.analytics')
</head>
<body>
  <section id="content" class="m-t-lg wrapper-md animated fadeInUp">
    <a class="nav-brand" href="#">MUNPANEL</a>
    <div class="row m-n">
      <div class="col-md-4 col-md-offset-4 m-t-lg">
        <section class="panel">
          <header class="panel-heading text-center">
              身份被禁用
          </header>
              <div class="alert alert-danger">
              您当前登陆{{Reg::currentConference()->name}}的身份（{{Reg::current()->regText()}}）已被系统／会议管理员禁用。如您的账号在该会议中有其他身份，您可切换为其他身份登陆。
              </div>
              <a href="{{ mp_url('/logout.reg') }}" class="btn btn-white btn-block">切换其他身份</a>
              <a href="{{ route('logout') }}" class="btn btn-white btn-block" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">注销</a>
        </section>
      </div>
    </div>
  </section>
  <!-- footer -->
  <footer id="footer">
    <div class="text-center padder clearfix">
      <p><small>&copy; {{config('munpanel.copyright_year')}} MUNPANEL. All rights reserved.
      @if(null !== config('munpanel.icp_license'))
      <br/><a href="http://www.miibeian.gov.cn/" title="{{config('munpanel.icp_license')}}" rel="nofollow">{{config('munpanel.icp_license')}}</a>
      @endif
      </small></p>
    </div>
  </footer>
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> {{ csrf_field() }} </form>
  <!-- / footer -->
	<script src="{{cdn_url('js/jquery.min.js')}}"></script>
  <!-- Bootstrap -->
  <script src="{{cdn_url('js/bootstrap.js')}}"></script>
  <script src="{{cdn_url('/js/gt.js')}}"></script>
  <!-- app -->
  <script src="{{cdn_url('js/app.js')}}"></script>
  <script src="{{cdn_url('js/app.plugin.js')}}"></script>
  <script src="{{cdn_url('js/app.data.js')}}"></script>
  <!-- Parsley -->
  <script src="{{cdn_url('js/parsley/parsley.min.js')}}"></script>
  <script src="{{cdn_url('js/parsley/parsley.extend.js')}}"></script>
    <script>
        var handlerEmbed = function (captchaObj) {
            $("#login-submit").click(function (e) {
                var validate = captchaObj.getValidate();
                if (!validate) {
                    $("#notice")[0].className = "show";
                    setTimeout(function () {
                        $("#notice")[0].className = "hide";
                    }, 2000);
                    e.preventDefault();
                }
            });
            captchaObj.appendTo("#embed-captcha");
            captchaObj.onReady(function () {
                $("#wait")[0].className = "hide";
            });
        };
        $.ajax({
            url: "{{mp_url('startCaptchaServlet?t=')}}" + (new Date()).getTime(), // prevent cache
            type: "get",
            dataType: "json",
            success: function (data) {
                //console.log(data);
                initGeetest({
                    gt: data.gt,
                    challenge: data.challenge,
                    new_captcha: data.new_captcha,
                    width: '100%',
                    product: "float",
                    offline: !data.success,
                    protocol: 'https://'
                }, handlerEmbed);
            }
        });
    </script>
  <script>setInterval(function(){var e=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject('Microsoft.XMLHTTP');e.open('GET','{{mp_url('/keepalive')}}',!0);e.setRequestHeader('X-Requested-With','XMLHttpRequest');e.send();}, 1200000);</script>
</body>
</html>
