@extends('layouts.master')

@section('title', 'Add new address - ' . config('app.name'))

@section('content')

    <div class="row content">

        <div class="col-md-12">
            <!-- Nav tabs -->
            @include('users.tabs')

            <ol class="breadcrumb">
                <li>{{ Html::link(route('addresses'), 'Addresses', ['role' => 'tab']) }}</li>
                <li class="active">Add address</li>
            </ol>

            <h1>Add new address</h1>

            @include('snippets.errors')
            @include('snippets.flash')

            {{ Form::open(['route' => 'addresses.store', 'role' => 'form']) }}

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                        {{ Form::label(null, 'Type:', ['class' => 'control-label required']) }}
                        &nbsp;&nbsp;&nbsp;
                        <label style="color: #666666; font-size: 14px; font-weight: normal;">
                            {{ Form::radio('type', 'delivery', !empty(old('type')) ? old('type') : (isset($_GET['type']) && $_GET['type'] == 'delivery')) }}

                            Delivery
                        </label>
                        &nbsp;&nbsp;&nbsp;
                        <label style="color: #666666; font-size: 14px; font-weight: normal;">
                            {{ Form::radio('type', 'billing', !empty(old('type')) ? old('type') : (isset($_GET['type']) && $_GET['type'] == 'billing')) }}

                            Billing
                        </label>

                        @include('snippets.errors_first', ['param' => 'type'])
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {{ Form::label('name', 'Name:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('name', old('name'), [
                                'class'     => 'form-control',
                                'id'        => 'name',
                                'maxlength' => 255,
                                'required'  => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'name'])
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('phone') ? ' has-error' : '' }}">
                        {{ Form::label('phone', 'Phone:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('phone', old('phone'), [
                                'class'       => 'form-control',
                                'id'          => 'phone',
                                'maxlength'   => 255,
                                'placeholder' => '(XXX) XXX - XXXX',
                                'required'    => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'phone'])
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('address_1') ? ' has-error' : '' }}">
                        {{ Form::label('address-1', 'Address:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('address_1', old('address_1'), [
                                'class'     => 'form-control',
                                'id'        => 'address-1',
                                'maxlength' => 255,
                                'required'  => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'address_1'])
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('address_2') ? ' has-error' : '' }}">
                        {{ Form::label('address-2', 'Address 2:', ['class' => 'control-label']) }}

                        {{
                            Form::text('address_2', old('address_2'), [
                                'class'     => 'form-control',
                                'id'        => 'address-2',
                                'maxlength' => 255,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'address_2'])
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group{{ $errors->has('city') ? ' has-error' : '' }}">
                        {{ Form::label('city', 'City:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('city', old('city'), [
                                'class'     => 'form-control',
                                'id'        => 'city',
                                'maxlength' => 255,
                                'required'  => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'city'])
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group{{ $errors->has('state') ? ' has-error' : '' }}">
                        {{ Form::label('state', 'State:', ['class' => 'control-label required']) }}

                        {{
                            Form::select('state', config('custom.states'), old('state'), [
                                'class'    => 'form-control',
                                'id'       => 'state',
                                'required' => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'state'])
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group{{ $errors->has('zipcode') ? ' has-error' : '' }}">
                        {{ Form::label('zipcode', 'Postal Code:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('zipcode', old('zipcode'), [
                                'class'     => 'form-control',
                                'id'        => 'zipcode',
                                'maxlength' => 10,
                                'required'  => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'zipcode'])
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Add</button>
                </div>
            </div>

            {{ Form::close() }}
        </div>

    </div>

@endsection