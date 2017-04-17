@extends('layouts.admin')

@section('title', 'Admin Panel - ' . config('app.name'))

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h1>Dashboard</h1>
        </div>
    </div>

    <div class="row">

        <div class="col-md-4">

            <table class="table table-bordered table-striped">
                <tr class="info">
                    <th colspan="2" class="text-center"><i class="fa fa-shopping-cart"></i> Orders</th>
                </tr>
                <tr>
                    <td>Last Order</td>
                    <td class="text-center">
                        @if (isset($stats['orders']) && $stats['orders']['last_order'])
                            {{ Html::link(route('admin.orders.order', $stats['orders']['last_order']['id']), '#' . $stats['orders']['last_order']['order_number'], ['title' => 'View this Order']) }} &middot; <small>${{ number_format($stats['orders']['last_order']['total'], 2) }} ~ {{ $stats['orders']['last_order']['created_at']->diffForHumans() }}</small>
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                <tr>
                    <td>Processing</td>
                    <td class="text-right">{{ $stats['orders']['processing'] }} &middot; ${{ number_format($stats['orders']['processing_total'], 2) }}</td>
                </tr>
                <tr>
                    <td>Month-to-date</td>
                    <td class="text-right">{{ $stats['orders']['month_to_date'] }} &middot; ${{ number_format($stats['orders']['month_to_date_total'], 2) }}</td>
                </tr>
                <tr>
                    <td>Lifetime</td>
                    <td class="text-right">{{ $stats['orders']['lifetime'] }} &middot; ${{ number_format($stats['orders']['lifetime_total'], 2) }}</td>
                </tr>
            </table>

        </div>

        <div class="col-md-4">

            <table class="table table-bordered table-striped">
                <tr class="info">
                    <th colspan="2" class="text-center"><i class="fa fa-home"></i> Store</th>
                </tr>
                <tr>
                    <td>Categories</td>
                    <td class="text-right">{{ $stats['store']['categories'] }}</td>
                </tr>
                <tr>
                    <td>Products</td>
                    <td class="text-right">{{ $stats['store']['products'] }}</td>
                </tr>
                <tr>
                    <td>Inventory</td>
                    <td class="text-right">{{ $stats['store']['inventory'] }}</td>
                </tr>
                <tr>
                    <td>Out of Stock</td>
                    <td class="text-right">
                        @if ($stats['store']['out_of_stock'] > 0)
                            {{ Html::link(route('admin.products.out_of_stock'), $stats['store']['out_of_stock']) }}
                        @else
                            0
                        @endif

                        items
                    </td>
                </tr>
            </table>

        </div>

        <div class="col-md-4">

            <table class="table table-bordered table-striped">
                <tr class="info">
                    <th colspan="2" class="text-center"><i class="fa fa-users"></i> Users</th>
                </tr>
                <tr>
                    <td>Last User</td>
                    <td class="text-center">{{ Html::link(route('admin.users.user', $stats['users']['last_user']['id']), $stats['users']['last_user']['first_name'] . ' ' . $stats['users']['last_user']['last_name']) }} &middot; <small>{{ $stats['users']['last_user']['created_at']->diffForHumans() }}</small></td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td class="text-right">{{ $stats['users']['total'] }}</td>
                </tr>
                <tr>
                    <td>Admins</td>
                    <td class="text-right">{{ $stats['users']['admins'] }}</td>
                </tr>
                <tr>
                    <td>Customers</td>
                    <td class="text-right">{{ $stats['users']['customers'] }}</td>
                </tr>
            </table>

        </div>

    </div>

@endsection