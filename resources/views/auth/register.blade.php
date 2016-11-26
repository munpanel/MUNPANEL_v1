<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Registration | MUNPANEL</title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="css/animate.css" type="text/css" />
  <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="css/font.css" type="text/css" cache="false" />
  <link rel="stylesheet" href="css/plugin.css" type="text/css" />
  <link rel="stylesheet" href="css/app.css" type="text/css" />
  <!--[if lt IE 9]>
    <script src="js/ie/respond.min.js" cache="false"></script>
    <script src="js/ie/html5.js" cache="false"></script>
    <script src="js/ie/fix.js" cache="false"></script>
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
  <section id="content" class="m-t-lg wrapper-md animated fadeInDown">
    <a class="nav-brand" href="#">MUNPANEL</a>
    <div class="row m-n">
      <div class="col-md-4 col-md-offset-4 m-t-lg">
        <section class="panel">
          <header class="panel-heading bg bg-primary text-center">
            注册
          </header>
{{$errors->first('password')}}
          <form action="{{ secure_url('/register') }}" method="post" class="panel-body" data-validate="parsley">
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label">真实姓名</label>
              <input type="text" name="name" placeholder="eg. 易轩" class="form-control" data-required="true" value="{{ old('name') }}">
            </div>
            <div class="form-group">
              <label class="control-label">Email</label>
              <input type="text" name="email" placeholder="eg. yixuan@bjmun.org" class="form-control" data-required="true" data-type="email" value="{{ old('email') }}">
            </div>
            <div class="form-group">
              <label class="control-label">密码</label>
              <input type="password" name="password" id="inputPassword" placeholder="Password" class="form-control" data-required="true">
            </div>
            <div class="form-group">
              <label class="control-label">确认密码</label>
              <input type="password" name="password_confirmation" data-equalto="#inputPassword" data-required="true" class="form-control" date-required="true">
            </div>
            <!--div class="form-group">
              <label class="control-label">Grade</label>
              <input type="text" placeholder="高一" class="form-control" data-required="true">
            </div-->
            <div class="checkbox">
              <label>
                <input type="checkbox" name="check" data-required="true"> 我就读于成员校</a>
              </label>
            </div>
            <button type="submit" class="btn btn-info">注册</button>
            <div class="line line-dashed"></div>
            <p class="text-muted text-center"><small>已有账号?</small></p>
            <a href="{{ secure_url('/login') }}" class="btn btn-white btn-block">登陆</a>
          </form>
        </section>
      </div>
    </div>
  </section>
  <!-- footer -->
  <footer id="footer">
    <div class="text-center padder clearfix">
      <p>
        <small>&copy; 2016 MUNPANEL. All rights reserved.</small>
      </p>
    </div>
  </footer>
  <!-- / footer -->
	<script src="js/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="js/bootstrap.js"></script>
  <!-- app -->
  <script src="js/app.js"></script>
  <script src="js/app.plugin.js"></script>
  <script src="js/app.data.js"></script>
  <!-- Parsley -->
  <script src="js/parsley/parsley.min.js"></script>
  <script src="js/parsley/parsley.extend.js"></script>
</body>
</html>
