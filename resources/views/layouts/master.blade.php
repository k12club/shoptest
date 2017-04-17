<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="keywords" content="{{ config('app.name') }}, Yumefave, Laravel Shop, Laravel, Laravel eCommerce, eCommerce, PHP eCommerce" />
    <meta name="description" content="" />
    <meta name="_token" content="@yield('_token')" />
    <meta name="csrf_token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link href="/favicon.ico" type="image/x-icon" rel="icon" /><link href="/favicon.ico" type="image/x-icon" rel="shortcut icon" />
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" />
    {{ Html::style('css/site.css') }}
    @yield('header')
</head>

<body>

<!-- Google Tag Manager -->
@include('snippets.gtm')
<!-- End Google Tag Manager -->

<div class="container wrapper">

    <!-- Site Top -->
    <div class="row">
        <div class="col-md-12">
            @include('nav.top')
        </div>
    </div>

    <!-- Header -->
    <div class="row">
        <div class="col-sm-4 col-xs-8">
            <a href="/" class="logo">
                <img src="/img/logo.jpg" class="img-responsive" />
            </a>
        </div>
        <div class="col-sm-5 hidden-xs">
            <form method="GET" action="/search">
                <div id="global-search">
                    <div class="input-group col-md-12">
                        <input type="text" class="form-control input-lg" placeholder="Search" name="q" />
                        <span class="input-group-btn">
                            <button class="btn btn-info btn-lg" type="submit" title="Search">
                                <i class="fa fa-search"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-2 col-md-offset-1 col-sm-3 col-xs-4">
            <div class="mini-basket pull-right">
                <div class="row">
                    <p class="mini-basket-title col-sm-12">
                        <i class="fa fa-shopping-cart text-muted"></i> <a href="{{ route('shop.cart') }}" onclick="dataLayer.push({'event': 'header-cart-click', 'color': 'gray'});"><span class="hidden-xs">Cart</span></a>
                        @if (count(session()->get('cart.items')) > 0)
                            (<span id="cart-global">{{ collect(session()->get('cart.items'))->sum('quantity') }}</span>)
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu -->
    @include('nav.main')

    <!-- Body -->
    @yield('content')

    <div id="footer">

        <div class="footer-center">

            <div class="wrap-fcenter">
                <div class="row">
                    <div class="col-lg-3 col-md-3"><div class="box pav-custom  ">
                            <div class="box-heading"><h2>Contact Us</h2></div>
                            <div class="box-content">
                                <p>Phone: (714) 123 - 4567</p>
                                <p>Email: {{ env('OWNER_EMAIL') }}</p>
                                <p>Address: 123 Main St..<br /> Los Angeles CA 92683</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3">
                        <div class="box pav-custom  ">
                            <div class="box-heading"><h2>Information</h2></div>
                            <div class="box-content">
                                <ul class="list">
                                    <li>{{ Html::link(route('page.about'), 'About Us', ['title' => 'About Us']) }}</li>
                                    <li>{{ Html::link(route('page.contact'), 'Contact Us', ['title' => 'Contact Us']) }}</li>
                                    <li>{{ Html::link(route('page.delivery'), 'Shipping & Delivery', ['title' => 'Delivery Information']) }}</li>
                                    <li>{{ Html::link(route('page.terms'), 'Terms & Conditions', ['title' => 'Terms & Conditions']) }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3">
                        <div class="box pav-custom  ">
                            <div class="box-heading"><h2>My Account</h2></div>
                            <div class="box-content">
                                <ul class="list">
                                    <li>{{ Html::link(route('account'), 'Account Settings') }}</li>
                                    <li>{{ Html::link(route('orders'), 'Order History') }}</li>
                                    @if (Auth::check())
                                        <li>{{ Html::link('/auth/logout', 'Logout') }}</li>
                                    @else
                                        <li>{{ Html::link('/auth/login', 'Login') }}</li>
                                        <li>{{ Html::link('/auth/register', 'Register') }}</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3">
                        <div class="box pav-custom  ">
                            <div class="box-heading"><h2>Social</h2></div>
                            <div class="box-content">
                                <ul class="social">
                                    <li><a href="https://www.facebook.com" title="Check out our Facebook page" target="_blank"><i class="fa fa-facebook">&nbsp;</i></a></li>
                                    <li><a href="https://twitter.com" title="Follow us on Twitter" target="_blank"><i class="fa fa-twitter">&nbsp;</i></a></li>
                                    <li><a href="https://www.instagram.com" title="Check out our Instagram page" target="_blank"><i class="fa fa-instagram">&nbsp;</i></a></li>
                                    <li><a href="https://www.youtube.com" title="Check out our Youtube channel" target="_blank"><i class="fa fa-youtube">&nbsp;</i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div id="powered">
            <div class="container">
                <div class="powered">
                    <div class="copyright pull-left">
                        Laravel eCommerce v1.1 by <a href="https://www.yumefave.com">Yumefave</a>.<br />
                        All Rights Reserved © 2016
                    </div>
                    <div class="paypal pull-right"><img src="/img/credit-cards.png" alt=""><a href="#"></a></div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Placed at the end of the document so the pages load faster -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/1.2.1/jquery-migrate.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<!-- Bottom Block -->
@yield('bottom_block')
<!-- /Bottom Block -->
</body>
</html>