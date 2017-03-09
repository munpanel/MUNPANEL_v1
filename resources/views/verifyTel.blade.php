<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Account Verification | MUNPANEL</title>
  <link rel="icon" href="{{secure_url('/images/favicon.ico')}}" type="image/x-icon" />
  <link rel="shortcut icon" href="{{secure_url('/images/favicon.ico')}}" type="image/x-icon" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="css/animate.css" type="text/css" />
  <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="css/font.css" type="text/css" cache="false" />
  <link rel="stylesheet" href="css/plugin.css" type="text/css" />
  <link rel="stylesheet" href="css/app.css" type="text/css" />
  <link rel="stylesheet" href="css/intlTelInput.css" type="text/css" />
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
  <section id="content" class="m-t-lg wrapper-md animated fadeInUp">
    <a class="nav-brand" href="#">MUNPANEL</a>
    <div class="row m-n">
      <div class="col-md-4 col-md-offset-4 m-t-lg">
        <section class="panel">
          <header class="panel-heading text-center">
            请验证您的电话号码
          </header>
            <div class="panel-body"><div class="alert alert-info">感谢您使用MUNPANEL！请在下方填写您的手机号，我们将给您发送一条短信或拨打一个电话并告知验证码以激活您的账户。未激活的账户将不能使用任何 MUNPANEL 服务，会议组织团队亦不可查看您的报名信息。<br>当前账户剩余 <b>{{Auth::user()->telVerifications}}</b> 次验证机会。如您无验证机会，请联系客服或重新注册新账户，感谢。</div></div>
            @if (Auth::user()->telVerifications > 0)
          <form action="{{ secure_url('/login') }}" method="post" class="panel-body" data-validate="parsley">
            {{ csrf_field() }}
            <div class="form-group">
              <label class="control-label">手机号</label>
              <!--input type="text" id="tel" name="tel" placeholder="eg. 18612345678" value="{{ Auth::user()->tel }}" autofocus-->
              <input type="tel" id="tel" autofocus>
              &nbsp&nbsp<button id="getVerificationSMS" class="btn btn-warning">发短信</button>&nbsp&nbsp<button id="getVerificationCALL" class="btn btn-warning">打电话</button>
            </div>
            </form>
            @endif
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
  <script src="js/intlTelInput.js"></script>
  <script>
  $("#tel").intlTelInput({
      initialCountry:"cn",
      separateDialCode: true,
      preferredCountries: ['cn','us','gb'],
      utilsScript: "js/intl-utils.js"
  });
  $('#getVerificationSMS').click(function(e) {
     $('#ajaxModal').remove();
     e.preventDefault();
     var $remote = 'verifyTel.modal/sms/' + $('#tel').intlTelInput("getNumber")
       , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
     $('body').append($modal);
     $modal.modal();
     $modal.load($remote);
  });
  $('#getVerificationCALL').click(function(e) {
     $('#ajaxModal').remove();
     e.preventDefault();
     var $remote = 'verifyTel.modal/call/' + $('#tel').intlTelInput("getNumber")
       , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
     $('body').append($modal);
     $modal.modal();
     $modal.load($remote);
  });
  </script>
</body>
</html>
