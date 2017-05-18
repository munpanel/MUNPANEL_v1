<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Sign In | MUNPANEL</title>
  <link rel="icon" href="{{cdn_url('/images/favicon.ico')}}" type="image/x-icon" />
  <link rel="shortcut icon" href="{{cdn_url('/images/favicon.ico')}}" type="image/x-icon" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <meta name="keywords" content="MUNPANEL,MUN,Model UN,Model United Nations,United Nations,UN,PANEL,模联,模拟联合国">
  <meta name="description" content="Sign in to your MUNPANEL account.">
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
@if (Config::get('analytics.enabled'))
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

      ga('create', "{{Config::get('analytics.trackingID')}}", 'auto');
      ga('send', 'pageview');

    </script>
@endif
</head>
<body>
  <section id="content" class="m-t-lg wrapper-md animated fadeInUp">
    <a class="nav-brand" href="#">MUNPANEL</a>
    <div class="row m-n">
      <div class="col-md-4 col-md-offset-4 m-t-lg">
        <section class="panel">
          <header class="panel-heading text-center">
            登陆&nbsp({{isset($mailLogin)?"Console Mail":"MUNPANEL"}}&nbsp账号)
          </header>
          @if (count($errors) > 0)
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif
          <form action="{{ isset($mailLogin)?mp_url('/loginMail'):mp_url('/login') }}" method="post" class="panel-body" data-validate="parsley">
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label">Email</label>
              <input type="text" id="email" name="email" placeholder="eg. yixuan@bjmun.org" class="form-control" data-required="true" value="{{ old('email') }}" autofocus>
            </div>
            <div class="form-group">
              <label class="control-label">密码</label>
              <input type="password" id="password" name="password" class="form-control" data-required="true">
            </div>
            <div id="embed-captcha"></div>
            <p id="wait" class="show">正在加载验证码......</p>
            <p id="notice" class="hide">请先完成验证</p>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="remember"> 记住我
              </label>
            </div>
            <a href="{{ mp_url('/password/reset') }}" class="pull-right m-t-xs"><small>忘记密码?</small></a>
            <button type="submit" class="btn btn-info" id="login-submit" onclick="loader(this)">登陆</button>
<div class="line line-dashed"></div>
            <p class="text-muted text-center"><small>没有账号?</small></p>
            <a href="{{ mp_url('/register') }}" class="btn btn-white btn-block">新建帐号并报名会议</a>
            @if (isset($mailLogin))
            <a href="{{ mp_url('/login') }}" class="btn btn-white btn-block">使用 MUNPANEL 账号登录</a>
            @else
            <a href="{{ mp_url('/loginViaConsoleMail') }}" class="btn btn-white btn-block">使用 Console Mail 账号登录</a>
            @endif
          </form>
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
