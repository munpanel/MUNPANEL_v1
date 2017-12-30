<html>
    <head>
        <meta charset="utf-8">
        <title>MUNPANEL</title>
        <link rel="shortcut icon" href="{{cdn_url('images/favicon.ico')}}" type="image/x-icon">
        <link rel="author" href="humans.txt" />
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">  
        <meta name="keywords" content="MUNPANEL,MUN,Model UN,Model United Nations,United Nations,UN,PANEL,模联,模拟联合国">
        <meta name="description" content="MUNPANEL is not merely a Model UN system - it is a platform that connects people, clubs, and conferences.">
        <meta name="copyright" content="Proudly Powered and Copyrighted by {{config('munpanel.copyright_year')}} MUNPANEL.">
        <meta name="generator" content="MUNPANEL System">
        <meta name="author" content="Adam Yi">
    <link rel="stylesheet" href="{{cdn_url('/css/bootstrap.css')}}" type="text/css" />
        <link href="{{cdn_url('css/style.css')}}" rel="stylesheet">  
        <link href="{{cdn_url('css/colors/style-color-01.css')}}" rel="stylesheet">    
        <link rel="stylesheet" href="css/simple-line-icons.css">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700" rel="stylesheet" type="text/css">
        @include('layouts.analytics')
    </head>
    <body>

    <div id="wrapper">

        <!-- Start Header -->
        <div id="header">
        
            <div class="container">
                <div class="row">
                    <div class="span12">
                    
                        <h1><a href="#">MUNPANEL</a></h1>
                        <h2 class="menulink"><a href="#">Menu</a></h2>
                        
                        <!-- Start Menu -->
                        <div id="menu">
                            <ul>  
                                <li><a href="{{secure_url('/')}}">Home</a></li>  
                                <li><a href="https://mp.weixin.qq.com/s/oqL2cA5dSa6PpwCj1RpSnQ">About</a></li> 
                                <!--li><a href="https://console.center">Console iT</a></li--> 
                            </ul> 
                        </div> 
                        <!-- End Menu -->
                        
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        
        </div>
        <!-- End Header -->

        <!-- Start Content -->

            <div class="container">
                     
                <div class="row">
                    
                    <div class="span12">
                    
                        <div class="inner">
                        
                            <div class="hero hero-narrrow" style="margin-top: 100.9px; display: block;">
                        
                                <h1>Model UN, Redefined</h1>
                                <h2>MUNPANEL is not merely a Model UN system - it is a platform that connects people, 
                                clubs, and conferences. It is not only a new way of modelling, but a new lifestyle
                                of Model United Nations.</h2>
                                
                                <p><!--a href="mailto:xuan@yiad.am" class="btn btn-primary">Contact</a--> <a href="{{route('portal')}}" class="btn btn-secondary">Portal</a></p>
                                
                                
                            
                            </div>
        
                        </div>
                        
                    </div>
                    
                </div>  
            
            </div>
        
        <!-- End content -->    
        
        <div class="clearfix"></div>     
        
    </div>

    <div id="footer">

        <!--p> 
            <a href="#"><span class="glyph-item mega icon-social-youtube" aria-hidden="true"></span></a>
            <a href="#"><span class="glyph-item mega icon-social-twitter" aria-hidden="true"></span></a>
            <a href="#"><span class="glyph-item mega icon-social-tumblr" aria-hidden="true"></span></a>
            <a href="#"><span class="glyph-item mega icon-social-facebook" aria-hidden="true"></span></a>
            <a href="#"><span class="glyph-item mega icon-social-dropbox" aria-hidden="true"></span></a>
            <a href="#"><span class="glyph-item mega icon-social-dribbble" aria-hidden="true"></span></a>
        </p-->
           
        Copyright © {{config('munpanel.copyright_year')}} MUNPANEL.<br/>Developed by Adam Yi
        @if(null !== config('munpanel.icp_license'))
        <br/><a href="http://www.miibeian.gov.cn/" title="{{config('munpanel.icp_license')}}" rel="nofollow">{{config('munpanel.icp_license')}}</a>
        @endif
        <br/><a href="{{mp_url('humans.txt')}}"><img src="{{cdn_url('images/humanstxt-isolated-blank.gif')}}" alt="Humans.txt" width="88" height="31"></a>

        <div class="clearfix"></div>

    </div>

    <!--[if lte IE 7]><script src="{{cdn_url('js/icons-lte-ie7.js')}}"></script><![endif]-->
    <script src="{{cdn_url('js/jquery.min.js')}}"></script>
    <script src="{{cdn_url('js/scripts.js')}}"></script>
  <script src="{{cdn_url('/js/bootstrap.min.js')}}"></script>
  <script>
                var $modal = $('<div class="modal" id="ajaxModal"><div class="modal-body"><div class="modal-dialog"><div class="modal-content"><div class="modal-body"><center><div class="row"><div class="col-sm-12 b-r"><div class="alert alert-warning"><b>The 2017 yearly report is released!</b></div><a id="confirmButton" href="/munpanel2017.pdf" class="btn btn-sm btn-success text-uc m-t-n-xs m-r-xs">Check it out~</a></div></div></center></div></div></div></div></div>');
                $('body').append($modal);
                $modal.modal();
  </script>
                             


    </body>
</html>
