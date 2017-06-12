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
  <link rel="stylesheet" href="{{cdn_url('css/intlTelInput.css')}}" type="text/css" />
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
            请验证您的电话号码
          </header>
            <div class="panel-body"><div class="alert alert-info">感谢您使用MUNPANEL！请在下方填写您的手机号，我们将给您发送一条短信或拨打一个电话并告知验证码以激活您的账户。未激活的账户将不能使用任何 MUNPANEL 服务，会议组织团队亦不可查看您的报名信息。{{--<br>当前账户剩余 <b>{{Auth::user()->telVerifications}}</b> 次验证机会。如您无验证机会，请联系客服或重新注册新账户，感谢。--}}</div></div>
            @if (Auth::user()->telVerifications > 0)
          <form action="{{ mp_url('/login') }}" method="post" class="panel-body" data-validate="parsley">
            {{ csrf_field() }}
            <div class="form-group"><center>
              <label class="control-label">手机号</label>
              <!--input type="text" id="tel" name="tel" placeholder="eg. 18612345678" value="{{ Auth::user()->tel }}" autofocus-->
              <input type="tel" id="tel" autofocus>
              <br><br><br><button id="getVerificationSMS" class="btn btn-warning">发短信</button>&nbsp&nbsp<button id="getVerificationCALL" class="btn btn-warning">打电话</button>
            </center></div>
            </form>
            <a href="{{ route('logout') }}" class="btn btn-white btn-block" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">注销</a>
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
  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;"> {{ csrf_field() }} </form>
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
  <script src="{{cdn_url('js/intlTelInput.js')}}"></script>
  <script src="{{cdn_url('js/js.cookie.js')}}"></script>
  <script>
  var btnSMS = $('#getVerificationSMS');
  var btnCALL = $('#getVerificationCALL');
  var count = 0;
  if(Cookies.get("captcha")){
      count = Cookies.get("captcha");
      btnSMS.text(count+'秒后可重新获取').attr('disabled',true).css('cursor','not-allowed');
      btnCALL.text(count+'秒后可重新获取').attr('disabled',true).css('cursor','not-allowed');
      var resend = setInterval(function(){
          count--;
          if (count > 0){
              btnSMS.text(count+'秒后可重新获取').attr('disabled',true).css('cursor','not-allowed');
              btnCALL.text(count+'秒后可重新获取').attr('disabled',true).css('cursor','not-allowed');
              Cookies.set("captcha", count, {path: '/', expires: (1/86400)*count});
          }else {
              clearInterval(resend);
              btnSMS.text("发短信").removeClass('disabled').removeAttr('disabled style');
              btnCALL.text("打电话").removeClass('disabled').removeAttr('disabled style');
          }
      }, 1000);
  }
  $("#tel").intlTelInput({
      initialCountry:"cn",
      separateDialCode: true,
      preferredCountries: ['cn','us','gb'],
      utilsScript: "js/intl-utils.js"
  });
  $('#getVerificationSMS').click(function(e) {
     count = 60;
     var resend = setInterval(function(){
         count--;
         if (count > 0){
             btnSMS.text(count+'秒后可重新获取').attr('disabled',true).css('cursor','not-allowed');
             btnCALL.text(count+'秒后可重新获取').attr('disabled',true).css('cursor','not-allowed');
             Cookies.set("captcha", count, {path: '/', expires: (1/86400)*count});
         }else {
             clearInterval(resend);
             btnSMS.text("发短信").removeClass('disabled').removeAttr('disabled style');
             btnCALL.text("打电话").removeClass('disabled').removeAttr('disabled style');
         }
      }, 1000);
     btnSMS.attr('disabled',true).css('cursor','not-allowed');
     btnCALL.attr('disabled',true).css('cursor','not-allowed');
     $('#ajaxModal').remove();
     e.preventDefault();
     var $remote = 'verifyTel.modal/sms/' + $('#tel').intlTelInput("getNumber")
       , $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"></div></div>');
     $('body').append($modal);
     $modal.modal();
     $modal.load($remote);
  });
  $('#getVerificationCALL').click(function(e) {
     count = 60;
     var resend = setInterval(function(){
         count--;
         if (count > 0){
             btnSMS.text(count+'秒后可重新获取').attr('disabled',true).css('cursor','not-allowed');
             btnCALL.text(count+'秒后可重新获取').attr('disabled',true).css('cursor','not-allowed');
             Cookies.set("captcha", count, {path: '/', expires: (1/86400)*count});
         }else {
             clearInterval(resend);
             btnSMS.text("发短信").removeClass('disabled').removeAttr('disabled style');
             btnCALL.text("打电话").removeClass('disabled').removeAttr('disabled style');
         }
      }, 1000);
     btnSMS.attr('disabled',true).css('cursor','not-allowed');
     btnCALL.attr('disabled',true).css('cursor','not-allowed');
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
