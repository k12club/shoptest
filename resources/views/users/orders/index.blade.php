@extends('layouts.master')

@section('title', 'Order History - ' . config('app.name'))

@section('content')

    <div class="row content">

        <div class="col-md-12">
            <!-- Nav tabs -->
            @include('users.tabs')

            <h1>My Orders</h1>

            @include('snippets.errors')
            @include('snippets.flash')

            @if (auth()->user()->orders() && auth()->user()->orders()->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <tr>
                            <th class="text-center">Order #</th>
                            <th class="text-center">Billing</th>
                            <th class="text-center">Delivery</th>
                            <th class="text-center">Total</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Ordered</th>
                        </tr>
                        @foreach (auth()->user()->orders()->orderBy('created_at', 'desc')->get() as $order)
                            @can('view', $order)
                                <tr>
                                    <td>
                                        {{ Html::link(route('user_order', $order['order_number']), $order['order_number']) }}
                                    </td>
                                    <td>
                                        @if (!empty($order['billing_name']) || !empty($order['billing_city']))
                                            {{ $order['billing_name'] }} -
                                            {{ $order['billing_city'] }},
                                            {{ $order['billing_state'] }}
                                        @else
                                            <span class="text-warning"><i class="fa fa-exclamation-circle"></i> Not provided</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (!empty($order['delivery_name']) || !empty($order['delivery_city']))
                                            {{ $order['delivery_name'] }} -
                                            {{ $order['delivery_city'] }},
                                            {{ $order['delivery_state'] }}
                                        @else
                                            <span class="text-warning"><i class="fa fa-exclamation-circle"></i> Not provided</span>
                                        @endif
                                    </td>
                                    <td class="text-right">${{ money_format('%i', $order['total']) }}</td>
                                    <td class="text-center">{!! CustomHelper::formatOrderStatus($order) !!}</td>
                                    <td>{{ $order['created_at']->timezone(config('custom.timezone'))->toDayDateTimeString() }}</td>
                                </tr>
                            @endcan
                        @endforeach
                    </table>
                </div>
            @else
                <div class="alert alert-warning">You have not made any orders yet.</div>
            @endif

        </div>

    </div>

@endsection