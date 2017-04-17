@extends('layouts.admin')

@section('title', 'Admin - Edit Order - ' . config('app.name'))

@section('content')

    <div class="row">

        <div class="col-md-12">

            <h2>Admin - Edit Order</h2>

            @include('snippets.errors')
            @include('snippets.flash')

            {{ Form::open(['route' => ['admin.orders.update', $order['id']], 'role' => 'form', 'id' => 'order-edit-form']) }}

            <div class="row">

                <div class="col-md-6">

                    <div class="checkout-box">

                        <h2>
                            Order

                            <span class="pull-right">
                                {!! CustomHelper::formatOrderPaymentStatus($order) !!}

                                @if ($order['payment_status'] == 'paid' && $order['payment_method'] == 'card')
                                    <a href="#" id="refund-btn" class="btn btn-info btn-sm" title="Click here to make a full refund for this order"><i class="fa fa-reply"></i> Refund</a>
                                @endif
                            </span>
                        </h2>

                        <div class="form-group{{ $errors->has('order_number') ? ' has-error' : '' }}">
                            {{ Form::label('order-number', 'Order Number', ['class' => 'control-label required']) }}

                            {{
                                Form::text('order_number', old('order_number', $order['order_number']), [
                                    'class'       => 'form-control',
                                    'id'          => 'order-number',
                                    'maxlength'   => 10,
                                    'placeholder' => 'Order number',
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'order_number'])
                        </div>

                        <div class="form-group{{ $errors->has('confirmation_code') ? ' has-error' : '' }}">
                            {{ Form::label('confirmation-code', 'Confirmation Code', ['class' => 'control-label required']) }}

                            {{
                                Form::text('confirmation_code', old('confirmation_code', $order['confirmation_code']), [
                                    'class'       => 'form-control',
                                    'id'          => 'confirmation-code',
                                    'maxlength'   => 6,
                                    'placeholder' => 'Confirmation Code',
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'confirmation_code'])
                        </div>

                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            {{ Form::label('status', 'Order Status', ['class' => 'control-label required']) }}

                            {{
                                Form::select('status', config('custom.order_status'), !empty($order['status']) ? $order['status'] : '', [
                                    'class'    => 'form-control',
                                    'id'       => 'status',
                                    'required' => true,
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'status'])
                        </div>

                        <div class="form-group{{ $errors->has('payment_status') ? ' has-error' : '' }}">
                            {{ Form::label('payment_status', 'Payment Status', ['class' => 'control-label required']) }}

                            {{
                                Form::select('payment_status', config('custom.payment_status'), $order['payment_status'], [
                                    'class'    => 'form-control',
                                    'id'       => 'payment-status',
                                    'required' => true,
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'payment_status'])
                        </div>

                        <div class="form-group{{ $errors->has('payment_method') ? ' has-error' : '' }}">
                            {{ Form::label('payment_method', 'Payment Method', ['class' => 'control-label']) }}

                            {{
                                Form::select('payment_method', ['' => 'Select one'] + config('custom.payment_method'), $order['payment_method'], [
                                    'class' => 'form-control',
                                    'id'    => 'payment-method',
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'payment_method'])
                        </div>

                        <div class="form-group{{ $errors->has('contact_email') ? ' has-error' : '' }}">
                            {{ Form::label('contact-email', 'Contact Email', ['class' => 'control-label required']) }}

                            {{
                                Form::text('contact_email', old('contact_email', $order['contact_email']), [
                                    'class'       => 'form-control',
                                    'id'          => 'contact-email',
                                    'maxlength'   => 255,
                                    'placeholder' => 'Contact Email address',
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'contact_email'])
                        </div>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="checkout-box">

                        <h2>
                            Shipping

                            <a href="{{ route('admin.orders.send_email', [$order['id'], 'shipping_confirmation']) }}" class="btn btn-sm btn-info pull-right" title="Send shipping confirmation email to customer"><i class="fa fa-envelope"></i> Send Shipping Email</a>
                        </h2>

                        <div class="form-group{{ $errors->has('shipping_carrier') ? ' has-error' : '' }}">
                            {{ Form::label('shipping-carrier', 'Shipping Carrier:', ['class' => 'control-label']) }}

                            {{
                                Form::select('shipping_carrier', [
                                    ''     => 'Select a shipping carrier',
                                    'usps' => 'USPS',
                                ], old('shipping_carrier', $order['shipping_carrier']), [
                                    'id'    => 'shipping-carrier',
                                    'class' => 'form-control'
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'shipping_carrier'])
                        </div>

                        <div class="form-group{{ $errors->has('shipping_plan') ? ' has-error' : '' }}">
                            {{ Form::label('shipping-plan', 'Shipping Plan:', ['class' => 'control-label']) }}

                            {{
                                Form::select('shipping_plan', [
                                    ''         => 'Select a shipping plan',
                                    'standard' => 'Standard (USPS Priority Mail 1-2 Day)',
                                    'express'  => 'Express (USPS Priority Mail Express 1-Day)',
                                ], old('shipping_plan', $order['shipping_plan']), [
                                    'id'    => 'shipping-plan',
                                    'class' => 'form-control'
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'shipping_plan'])
                        </div>

                        <div class="form-group{{ $errors->has('shipping_tracking_number') ? ' has-error' : '' }}">
                            {{ Form::label('shipping-tracking-number', 'Tracking Number:', ['class' => 'control-label']) }}

                            {{
                                Form::text('shipping_tracking_number', old('shipping_tracking_number', $order['shipping_tracking_number']), [
                                    'id'          => 'shipping-tracking-number',
                                    'placeholder' => 'Tracking number',
                                    'maxlength'   => 45,
                                    'class'       => 'form-control'
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'shipping_tracking_number'])
                        </div>

                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-md-6">

                    <div class="checkout-box">

                        <h2>Delivery</h2>

                        <div class="form-group{{ $errors->has('delivery_name') ? ' has-error' : '' }}">
                            {{ Form::label('delivery-name', 'Name:', ['class' => 'control-label required']) }}

                            {{
                                Form::text('delivery_name', old('delivery_name', $order['delivery_name']), [
                                    'class'       => 'form-control',
                                    'id'          => 'delivery-name',
                                    'maxlength'   => 255,
                                    'placeholder' => 'Name',
                                    'required'    => true,
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'delivery_name'])
                        </div>

                        <div class="form-group{{ $errors->has('delivery_address_1') ? ' has-error' : '' }}">
                            {{ Form::label('delivery-address-1', 'Address 1:', ['class' => 'control-label required']) }}

                            {{
                                Form::text('delivery_address_1', old('delivery_address_1', $order['delivery_address_1']), [
                                    'class'       => 'form-control',
                                    'id'          => 'delivery-address-1',
                                    'maxlength'   => 255,
                                    'placeholder' => '123 Main St.',
                                    'required'    => true,
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'delivery_address_1'])
                        </div>

                        <div class="form-group{{ $errors->has('delivery_address_2') ? ' has-error' : '' }}">
                            {{ Form::label('delivery-address-2', 'Address 2:', ['class' => 'control-label']) }}

                            {{
                                Form::text('delivery_address_2', old('delivery_address_2', $order['delivery_address_2']), [
                                    'class'       => 'form-control',
                                    'id'          => 'delivery-address-2',
                                    'maxlength'   => 255,
                                    'placeholder' => 'Apt. 1',
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'delivery_address_2'])
                        </div>

                        <div class="form-group{{ $errors->has('delivery_city') ? ' has-error' : '' }}">
                            {{ Form::label('delivery-city', 'City:', ['class' => 'control-label required']) }}

                            {{
                                Form::text('delivery_city', old('delivery_city', $order['delivery_city']), [
                                    'class'       => 'form-control',
                                    'id'          => 'delivery-city',
                                    'maxlength'   => 100,
                                    'placeholder' => 'City',
                                    'required'    => true,
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'delivery_city'])
                        </div>

                        <div class="form-group{{ $errors->has('delivery_state') ? ' has-error' : '' }}">
                            {{ Form::label('delivery-state', 'State:', ['class' => 'control-label required']) }}

                            {{
                                Form::select('delivery_state', config('custom.states'), old('delivery_state', $order['delivery_state']), [
                                    'class'    => 'form-control',
                                    'id'       => 'delivery-state',
                                    'required' => true,
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'delivery_state'])
                        </div>

                        <div class="form-group{{ $errors->has('delivery_zipcode') ? ' has-error' : '' }}">
                            {{ Form::label('delivery-zipcode', 'ZIP code:', ['class' => 'control-label required']) }}

                            {{
                                Form::text('delivery_zipcode', old('delivery_zipcode', $order['delivery_zipcode']), [
                                    'class'       => 'form-control',
                                    'id'          => 'delivery-zipcode',
                                    'maxlength'   => 20,
                                    'placeholder' => '92683',
                                    'required'    => true,
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'delivery_zipcode'])
                        </div>

                        <div class="form-group{{ $errors->has('delivery_phone') ? ' has-error' : '' }}">
                            {{ Form::label('delivery-phone', 'Phone Number:', ['class' => 'control-label required']) }}

                            {{
                                Form::text('delivery_phone', old('delivery_phone', $order['delivery_phone']), [
                                    'class'       => 'form-control',
                                    'id'          => 'delivery-phone',
                                    'maxlength'   => 20,
                                    'placeholder' => '(714) 123-4567',
                                    'required'    => true,
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'delivery_phone'])
                        </div>

                    </div>

                </div>

                <div class="col-md-6">

                    <div class="checkout-box">

                        <h2>Billing</h2>

                        <div class="form-group{{ $errors->has('billing_name') ? ' has-error' : '' }}">
                            {{ Form::label('billing-name', 'Name:', ['class' => 'control-label']) }}

                            {{
                                Form::text('billing_name', old('billing_name', $order['billing_name']), [
                                    'class'       => 'form-control',
                                    'id'          => 'billing-name',
                                    'maxlength'   => 255,
                                    'placeholder' => 'Name',
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'billing_name'])
                        </div>

                        <div class="form-group{{ $errors->has('billing_address_1') ? ' has-error' : '' }}">
                            {{ Form::label('billing-address-1', 'Address 1:', ['class' => 'control-label']) }}

                            {{
                                Form::text('billing_address_1', old('billing_address_1', $order['billing_address_1']), [
                                    'class'       => 'form-control',
                                    'id'          => 'billing-address-1',
                                    'maxlength'   => 255,
                                    'placeholder' => '123 Main St.',
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'billing_address_1'])
                        </div>

                        <div class="form-group{{ $errors->has('billing_address_2') ? ' has-error' : '' }}">
                            {{ Form::label('billing-address-2', 'Address 2:', ['class' => 'control-label']) }}

                            {{
                                Form::text('billing_address_2', old('billing_address_2', $order['billing_address_2']), [
                                    'class'       => 'form-control',
                                    'id'          => 'billing-address-2',
                                    'maxlength'   => 255,
                                    'placeholder' => 'Apt. 1',
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'billing_address_2'])
                        </div>

                        <div class="form-group{{ $errors->has('billing_city') ? ' has-error' : '' }}">
                            {{ Form::label('billing-city', 'City:', ['class' => 'control-label']) }}

                            {{
                                Form::text('billing_city', old('billing_city', $order['billing_city']), [
                                    'class'       => 'form-control',
                                    'id'          => 'billing-city',
                                    'maxlength'   => 255,
                                    'placeholder' => 'City',
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'billing_city'])
                        </div>

                        <div class="form-group{{ $errors->has('billing_state') ? ' has-error' : '' }}">
                            {{ Form::label('billing-state', 'State:', ['class' => 'control-label']) }}

                            {{
                                Form::select('billing_state', config('custom.states'), old('billing_state', $order['billing_state']), [
                                    'class' => 'form-control',
                                    'id'    => 'billing-state',
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'billing_state'])
                        </div>

                        <div class="form-group{{ $errors->has('billing_zipcode') ? ' has-error' : '' }}">
                            {{ Form::label('billing-zipcode', 'ZIP code:', ['class' => 'control-label']) }}

                            {{
                                Form::text('billing_zipcode', old('billing_zipcode', $order['billing_zipcode']), [
                                    'class'       => 'form-control',
                                    'id'          => 'billing-zipcode',
                                    'maxlength'   => 20,
                                    'placeholder' => '92683',
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'billing_zipcode'])
                        </div>

                        <div class="form-group{{ $errors->has('billing_phone') ? ' has-error' : '' }}">
                            {{ Form::label('billing-phone', 'Phone Number:', ['class' => 'control-label']) }}

                            {{
                                Form::text('billing_phone', old('billing_phone', $order['billing_phone']), [
                                    'class'       => 'form-control',
                                    'id'          => 'billing-phone',
                                    'maxlength'   => 20,
                                    'placeholder' => '(714) 123-4567',
                                ])
                            }}

                            @include('snippets.errors_first', ['param' => 'billing_phone'])
                        </div>

                        <p class="buttons">
                            <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Update</button>
                        </p>

                    </div>

                </div>

            </div>

            {{ Form::close() }}

        </div>

    </div>

@endsection

@section('bottom_block')
    @parent

    <script type="text/javascript">
        $( document ).ready(function() {
            $('#refund-btn').click(function(e) {
                e.preventDefault();

                var $form = $(this).closest('form');

                $('<input>').attr({"type": "hidden",  "name": "refund", "value": true}).appendTo($form);

                var r = confirm('Are you sure you want to refund this order?');

                if (r == true) {
                    $form.submit();
                } else {
                    return false;
                }
            });
        });
    </script>

@endsection