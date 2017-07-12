<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Registration | MUNPANEL</title>
  <link rel="icon" href="{{cdn_url('/images/favicon.ico')}}" type="image/x-icon" />
  <link rel="shortcut icon" href="{{cdn_url('/images/favicon.ico')}}" type="image/x-icon" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <meta name="keywords" content="MUNPANEL,MUN,Model UN,Model United Nations,United Nations,UN,PANEL,模联,模拟联合国">
  <meta name="description" content="Register a MUNPANEL account.">
  <meta name="copyright" content="Proudly Powered and Copyrighted by {{config('munpanel.copyright_year')}} MUNPANEL.">
  <meta name="generator" content="MUNPANEL System">
  <meta name="author" content="Adam Yi">
  <link rel="stylesheet" href="{{cdn_url('css/bootstrap.css')}}" type="text/css" />
  <link rel="stylesheet" href="{{cdn_url('css/animate.css')}}" type="text/css" />
  <link rel="stylesheet" href="{{cdn_url('css/font-awesome.min.css')}}" type="text/css" />
  <link rel="stylesheet" href="{{cdn_url('css/font.css')}}" type="text/css" cache="false" />
  <link rel="stylesheet" href="{{cdn_url('css/plugin.css')}}" type="text/css" />
  <link rel="stylesheet" href="{{cdn_url('css/app.css')}}" type="text/css" />
  <!--[if lt IE 9]>
    <script src="js/ie/respond.min.js" cache="false"></script>
    <script src="js/ie/html5.js" cache="false"></script>
    <script src="js/ie/fix.js" cache="false"></script>
  <![endif]-->
  @include('layouts.analytics')
  <script>
  function onSubmit(token) {
      $('#regForm').submit();
  }
  </script>
  <script src="https://{{config('recaptcha.domain')}}/recaptcha/api.js" async defer></script>
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
          @if (count($errors) > 0)
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif
{{--$errors->first('password')--}}
          <form action="{{ mp_url('/register') }}" method="post" class="panel-body" data-validate="parsley" id="regForm">
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label">真实姓名</label>
              <input type="text" name="name" placeholder="eg. 易轩" class="form-control" data-required="true" value="{{ old('name') }}">
            </div>
            <div class="form-group">
              <label class="control-label">Email</label>
              <input type="text" name="email" placeholder="eg. adamxuanyi@gmail.com" class="form-control" data-required="true" data-type="email" value="{{ old('email') }}">
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
            <!--div class="checkbox">
              <label>
                <input type="checkbox" name="check" data-required="true"> 我就读于成员校</a>
              </label>
            </div-->
            <button class="btn btn-info" type="submit" id="reg-submit">注册</button>
             <div id='recaptcha' class="g-recaptcha"
                  data-sitekey="{{config('recaptcha.sitekey')}}"
                  data-callback="onSubmit"
                  data-size="invisible"></div>
            <div class="line line-dashed"></div>
            <p class="text-muted text-center"><small>已有账号?</small></p>
            <a href="{{ mp_url('/login') }}" class="btn btn-white btn-block">登陆</a>
          </form>
        </section>
      </div>
    </div>
  </section>
  <!-- footer -->
  <footer id="footer">
    <div class="text-center padder clearfix">
      <p>
        <small>&copy; {{config('munpanel.copyright_year')}} MUNPANEL. All rights reserved.
        @if(null !== config('munpanel.icp_license'))
        <br/><a href="http://www.miibeian.gov.cn/" title="{{config('munpanel.icp_license')}}" rel="nofollow">{{config('munpanel.icp_license')}}</a>
        @endif</small>
      </p>
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
  $('#reg-submit').click(function(e) {
      e.preventDefault();
      if ($('#regForm').parsley('validate')) {
          loader(this);
          grecaptcha.execute();
      } else
          grecaptcha.reset();
  });
  </script>
  <script>setInterval(function(){var e=window.XMLHttpRequest?new XMLHttpRequest:new ActiveXObject('Microsoft.XMLHTTP');e.open('GET','{{mp_url('/keepalive')}}',!0);e.setRequestHeader('X-Requested-With','XMLHttpRequest');e.send();}, 1200000);</script>
</body>
</html>
