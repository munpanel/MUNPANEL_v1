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
                In case you contact customer support, please provide this error ID: <b>{{$sentryID}}</b><br/><br/>
                <div class="title">Something went wrong.</div>
                @unless(empty($sentryID))
                    <!-- Sentry JS SDK 2.1.+ required -->
                    <script src="https://cdn.ravenjs.com/3.3.0/raven.min.js"></script>

                    <script>
                    @if (Auth::check())
                    Raven.setUserContext({
                        id: '{{Auth::user()->id}}',
                        name: '{{Auth::user()->name}}',
                        email: '{{Auth::user()->email}}'
                    });
                    @endif
                    Raven.showReportDialog({
                        eventId: '{{ $sentryID }}',
                        @if (Auth::check())
                        user: {
                            name: '{{Auth::user()->name}}',
                            email: '{{Auth::user()->email}}'
                        },
                        @endif

                        // use the public DSN (dont include your secret!)
                        dsn: 'https://3026b841fd7e4a5c9a4422c8aab5f270@sentry.io/162702'
                    });
                    </script>
                @endunless
                <br/><br/>We are already notified and are already working on it.<br/>If you have any more concerns, feel free to reach us at <a href="support@munpanel.com">support@munpanel.com</a><br/>
                Powered by MUNPANEL, a Product of Console iT. Developed by Adam Yi.
            </div>
        </div>
    </body>
</html>
