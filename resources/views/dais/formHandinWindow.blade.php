<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (isset($error))
    <title>错误</title>
    @else
    <title>{{$name}}的学术作业提交 | MUNPANEL</title>
    @endif

    <link rel="icon" href="{{mp_url('/images/favicon.ico')}}" type="image/x-icon" />
    <link rel="shortcut icon" href="{{mp_url('/images/favicon.ico')}}" type="image/x-icon" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{mp_url('/css/bootstrap.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{mp_url('/css/animate.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{mp_url('/css/font-awesome.min.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{mp_url('/css/font.css')}}" type="text/css" cache="false" />
    <link rel="stylesheet" href="{{mp_url('/css/plugin.css')}}" type="text/css" />
    <link rel="stylesheet" href="{{mp_url('/css/app.css')}}" rel="stylesheet" />
    @stack('css')
    <link rel="stylesheet" href="{{mp_url('/css/munpanel.css')}}" type="text/css" />

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
    <!--[if lt IE 9]>
      <script src="{{mp_url('/js/ie/respond.min.js')}}" cache="false"></script>
      <script src="{{mp_url('/js/ie/html5.js')}}" cache="false"></script>
      <script src="{{mp_url('/js/ie/excanvas.js')}}" cache="false"></script>
      <script src="{{mp_url('/js/ie/fix.js')}}" cache="false"></script>
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
  <section class="wrapper" id="content">
    <h3 class="m-t-none">{{$error or $handin->assignment->title}}</h3>
    <h4>{{$errmsg or ((isset($handin->nation_id) ? ($handin->nation->name . '代表') : '').$name.'于 '.date(' n 月 j 日 H:i ', strtotime($handin->created_at)).' 的提交')}}</h4>
    @if (!isset($error))
      @include("components.formAnswer")
    @endif
  <center><button class="btn btn-primary" onclick="self.close();">关闭页面</button></center>
  </section>
	<script src="{{mp_url('/js/jquery.min.js')}}"></script>
  <!-- Bootstrap -->
  <script src="{{mp_url('/js/bootstrap.min.js')}}"></script>
  <!-- App -->
  <script src="{{mp_url('/js/app.js')}}"></script>
  <script src="{{mp_url('/js/app.plugin.js')}}"></script>
  <script src="{{mp_url('/js/app.data.js')}}"></script>
  @stack('scripts')
</body>
</html>


