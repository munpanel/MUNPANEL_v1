<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Account Verification | MUNPANEL</title>
  <link rel="icon" href="{{cdn_url('/images/favicon.ico')}}" type="image/x-icon" />
  <link rel="shortcut icon" href="{{cdn_url('/images/favicon.ico')}}" type="image/x-icon" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
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
            请验证您的电子邮箱
          </header>
            <div class="panel-body"><div class="alert alert-info">感谢您使用MUNPANEL！我们已发送一封激活邮件到您的邮箱({{Auth::user()->email}})。请根据邮件提示激活您的账户。未激活的账户将不能使用任何 MUNPANEL 服务，会议组织团队亦不可查看您的报名信息。</div></div>
<div class="line line-dashed"></div>
            <p class="text-muted text-center"><small>没有收到邮件?</small></p>
            <a href="{{ mp_url('/verifyEmail/resend') }}" class="btn btn-white btn-block">再发一封</a>
        </section>
      </div>
    </div>
  </section>
  <!-- footer -->
  <footer id="footer">
    <div class="text-center padder clearfix">
      <p><small>&copy; {{config('munpanel.copyright_year')}} MUNPANEL. All rights reserved.</small></p>
    </div>
  </footer>
  <!-- / footer -->
	<script src="{{cdn_url('js/jquery.min.js')}}"></script>
  <!-- Bootstrap -->
  <script src="{{cdn_url('js/bootstrap.js')}}"></script>
  <!-- app -->
  <script src="{{cdn_url('js/app.js')}}"></script>
  <script src="{{cdn_url('js/app.plugin.js')}}"></script>
  <script src="{{cdn_url('js/app.data.js')}}"></script>
  <!-- Parsley -->
  <script src="{{cdn_url('js/parsley/parsley.min.js')}}"></script>
  <script src="{{cdn_url('js/parsley/parsley.extend.js')}}"></script>
</body>
</html>
