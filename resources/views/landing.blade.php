<html>
    <head>
        <meta charset="utf-8">
        <title>MUNPANEL</title>
        <link rel="shortcut icon" href="{{cdn_url('images/favicon.ico')}}" type="image/x-icon">
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">  
        <link href="{{cdn_url('css/style.css')}}" rel="stylesheet">  
        <link href="{{cdn_url('css/colors/style-color-01.css')}}" rel="stylesheet">    
        <link rel="stylesheet" href="css/simple-line-icons.css">
        <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,300,600,700" rel="stylesheet" type="text/css">
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
                                <li><a href="{{secure_url('/about')}}">About</a></li> 
                                <li><a href="https://console.center">Console iT</a></li> 
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
                                
                                <p><a href="mailto:xuan@yiad.am" class="btn btn-primary">Contact</a> <a href="#" class="btn btn-secondary">Portal under Development...</a></p>
                                
                                
                            
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
           
        Copyright © {{config('munpanel.copyright_year')}} MUNPANEL.<br/>A Product of Console iT, Developed by Adam Yi
        @if(null !== config('munpanel.icp_license'))
        <br/><a href="http://www.miibeian.gov.cn/" title="{{config('munpanel.icp_license')}}" rel="nofollow">{{config('munpanel.icp_license')}}</a>
        @endif

        <div class="clearfix"></div>

    </div>

    <!--[if lte IE 7]><script src="{{cdn_url('js/icons-lte-ie7.js')}}"></script><![endif]-->
    <script src="{{cdn_url('js/jquery.min.js')}}"></script>
    <script src="{{cdn_url('js/scripts.js')}}"></script>
                             


    </body>
</html>
