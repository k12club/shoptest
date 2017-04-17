@extends('layouts.master')

@section('title', 'Checkout - ' . config('app.name'))

@section('header')
    <link media="all" type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.2/css/bootstrap3/bootstrap-switch.min.css" />

    <style>
        .address-row {
            padding: 10px 0;
        }

        .address-row:not(:last-child) {
            border-bottom: 1px solid #eeeeee;
        }
    </style>
@stop

@section('_token', csrf_token())

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h1>
                Checkout

                @if (config('custom.checkout.pay_later'))
                    <span class="pull-right">
                        <input name="pay_now" id="pay-now" type="checkbox" data-size="small" data-on-text="Pay Now" data-off-text="Pay Later"{{ session('cart.pay_later') ? '' : ' checked' }}/>
                    </span>
                @endif
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            @include('snippets.errors')
            @include('snippets.flash')

            @include('shop.checkout.login')

            {{ Form::open(['route' => ['shop.checkout.submit'], 'class' => 'material', 'id' => 'checkout-form']) }}

            @include('shop.checkout.contact')

            @include('shop.checkout.shipping')

            @include('shop.checkout.delivery')

            @if (config('custom.checkout.pay_later'))
                @include('shop.checkout.pay_later')
            @endif

            @include('shop.checkout.billing')

            @include('shop.checkout.payment')
        </div>

        <div class="col-md-4">
            <div class="well">
                <strong>Order Summary</strong>

                <hr />

                <p class="row">
                    <span class="col-md-6">
                        <strong id="cart-items">{{ $fees['items'] }}</strong> item(s) &middot;
                        <a href="{{ route('shop.cart') }}"><small class="text-muted">Edit</small></a>
                    </span>
                    <span class="col-md-6 text-right">
                        $<strong id="cart-subtotal">{{ number_format($fees['subtotal'], 2) }}</strong>
                    </span>
                </p>

                <p class="row">
                    <span class="col-md-6">
                        Estimated Tax
                    </span>
                    <span class="col-md-6 text-right">
                        $<strong id="cart-tax">{{ number_format($fees['tax'], 2) }}</strong>
                    </span>
                </p>

                <p class="row">
                    <span class="col-md-6">
                        Shipping & Service
                    </span>
                    <span class="col-md-6 text-right">
                        $<strong id="cart-shipping">{{ number_format($fees['shipping'], 2) }}</strong>
                    </span>
                </p>

                <hr class="separator" />

                <p class="row">
                    <span class="col-md-6">
                        Total
                    </span>
                    <span class="col-md-6 text-right">
                        $<strong id="cart-total">{{ number_format($fees['total'], 2) }}</strong>
                    </span>
                </p>

                <br />

                <button id="place-order-btn" class="btn btn-block btn-primary" type="submit">
                    <strong>Place Order</strong>
                    &nbsp;<i class="fa fa-lock"></i>
                </button>

                <p class="help-block">
                    <br />
                    <img src="/img/comodo-badge-icon.png" class="pull-right" />
                    <small>
                        Order is securely processed using 256-bit SSL encryption and Stripe PCI-compliant technology.
                    </small>
                </p>

                {{ Form::close() }}
            </div>

            @if (env('SHOP_DEMO'))
                <div class="well">
                    <p class="alert alert-info"><i class="fa fa-exclamation-circle"></i> Please use the following cards for testing.</p>

                    <table class="table table-striped">
                        <tr>
                            <th>Number</th>
                            <th>Card Type</th>
                        </tr>
                        <tr>
                            <td>4242 4242 4242 4242</td>
                            <td>Visa</td>
                        </tr>
                        <tr>
                            <td>4012 8888 8888 1881</td>
                            <td>Visa</td>
                        </tr>
                        <tr>
                            <td>4000 0566 5566 5556</td>
                            <td>Visa (debit)</td>
                        </tr>
                        <tr>
                            <td>5555 5555 5555 4444</td>
                            <td>Mastercard</td>
                        </tr>
                        <tr>
                            <td>5200 8282 8282 8210</td>
                            <td>Mastercard (debit)</td>
                        </tr>
                        <tr>
                            <td>5105 1051 0510 5100</td>
                            <td>Mastercard (prepaid)</td>
                        </tr>
                        <tr>
                            <td>3782 822463 10005</td>
                            <td>American Express</td>
                        </tr>
                        <tr>
                            <td>3714 496353 98431</td>
                            <td>American Express</td>
                        </tr>
                        <tr>
                            <td>6011 1111 1111 1117</td>
                            <td>Discover</td>
                        </tr>
                        <tr>
                            <td>6011 0009 9013 9424</td>
                            <td>Discover</td>
                        </tr>
                        <tr>
                            <td>3056 9309 0259 04</td>
                            <td>Diners Club</td>
                        </tr>
                        <tr>
                            <td>3852 0000 0232 37</td>
                            <td>Diners Club</td>
                        </tr>
                        <tr>
                            <td>3530 1113 3330 0000</td>
                            <td>JCB</td>
                        </tr>
                        <tr>
                            <td>3566 0020 2036 0505</td>
                            <td>JCB</td>
                        </tr>
                    </table>
                </div>
            @endif
        </div>
    </div>

    @if (auth()->check())
        @include('shop.checkout.delivery_modal')

        @include('shop.checkout.billing_modal')

        @include('shop.checkout.payment_modal')
    @endif

