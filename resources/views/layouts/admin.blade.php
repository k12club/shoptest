<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="_token" content="@yield('_token', '')" />
    <title>@yield('title')</title>
    <link href="/favicon.ico" type="image/x-icon" rel="icon" />
    <link href="/favicon.ico" type="image/x-icon" rel="shortcut icon" />
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.2/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" />
    {{ Html::style('css/site.css') }}
</head>

<body>

<div class="container-fluid wrapper">
    <!-- Site Top -->
    <div class="row">
        <div class="col-md-12">
            @include('nav.top')
        </div>
    </div>

    <!-- Header -->
    <div class="row">
        <div class="col-sm-12">
            <p class="logo">
                <img src="/img/logo.jpg" />
            </p>
        </div>
    </div>

    <!-- Menu -->
    @include('nav.admin')

    <!-- Body -->
    @yield('content')

    <div id="footer">
        <div id="powered">
            <div class="container">
                <div class="powered">
                    <div class="copyright text-center">
                        Powered By <a href="http://www.yumefave.com">Yumefave</a>.<br />
                        All Rights Reserved &copy; 2016
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Placed at the end of the document so the pages load faster -->
<script type="text/javascript" src="//code.jquery.com/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<!-- Bottom Block -->
@yield('bottom_block')
<!-- /Bottom Block -->
</body>
</html>