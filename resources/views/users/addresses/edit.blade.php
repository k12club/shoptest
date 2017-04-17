@extends('layouts.master')

@section('title', 'Edit address - ' . config('app.name'))

@section('content')

    <div class="row content">

        <div class="col-md-12">
            <!-- Nav tabs -->
            @include('users.tabs')

            <ol class="breadcrumb">
                <li>{{ Html::link(route('addresses'), 'Addresses', ['role' => 'tab']) }}</li>
                <li class="active">Edit address</li>
            </ol>

            <h1>Edit address</h1>

            @include('snippets.errors')
            @include('snippets.flash')

            {{ Form::open(['route' => ['addresses.update', $address['id']], 'role' => 'form']) }}

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {{ Form::label('name', 'Name:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('name', old('name', $address['name']), [
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

                        {{ Form::text('phone', old('phone', $address['phone']), [
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
                            Form::text('address_1', old('address_1', $address['address_1']), [
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
                            Form::text('address_2', old('address_2', $address['address_2']), [
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
                    <div class="form-group {{ $errors->has('city') ? ' has-error' : '' }}">
                        {{ Form::label('city', 'City:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('city', old('city', $address['city']), [
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
                    <div class="form-group {{ $errors->has('state') ? ' has-error' : '' }}">
                        {{ Form::label('state', 'State:', ['class' => 'control-label required']) }}

                        {{
                            Form::select('state', config('custom.states'), old('state', $address['state']), [
                                'class'    => 'form-control',
                                'id'       => 'state',
                                'required' => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'state'])
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group {{ $errors->has('zipcode') ? ' has-error' : '' }}">
                        {{ Form::label('zipcode', 'Postal Code:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('zipcode', old('zipcode', $address['zipcode']), [
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
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success" title="Update this address"><i class="fa fa-save"></i> Update</button>
                </div>
            </div>

            {{ Form::close() }}

            <div class="row">
                <div class="col-md-12">
                    <br />
                    <label>Remove this address?</label>
                    {{ Form::open(['route' => ['addresses.remove', $address['id']], 'method' => 'DELETE', 'onsubmit' => 'return confirm("Do you really want to remove this address?");']) }}
                        <button type="submit" class="btn btn-danger" title="Remove this address"><i class="fa fa-times"></i> Remove</button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

    </div>
@endsection