@stop

@section('bottom_block')
    @parent
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-switch/3.3.2/js/bootstrap-switch.min.js"></script>

    <script type="text/javascript">
        $( document ).ready(function() {
            var currentShipping = function() {
                if ($('input[name="cash_on_delivery"]').prop('checked')) {
                    return 'cash_on_delivery';
                } else {
                    var shippingOption = $('input[type="radio"][name^="shipping"]:checked'),
                        shippingOptionName = shippingOption.attr('name'),
                        shippingOptionValue = shippingOption.val(),
                        shipping = {
                            "carrier": shippingOptionName.substr(9, (shippingOptionName.length - 10)),
                            "plan": shippingOptionValue
                        };

                    return shipping;
                }
            };

            var switchPayNowElements = function(state) {
                $('#pay-later-block, #cash-on-delivery').toggleClass('hide', state);

                $('#billing-block, #payment-block').toggleClass('hide', !state);

                $('input[name="cash_on_delivery"]').attr('disabled', state);

                $('#billing-fieldset, #payment-fieldset, input[name="billing[address_id]"], input[name="card_id"]').attr('disabled', !state);
            };

            @if (config('custom.checkout.pay_later'))
                // Initial view
                switchPayNowElements($('input[name="pay_now"]').prop('checked'));

                $('#pay-now').bootstrapSwitch({
                    onSwitchChange: function(event, state) {
                        event.preventDefault();

                        switchPayNowElements(state);

                        $.ajax({
                            headers: {
                                'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                            },
                            url: "/shop/cart/update",
                            type: "POST",
                            data: {
                                "pay_later": !state,
                                "shipping": currentShipping()
                            },
                            cache: false,
                            success: function(data) {
                                if (typeof data['status'] != 'undefined' && data['status'] == 'success') {
                                    if (typeof data['fees'] != 'undefined') {
                                        $('#cart-shipping').text(data['fees']['shipping'].toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
                                        $('#cart-total').text(data['fees']['total'].toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
                                    }
                                }
                            },
                            error: function() {
                            }
                        });
                    }
                });
            @endif

            @if (config('custom.checkout.pay_later') && config('custom.checkout.cash_on_delivery'))
                $('input[name="cash_on_delivery"]').change(function() {
                    $('fieldset[class^="shipping"]').toggleClass('hide', $(this).prop('checked')).attr('disabled', $(this).prop('checked'));

                    $.ajax({
                        headers: {
                            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                        },
                        url: "/shop/cart/update",
                        type: "POST",
                        data: {
                            shipping: currentShipping()
                        },
                        cache: false,
                        success: function(data) {
                            if (typeof data['status'] != 'undefined' && data['status'] == 'success') {
                                if (typeof data['fees'] != 'undefined') {
                                    $('#cart-shipping').text(data['fees']['shipping'].toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
                                    $('#cart-total').text(data['fees']['total'].toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,"));
                                }
                            }
                        },
                        error: function() {
                        }
                    });
                });
            @endif

            $('#checkout-form').submit(function(e) {
                var $form = $(this);

                $('#place-order-btn').text('Placing order...');

                // If Pay Now
                if ($('input[name="pay_now"]').prop('checked')) {
                    @if (!auth()->check() || auth()->user()->cards()->count() == 0)
                        var stripeMainesponseHandler = function(status, response) {
                            var $form = $('#checkout-form');

                            if (response.error) {
                                // Show the errors on the form
                                $form.find('.payment-errors').text(response.error.message).removeClass('hide');
                                $form.find('button').prop('disabled', false);

                                $('#place-order-btn').text('Place Order');
                            } else {
                                // token contains id, last4, and card type
                                var token = response.id;
                                // Insert the token into the form so it gets submitted to the server
                                $form.append($('<input type="hidden" name="stripe_token" />').val(token));

                                // Disable the payment fields for PCI-Compliant
                                $('#payment-fieldset').attr('disabled', true);

                                // and re-submit
                                $form.get(0).submit();
                            }
                        };
                    @endif

                    if ($('input[name="card_id"]').length > 0 && $('input[name="card_id"]').val() != '') {
                        $form.get(0).submit();
                    } else {
                        // Disable the submit button to prevent repeated clicks
                        $form.find('button').prop('disabled', true);

                        Stripe.card.createToken($form, stripeMainesponseHandler);

                        // Prevent the form from submitting with the default action
                        return false;
                    }
                }
                // Else if Pay Later
                else {
                    $('<input>').attr({"type": "hidden",  "name": "pay_later"}).appendTo($form);

                    return true;
                }
            });
        });
    </script>
@endsection