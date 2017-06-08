<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>MUNPANEL</title>
  <style type="text/css">
  div{position: relative;}
  div img{width: 100px;height: 100px;}
  .mp-block{position: absolute;text-align: center;width: 100px;height: 100px;animation: blockadnimation 5s;
-moz-animation: blockadnimation 3s;  /* Firefox */
-webkit-animation: blockadnimation 3s;  /* Safari & Chrome */
-o-animation: blockadnimation 3s;background:#fff;left: 50%;margin: 0 0 0 -50px;top: 0;
animation-fill-mode: forwards;}
@keyframes blockadnimation
{
0%   {}
100% {transform: translate(100px, 0)}
}

@-moz-keyframes blockadnimation /* Firefox */
{
0%   {}
100% {transform: translate(100px, 0)}
}

@-webkit-keyframes blockadnimation /* Safari & Chrome */
{
0%   {}
100% {transform: translate(100px, 0)}
}

@-o-keyframes blockadnimation /* Opera */
{
0%   {}
100% {transform: translate(100px, 0)}
}
  </style>
</head>
<body>
  <div align="center">
    <img src="{{cdn_url('/images/success.png')}}"/>
    <div class="mp-block" align="center"></div>
  </div>
  <div style="text-align:center;">
  <h2>Success!</h2>
  <h3>您已支付订单 {{$orderID}}</h3>
  <h4>{{$amount}} 元</h4>
  </div>
 <div id="footer" style="text-align: center;color: #999;padding: 3.5em 0 3em;">
 Copyright © {{config('munpanel.copyright_year')}} MUNPANEL.<br>A Product of Console iT, Developed by Adam Yi
 <div class="clearfix"></div>
 </div>
</body>
</html>
