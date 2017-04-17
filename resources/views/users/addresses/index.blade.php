@extends('layouts.master')

@section('title', 'Manage Addresses - ' . config('app.name'))

@section('content')

    <div class="row content">

        <div class="col-md-12">
            <!-- Nav tabs -->
            @include('users.tabs')

            <h1>My Addresses</h1>

            @include('snippets.errors')
            @include('snippets.flash')

            @if (auth()->user()->deliveryAddresses()->count() > 0)
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>
                            Delivery
                            {{ Html::link(route('addresses.create', ['type' => 'delivery']), ' Add new', ['class' => 'fa fa-plus pull-right']) }}
                        </th>
                    </tr>
                    @foreach (auth()->user()->deliveryAddresses as $deliveryAddress)
                        <tr>
                            <td>
                                <span class="pull-left">
                                    {!! CustomHelper::formatAddress($deliveryAddress) !!}
                                </span>
                                <span class="pull-right text-right">
                                    {{ Html::link(route('addresses.edit', $deliveryAddress['id']), 'Edit') }}<br />

                                    @if ($deliveryAddress['default_delivery'])
                                        <i class="fa fa-check"></i> Primary
                                    @else
                                        {{ Html::link(route('addresses.primary', $deliveryAddress['id']), 'Make Primary') }}
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                <div class="alert alert-warning">
                    You have no saved delivery addresses at the present. Click {{ Html::link(route('addresses.create', ['type' => 'delivery']), 'here') }} to add a new delivery address.
                </div>
            @endif

            @if (auth()->user()->billingAddresses()->count() > 0)
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th>
                            Billing
                            {{ Html::link(route('addresses.create', ['type' => 'billing']), ' Add new', ['class' => 'fa fa-plus pull-right']) }}
                        </th>
                    </tr>
                    @foreach (auth()->user()->billingAddresses as $billingAddress)
                        <tr>
                            <td>
                                <span class="pull-left">
                                    {!! CustomHelper::formatAddress($billingAddress) !!}
                                </span>
                                <span class="pull-right text-right">
                                    {{ Html::link(route('addresses.edit', $billingAddress['id']), 'Edit') }}<br />
                                    @if ($billingAddress['default_billing'])
                                        <i class="fa fa-check"></i> Primary
                                    @else
                                        {{ Html::link(route('addresses.primary', $billingAddress['id']), 'Make Primary') }}
                                    @endif
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @else
                <div class="alert alert-warning">
                    You have no saved billing addresses at the present. Click {{ Html::link(route('addresses.create', ['type' => 'billing']), 'here') }} to add a new billing address.
                </div>
            @endif

        </div>

    </div>

@endsection