@extends('layouts.master')

@section('title', config('app.name'))

@section('header')
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css') }}
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css') }}
@stop

@section('content')

    <div class="row" style="margin-top: 10px;">
        <!-- Sidebar Starts -->
        <div class="col-md-3">
            <!-- Categories Links Starts -->
            <h3 class="side-heading hidden-xs">Menu</h3>
            <div class="list-group categories hidden-xs">
                <a href="{{ route('shop') }}" class="list-group-item">
                    <i class="fa fa-chevron-right"></i> Shop
                </a>
                @foreach ($categories as $category)
                    <a href="{{ route('shop.category', $category['uri']) }}" class="list-group-item">
                        <i class="fa fa-chevron-right"></i> {{ $category['name'] }}
                    </a>
                @endforeach
            </div>
            <!-- Categories Links Ends -->

            @include('home.special_products')
        </div>
        <!-- Sidebar Ends -->
        <!-- Primary Content Starts -->
        <div class="col-md-9">
            @include('home.slider')
            <h3 style="color: #ff884c;">Laravel powered E-commerce online store</h3>

            <ul>
                <li>Full-featured shop:</li>
                <ul>
                    <li>Categories</li>
                    <li>Products</li>
                    <li>Search</li>
                    <li>1 page checkout</li>
                    <li>Flat-rate shipping</li>
                    <li>Address book</li>
                    <li>Shipping tracking</li>
                    <li>Full Admin Panel</li>
                    <li>Static pages</li>
                    <li>...</li>
                </ul>
                <li>Payments handled by Stripe</li>
                <li>Photos uploaded and stored in Amazon S3</li>
                <li>Integrated w/ Rollbar for Application Logs</li>
                <li>Beautiful & responsive email templates w/ Sparkpost</li>
                <li>Responsive design (tested on iPhone, iPad, Android devices, tablets, desktops)</li>
                <li>Clean & optimal codes w/ best practices and full comments</li>
            </ul>

            <br />
            @include('home.latest_products')
        </div>
        <!-- Primary Content Ends -->
    </div>

@stop

@section('bottom_block')
    {{ Html::script('https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js')}}

    <script>
        (function($) {

            "use strict";

            // TOOLTIP
            $(".header-links .fa, .tool-tip").tooltip({
                placement: "bottom"
            });
            $(".btn-wishlist, .btn-compare, .display .fa").tooltip('hide');

            $('#latest-products').slick({
                dots: false,
                infinite: true,
                slidesToShow: 3,
                slidesToScroll: 3,
                autoplay: true,
                autoplaySpeed: 5000,
                speed: 1000,
                prevArrow: $('.latest-products-prev'),
                nextArrow: $('.latest-products-next'),
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ]
            });

            // TABS
            $('.nav-tabs a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
            });

        })(jQuery);
    </script>
@stop