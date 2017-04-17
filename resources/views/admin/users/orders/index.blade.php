@if ($user->orders()->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <tr>
                <th class="text-center">Order Number</th>
                <th class="text-center">Confirmation Code</th>
                <th class="text-center">Name</th>
                <th class="text-center">Total</th>
                <th class="text-center">Status</th>
                <th class="text-center">Ordered</th>
                <th class="text-center">Edit</th>
            </tr>
            @foreach($user->orders()->orderBy('created_at', 'desc')->get() as $order)
                <tr>
                    <td><a href="{{ route('admin.orders.order', $order['id']) }}" title="View this Order">{{ $order['order_number'] }}</a></td>
                    <td>{{ $order['confirmation_code'] }}</td>
                    <td>{{ empty($order['user_id']) ? $order['billing_name'] : $order->user['first_name'] . ' ' . $order->user['last_name'] }}</td>
                    <td class="text-right">${{ number_format($order['total'], 2) }}</td>
                    <td class="text-center">{!! CustomHelper::formatOrderStatus($order['status']) !!}</td>
                    <td><span title="{{ $order['created_at']->timezone(config('custom.timezone'))->toDayDateTimeString() }}">{{ $order['created_at']->diffForHumans() }}</span></td>
                    <td class="text-center"><a href="{{ route('admin.orders.edit', $order['id']) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a></td>
                </tr>
            @endforeach
        </table>
    </div>
@else
    <div class="alert alert-warning">
        This user has made no purchases yet.
    </div>
@endif