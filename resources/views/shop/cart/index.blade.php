@extends('layouts.master')

@section('title', 'Shopping Cart - ' . config('app.name'))

@section('_token', csrf_token())

@section('content')

    <div class="row">

        <div class="col-md-12">
            <h1>
                Shopping Cart

                @if (!empty($cartItems))
                    {{ Html::link(route('shop.checkout'), 'Go to Checkout &raquo;', ['class' => 'btn btn-primary pull-right checkout', 'title' => 'Go to Checkout']) }}
                @endif
            </h1>
        </div>

    </div>

    <div class="row">

        <div class="col-md-12">

            @include('shop.cart.list')

            @if (!empty($cartItems))
                <p class="text-right subtotal"><span class="text-muted">Subtotal:</span> <strong>$</strong><strong id="subtotal">{{ money_format('%i', $total) }}</strong></p>

                {{ Html::link(route('shop.checkout'), 'Go to Checkout &raquo;', ['class' => 'btn btn-primary pull-right checkout', 'title' => 'Go to Checkout']) }}<br /><br />
            @endif

        </div>

    </div>

@stop

@section('bottom_block')
    <script>
        $(document).ready(function() {
            // Compute the subtotal
            var update = function() {
                var items = {},
                    subtotal = 0,
                    totalItems = 0;

                $('[id^="item-"] select').each(function(idx, item) {
                    var quantity = parseInt($(this).val()),
                        tr = $(this).closest('tr'),
                        inventoryId = tr.attr('id').substr(5),
                        price = tr.data('price'),
                        itemTotal = price * quantity;

                    items[inventoryId] = quantity;

                    tr.find('.item-total').text(itemTotal.toFixed(2));

                    subtotal += itemTotal;

                    $('#subtotal').text(subtotal.toFixed(2));

                    totalItems += quantity;

                    $('#cart-global').text(totalItems);

                    // Remove an item if its quantity is 0
                    if (quantity == 0) {
                        $('#item-' + inventoryId).remove();
                    }
                });

                // Message when cart is empty (all items are removed)
                if (totalItems == 0) {
                    $('.basket-table').replaceWith('<div class="alert alert-warning">You have removed all items in the cart.</div>');

                    $('.btn.checkout, p.subtotal').remove();
                }

                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="_token"]').attr('content')
                    },
                    url: "/shop/cart/update",
                    type: "POST",
                    data: {
                        items: items
                    },
                    cache: false,
                    success: function(data) {
                    },
                    error: function() {
                    }
                });
            };

            // When updating quantity on each item
            $('[id^="item-"] select').change(function() {
                update();
            });

            // When click on Remove button on each item
            $('.remove-btn').click(function() {
                var tr = $(this).closest('tr'),
                    id = tr.attr('id'),
                    quantityInput = $(this).prev('select');

                quantityInput.val('0');

                update();
            });
        });
    </script>
@endsection