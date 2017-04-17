@extends('layouts.admin')

@section('title', 'Admin - Edit User - ' . config('app.name'))

@section('content')

    <div class="row">

        <div class="col-md-5">

            <h2>Account</h2>

            @include('snippets.errors')
            @include('snippets.flash')

            {{ Form::open(['route' => ['admin.users.update', $user['id']], 'files' => true, 'role' => 'form']) }}

            <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                {{ Form::label('first-name', 'First Name:', ['class' => 'control-label required']) }}

                {{
                    Form::text('first_name', old('first_name', $user['first_name']), [
                        'class'       => 'form-control',
                        'id'          => 'first-name',
                        'maxlength'   => 255,
                        'placeholder' => 'First name',
                        'required'    => true,
                    ])
                }}

                @include('snippets.errors_first', ['param' => 'first_name'])
            </div>

            <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                {{ Form::label('last-name', 'First Name:', ['class' => 'control-label required']) }}

                {{
                    Form::text('last_name', old('last_name', $user['last_name']), [
                        'class'       => 'form-control',
                        'id'          => 'last-name',
                        'maxlength'   => 255,
                        'placeholder' => 'First name',
                        'required'    => true,
                    ])
                }}

                @include('snippets.errors_first', ['param' => 'last_name'])
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                {{ Form::label('email', 'Email:', ['class' => 'control-label required']) }}

                {{
                    Form::email('email', old('email', $user['email']), [
                        'class'       => 'form-control',
                        'id'          => 'email',
                        'maxlength'   => 255,
                        'placeholder' => 'Email address',
                        'required'    => true,
                    ])
                }}

                @include('snippets.errors_first', ['param' => 'email'])
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                {{ Form::label('password', 'Password:', ['class' => 'control-label']) }}

                {{
                    Form::input('password', 'password', old('password'), [
                        'class'       => 'form-control',
                        'id'          => 'password',
                        'maxlength'   => 255,
                        'placeholder' => 'Password',
                    ])
                }}

                <p class="help-block">
                    Leave this field blank will keep the current password.
                </p>

                @include('snippets.errors_first', ['param' => 'password'])
            </div>

            <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                {{ Form::label('type', 'User Type:', ['class' => 'control-label required']) }}

                {{
                    Form::select('type', [
                            'user' => 'User',
                            'admin' => 'Admin'
                        ],
                        old('type', $user['type']), [
                            'class' => 'form-control',
                            'id' => 'type',
                            'required' => true,
                        ]
                    )
                }}

                @include('snippets.errors_first', ['param' => 'type'])
            </div>

            <div class="form-group">
                <button class="btn btn-primary"><i class="fa fa-save"></i> Save</button>

                {{ Html::link(route('admin.users'), 'Cancel', ['class' => 'btn', 'title' => 'Cancel']) }}
            </div>

            {{ Form::close() }}
        </div>

        <div class="col-md-7">
            <h2>Info</h2>

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#orders" aria-controls="orders" role="tab" data-toggle="tab">Orders</a></li>
                <li role="presentation"><a href="#addresses" aria-controls="addresses" role="tab" data-toggle="tab">Addresses</a></li>
                <li role="presentation"><a href="#payments" aria-controls="payments" role="tab" data-toggle="tab">Payments</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="orders">
                    @include('admin.users.orders.index')
                </div>
                <div role="tabpanel" class="tab-pane" id="addresses">
                    @include('admin.users.addresses.index')
                </div>
                <div role="tabpanel" class="tab-pane" id="payments">
                    @include('admin.users.payments.index')
                </div>
            </div>
        </div>
    </div>

@endsection