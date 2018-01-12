<html>
    <head>
        <meta charset="utf-8">
        <title>MUNPANEL</title>
        <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/manifest.json">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
        <link rel="shortcut icon" href="/favicon.ico">
        <meta name="theme-color" content="#5dcff3">
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
                    
                        <h1><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50.33 16.459" width="120px"><path fill="#ffffff" d="M18.35 13.54a1.8 1.8 0 0 1-.713-.764 2.583 2.583 0 0 1-.253-1.183V8.426h.984v3.225a1.334 1.334 0 0 0 .276.878 1.081 1.081 0 0 0 1.565 0 1.333 1.333 0 0 0 .275-.878V8.426h.985v3.166a2.542 2.542 0 0 1-.25 1.154 1.864 1.864 0 0 1-.712.779 2.055 2.055 0 0 1-1.081.279 2.112 2.112 0 0 1-1.076-.264zM22.579 8.426h1.153l2.116 3.526h.059l-.059-1.014V8.426h.984v5.26h-1.043l-2.233-3.725H23.5l.059 1.014v2.711h-.977zM28.031 8.426h1.851a1.986 1.986 0 0 1 .911.209 1.587 1.587 0 0 1 .647.591 1.607 1.607 0 0 1 .235.863 1.626 1.626 0 0 1-.235.867 1.58 1.58 0 0 1-.647.591 1.974 1.974 0 0 1-.911.21h-.86v1.932h-.991zm1.88 2.387a.769.769 0 0 0 .577-.213.7.7 0 0 0 .209-.514.694.694 0 0 0-.209-.511.783.783 0 0 0-.577-.209h-.889v1.447zM33.475 8.426h1.138l1.977 5.26H35.5l-.441-1.256h-2.018l-.441 1.257h-1.095zm1.256 3.093l-.463-1.323-.191-.632h-.059l-.191.632-.47 1.323zM37.3 8.426h1.153l2.117 3.526h.058l-.058-1.014V8.426h.984v5.26h-1.041L38.28 9.962h-.059l.059 1.014v2.711h-.98zM42.755 8.426h3.335v.94h-2.344v1.22h2.109v.94h-2.109v1.22h2.344v.94h-3.335zM47.112 8.426h.988v4.32h2.23v.94h-3.218z"></path><path fill="#ffffff" d="M13.73 2.18a.278.278 0 0 0-.3-.05l.392.925A7.642 7.642 0 0 1 6.588 15.7a.627.627 0 1 0-.137.541A8.2 8.2 0 0 0 13.73 2.18z"></path><path fill="#ffffff" d="M13.264 2.385v9.841a.984.984 0 0 1-1.964 0V6.62a.279.279 0 0 0-.435-.231l-2.11 1.437a1.025 1.025 0 0 1-1.114 0L5.536 6.389a.279.279 0 0 0-.436.231v5.956a.628.628 0 1 0 .556 0v-5.43l1.676 1.139a1.563 1.563 0 0 0 1.733 0l1.674-1.139v5.08a1.541 1.541 0 0 0 3.082 0V3.057l-.392-.925a.277.277 0 0 0-.165.253z"></path><path fill="#ffffff" d="M.557 8.226A7.643 7.643 0 0 1 9.808.755a.629.629 0 1 0 .138-.54 8.2 8.2 0 0 0-7.28 14.06.278.278 0 0 0 .188.073.291.291 0 0 0 .112-.023l-.393-.927A7.659 7.659 0 0 1 .557 8.226z"></path><path fill="#ffffff" d="M11.568 3.191a.627.627 0 0 0-1.14.439L8.2 5.147 4.985 2.961a1.553 1.553 0 0 0-2.41 1.273v9.167l.393.927a.279.279 0 0 0 .165-.254V4.231a.993.993 0 0 1 1.54-.81l3.37 2.293a.279.279 0 0 0 .313 0l2.386-1.623a.628.628 0 0 0 .827-.9z"></path></svg></h1>
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
