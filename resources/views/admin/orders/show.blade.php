@extends('layouts.admin')

@section('title', 'Order # ' . $order['order_number'] . ' - ' . config('app.name'))

@section('content')

    <div class="row content">

        <div class="col-md-12">

            <h1>
                Order # {{ $order['order_number'] }}
                <a href="{{ route('admin.orders.edit', $order['id']) }}" class="btn btn-sm btn-primary pull-right"><i class="fa fa-pencil"></i> Edit</a>
            </h1>

            <strong>Order Summary:</strong><br /><br />

            Status: {!! CustomHelper::formatOrderStatus($order) !!}

            @if ($order['cash_on_delivery'] === true)
                | <i class="fa fa-money"></i> Cash on Delivery
            @endif

            @if (!empty($order['shipping_carrier']))
                | {{ config('custom.checkout.shipping.carriers.' . $order['shipping_carrier'] . '.name') }}
            @endif

            @if (!empty($order['shipping_plan']))
                | {{ config('custom.checkout.shipping.carriers.' . $order['shipping_carrier'] . '.plans.' . $order['shipping_plan'] . '.plan') }}
            @endif

            @if (!empty($order['shipping_tracking_number']))
                (Tracking Number: {!! CustomHelper::formatTrackingURL($order) !!})
            @endif
            <br /><br />

            Payment: {!! CustomHelper::formatOrderPaymentStatus($order) !!}<br /><br />

            Confirmation Code: {{ $order['confirmation_code'] }}<br /><br />

            Contact Email: {{ !empty($order['contact_email']) ? $order['contact_email'] : $order->user['email'] }}
            <hr />

            <table class="table table-striped">
                <tr>
                    <th>Billing</th>
                    <th>Delivery</th>
                </tr>
                <tr>
                    <td>
                        @if (!empty($order['billing_name']) || $order['billing_address_1'])
                            {!!
                                CustomHelper::formatAddress([
                                    'name' => $order['billing_name'],
                                    'phone' => $order['billing_phone'],
                                    'address_1' => $order['billing_address_1'],
                                    'address_2' => $order['billing_address_2'],
                                    'city' => $order['billing_city'],
                                    'state' => $order['billing_state'],
                                    'zipcode' => $order['billing_zipcode']
                                ])
                            !!}
                        @else
                            <p class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> Not Provided</p>
                        @endif

                        <br /><br />

                        Phone:
                        @if (!empty($order['billing_phone']))
                            {{ CustomHelper::formatPhoneNumber($order['billing_phone']) }}
                        @else
                            <p class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> Not Provided</p>
                        @endif
                    </td>
                    <td>
                        @if (!empty($order['delivery_name']) || $order['delivery_address_1'])
                            {!!
                                CustomHelper::formatAddress([
                                    'name' => $order['delivery_name'],
                                    'phone' => $order['delivery_phone'],
                                    'address_1' => $order['delivery_address_1'],
                                    'address_2' => $order['delivery_address_2'],
                                    'city' => $order['delivery_city'],
                                    'state' => $order['delivery_state'],
                                    'zipcode' => $order['delivery_zipcode']
                                ])
                            !!}
                        @else
                            <p class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> Not Provided</p>
                        @endif

                        <br /><br />

                        Phone:
                        @if (!empty($order['delivery_phone']))
                            {{ CustomHelper::formatPhoneNumber($order['delivery_phone']) }}
                        @else
                            <p class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> Not Provided</p>
                        @endif

                        @if (!empty($order['notes']))
                            <br /><br />
                            <p>Notes: {!! $order['notes'] !!}</p>
                        @endif
                    </td>
                </tr>
            </table>

            <div class="table-responsive">
                <table class="table table-striped">
                    <tr>
                        <th>Item</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                    @foreach ($order->inventoryItems as $item)
                        <tr>
                            <td class="image">
                                @if ($item->product->defaultPhoto()->count() > 0)
                                    <img class="product-image" src="{{ CustomHelper::image($item->product->defaultPhoto['name'], true) }}" alt="{{ $item->product['name'] }}"><br />
                                @endif

                                {{ Html::link(route('shop.product', [$item->product['uri'], $item->product['id']]), $item->product['name'], ['title' => 'View this item']) }}

                                @if ($item->options()->count() > 0)
                                    <ul>
                                        @foreach ($item->options as $option)
                                            <li>{{ $option->attribute['name'] }}: {{ $option['name'] }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                            </td>
                            <td>
                                {{ $item['sku'] }}
                            </td>
                            <td>${{ number_format($item->pivot->price, 2) }}</td>
                            <td>{{ $item->pivot->quantity }}</td>
                            <td>${{ number_format($item->pivot->price * $item->pivot->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>

            <div class="basket-total">
                <p class="text-right"><span class="pull-left">Subtotal:</span>${{ number_format($order['subtotal'], 2) }} USD</p>
                <p class="text-right"><span class="pull-left">Tax:</span>${{ number_format($order['tax'], 2) }} USD</p>
                <p class="text-right"><span class="pull-left">Shipping:</span> {{ $order['shipping_fee'] }} USD</p>
                <hr />
                <p class="text-right"><span class="pull-left">Total:</span> ${{ number_format($order['total'], 2) }} USD</p>
            </div>

        </div>

    </div>

@endsection