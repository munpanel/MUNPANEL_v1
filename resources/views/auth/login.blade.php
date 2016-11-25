<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Sign In | MUNPANEL</title>
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
</head>
<body>
  <section id="content" class="m-t-lg wrapper-md animated fadeInUp">
    <a class="nav-brand" href="#">MUNPANEL</a>
    <div class="row m-n">
      <div class="col-md-4 col-md-offset-4 m-t-lg">
        <section class="panel">
          <header class="panel-heading text-center">
            登陆
          </header>
          <form action="{{ secure_url('/login') }}" method="post" class="panel-body" data-validate="parsley">
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label">Email</label>
              <input type="text" id="email" name="email" placeholder="eg. yixuan@bjmun.org" class="form-control" data-required="true" value="{{ old('email') }}" autofocus>
            </div>
            <div class="form-group">
              <label class="control-label">密码</label>
              <input type="password" id="password" name="password" class="form-control" data-required="true">
            </div>
            <div class="checkbox">
              <label>
                <input type="checkbox" name="remember"> 记住我
              </label>
            </div>
            <a href="{{ secure_url('/password/reset') }}" class="pull-right m-t-xs"><small>忘记密码?</small></a>
            <button type="submit" class="btn btn-info">登陆</button>
<div class="line line-dashed"></div>
            <p class="text-muted text-center"><small>没有账号?</small></p>
            <a href="{{ secure_url('/register') }}" class="btn btn-white btn-block">新建帐号（限成员校）</a>
          </form>
        </section>
      </div>
    </div>
  </section>
  <!-- footer -->
  <footer id="footer">
    <div class="text-center padder clearfix">
      <p><small>&copy; 2016 MUNPANEL. All rights reserved.</small></p>
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
