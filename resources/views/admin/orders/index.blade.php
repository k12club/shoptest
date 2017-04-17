@extends('layouts.admin')

@section('title', 'Admin - Orders - ' . config('app.name'))

@section('content')

    <div class="row">

        <div class="col-md-12">

            <h1>Orders</h1>

            <table class="table table-striped table-bordered">
                <tr>
                    <th>Processing</th>
                    <th>Month-to-Date</th>
                    <th>Lifetime</th>
                </tr>
                <tr>
                    <td>{{ $stats['orders_processing'] }} (${{ number_format($stats['orders_processing_total'], 2) }})</td>
                    <td>{{ $stats['orders_month_to_date'] }} (${{ number_format($stats['orders_month_to_date_total'], 2) }})</td>
                    <td>{{ $stats['orders_lifetime'] }} (${{ number_format($stats['orders_lifetime_total'], 2) }})</td>
                </tr>
            </table>
            <hr />

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th class="text-center">Order Number</th>
                        <th class="text-center">Confirmation Code</th>
                        <th class="text-center">Name</th>
                        <th class="text-center">Total</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Payment</th>
                        <th class="text-center">Ordered</th>
                        <th class="text-center">Edit</th>
                    </tr>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ Html::link(route('admin.orders.order', $order['id']), $order['order_number'], ['title' => 'View this Order']) }}</td>
                            <td>{{ $order['confirmation_code'] }}</td>
                            <td class="text-center">{{ empty($order['user_id']) ? $order['billing_name'] : $order->user['first_name'] . ' ' . $order->user['last_name'] }}</td>
                            <td class="text-right">${{ number_format($order['total'], 2) }}</td>
                            <td class="text-center">{!! CustomHelper::formatOrderStatus($order) !!}</td>
                            <td class="text-center">{!! CustomHelper::formatOrderPaymentStatus($order) !!}</td>
                            <td class="text-right"><span title="{{ $order['created_at']->timezone(config('custom.timezone'))->toDayDateTimeString() }}">{{ $order['created_at']->diffForHumans() }}</span></td>
                            <td class="text-center"><a href="{{ route('admin.orders.edit', $order['id']) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a></td>
                        </tr>
                    @endforeach
                </table>
            </div>

        </div>

    </div>

@endsection