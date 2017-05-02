<!DOCTYPE html>
<html>
    <head>
        <title>Something went wrong.</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                //color: #B0BEC5;
                color: #99ABB4;
                display: table;
                font-weight: 100;
                font-family: 'Lato', sans-serif;
            }

            a {
                color: inherit;
            } 

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }

            .powered-by {
                display: none;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                @unless(empty($sentryID))
                In case you contact customer support, please provide this error ID: <b>{{$sentryID}}</b><br/><br/>
                @endunless
                <div class="title">Something went wrong.</div>
                @unless(empty($sentryID))
                    <!-- Sentry JS SDK 2.1.+ required -->
                    <script src="https://cdn.ravenjs.com/3.3.0/raven.min.js"></script>

                    <script>
                    Raven.showReportDialog({
                        eventId: '{{ $sentryID }}',
                        @if (Auth::check())
                        user: {
                            name: '{{Auth::user()->name}}',
                            email: '{{Auth::user()->email}}'
                        },
                        @endif

                        // use the public DSN (dont include your secret!)
                        dsn: '{{config('sentry.dsn_public')}}'
                    });
                    </script>
                @endunless
                <br/><br/>{!!isset($sentryID)?"We are already notified and are already working on it.<br/>":""!!}If you have any more concerns, feel free to reach us at <a href="mailto:support@munpanel.com">support@munpanel.com</a><br/>
                Powered by MUNPANEL, a Product of Console iT. Developed by Adam Yi.
              @if(null !== config('munpanel.icp_license'))
              <br/><br/><a href="http://www.miibeian.gov.cn/" title="{{config('munpanel.icp_license')}}" rel="nofollow">{{config('munpanel.icp_license')}}</a>
              @endif
            </div>
        </div>
    </body>
</html>
