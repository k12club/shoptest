<div class="row" id="payment-block">
    <div class="col-md-3">
        <h5 style="line-height: normal; margin-top: 0;">Payment</h5>
    </div>
    <div class="col-md-9">
        @if (auth()->check())
            @if (auth()->user()->cards()->count() > 0)
                <div class="row">
                    <div class="col-md-12">
                        <input type="hidden" name="card_id" value="{!! auth()->user()->primaryCard()->first()['id'] !!}" />
                        <span class="pull-left" id="payment-current-card">
                            {!! CustomHelper::formatCard(auth()->user()->primaryCard()->first()) !!}
                        </span>
                        <span class="pull-right">
                            <a href="#" id="payment-change-card" class="btn" data-toggle="modal" data-target="#payment-modal">Change credit card</a>
                        </span>
                    </div>
                </div>
            @else
                @include('shop.checkout.payment_fieldset')

                <label class="pull-right" style="color: #666666; font-size: 14px; font-weight: normal;">
                    {!! Form::checkbox(
                        'save_payment_method',
                        true, true) !!}

                    Save payment method
                </label>
            @endif
        @else
            @include('shop.checkout.payment_fieldset')

            <label class="pull-right" style="color: #666666; font-size: 14px; font-weight: normal;">
                {!! Form::checkbox(
                    'save_payment_method',
                    true,
                    old('save_payment_method') ) !!}

                Save payment method
            </label>
        @endif

        <br /><br />
    </div>
</div>

@section('bottom_block')
    @parent
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <script>
        // This identifies your website in the createToken call below
        Stripe.setPublishableKey('{!! env('STRIPE_KEY') !!}');

        $( document ).ready(function() {
            $('#payment-new-card').click(function () {
                $(this).toggleClass('hide');

                $('#payment-current-cards, #add-payment-card-cancel, #add-payment-card-form').toggleClass('hide');
            });

            $('#add-payment-card-cancel').click(function () {
                $(this).toggleClass('hide');

                $('#payment-current-cards, #payment-new-card, #add-payment-card-form').toggleClass('hide');
            });

            var cardBrand = function(inputBrand) {
                var brand = '';

                switch(inputBrand) {
                    case 'Visa':
                        brand = 'visa';
                        break;
                    case 'MasterCard':
                        brand = 'mastercard';
                        break;
                    case 'American Express':
                        brand = 'amex';
                        break;
                    case 'Discover':
                        brand = 'discover';
                        break;
                    case 'Diners Club':
                        brand = 'diners-club';
                        break;
                    case 'JCB':
                        brand = 'jcb';
                        break;
                }

                return brand;
            };

            var stripeResponseHandler = function(status, response) {
                var $form = $('#add-payment-card-form');

                if (response.error) {
                    // Show the errors on the form
                    $form.find('.payment-errors').text(response.error.message).removeClass('hide');
                    $form.find('button').prop('disabled', false);
                } else {
                    // token contains id, last4, and card type
                    var token = response.id;

                    $.ajax({
                        headers: {
                            'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                        },
                        url: "/shop/payments",
                        type: "POST",
                        data: {
                            "stripe_token": token
                        },
                        cache: false,
                        success: function(data) {
                            if (typeof data['status'] != 'undefined' && data['status'] === true) {
                                if (typeof data['card_id'] != 'undefined') {
                                    var li = $("<li/>");

                                    li.attr({
                                        "data-name": data['name'],
                                        "data-last4": data['last4'],
                                        "data-brand": data['brand']
                                    });

                                    li.addClass('row address-row').html('<div class="col-md-8">' +
                                            '<a href="javascript:void(0);" class="address" id="payment-select-card-'+data['card_id']+'" title="Use this card for this payment">' +
                                            '<i class="fa fa-cc-'+cardBrand(data['brand'])+'"></i>' + ' ***' + data['last4'] +  ' - ' + data['name'] +
                                            '</a>' +
                                            '</div>' +
                                            '<div class="col-md-4 text-right">' +
                                            '   <a href="#">Edit</a> |' +
                                            '   <a href="#">Delete</a><br /><br />' +
                                            '   <a href="#">Make primary</a>' +
                                            '</div>');

                                    $('#payment-current-cards').append(li);

                                    $('#add-payment-card-cancel, #payment-current-cards, #payment-new-card, #add-payment-card-form').toggleClass('hide');
                                }
                            }
                        },
                        error: function() {
                        }
                    });
                }
            };

            $('#add-payment-card-form').submit(function() {
                var $form = $(this);

                // Disable the submit button to prevent repeated clicks
                $form.find('button').prop('disabled', true);

                Stripe.card.createToken($form, stripeResponseHandler);

                return false;
            });

            // Must use this delegated event since there can be new anchor created dynamically
            $("#payment-current-cards").on('click', 'a[id^="payment-select-card-"]', function(event){
                var id = $(this).attr('id'),
                    cardId = id.substr(20),
                    li = $(this).closest('li'),
                    name = li.data('name'),
                    last4 = li.data('last4'),
                    brand = li.data('brand');

                $('#payment-current-card').html(brand + ' ***' + last4 + ' - ' + name);

                $('input[name="card_id"]').val(cardId);

                $('#payment-modal').modal('hide');
            });
        });
    </script>
@endsection