@extends('layouts.master')

@section('title', 'Sign Up - ' . config('app.name'))

@section('content')

    <div class="row content">

        <div class="col-md-6 col-md-offset-3">

            <h2>Sign Up</h2>

            @include('snippets.errors')

            {{ Form::open(['url' => url('register'), 'role' => 'form']) }}

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group{{ $errors->has('first_name') || $errors->has('last_name') ? ' has-error' : '' }}">
                        <div class="row">
                            <div class="col-md-6">
                                {{ Form::label('first-name', 'First Name:', ['class' => 'control-label required']) }}

                                {{
                                    Form::text(
                                        'first_name',
                                        old('first_name'),
                                        [
                                            'class'       => 'form-control',
                                            'id'          => 'first-name',
                                            'placeholder' => 'First Name',
                                            'maxlength'   => 255,
                                            'required'    => true,
                                        ]
                                    )
                                }}

                                @include('snippets.errors_first', ['param' => 'first_name'])
                            </div>

                            <div class="col-md-6">
                                {{ Form::label('last-name', 'Last Name:', ['class' => 'control-label required']) }}

                                {{
                                    Form::text('last_name', old('last_name'), [
                                        'class'       => 'form-control',
                                        'id'          => 'last-name',
                                        'placeholder' => 'Last Name',
                                        'maxlength'   => 255,
                                        'required'    => true,
                                    ])
                                }}

                                @include('snippets.errors_first', ['param' => 'last_name'])
                            </div>
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        {{ Form::label('email', 'Email:', ['class' => 'control-label required']) }}

                        {{
                            Form::email('email', old('email'), [
                                'class'       => 'form-control',
                                'id'          => 'email',
                                'placeholder' => 'Email address',
                                'maxlength'   => 255,
                                'required'    => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'email'])
                    </div>

                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                        {{ Form::label('password', 'Password:', ['class' => 'control-label required']) }}

                        {{
                            Form::input('password', 'password', old('password'), [
                                'class'       => 'form-control',
                                'id'          => 'password',
                                'maxlength'   => 255,
                                'placeholder' => 'Password',
                                'required'    => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'password'])
                    </div>

                    <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                        {{ Form::label('password-confirmation', 'Confirm Password:', ['class' => 'control-label required']) }}

                        {{
                            Form::input('password', 'password_confirmation', old('password_confirmation'), [
                                'class'       => 'form-control',
                                'id'          => 'password-confirmation',
                                'maxlength'   => 255,
                                'placeholder' => 'Confirm Password',
                                'required'    => true,

                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'password_confirmation'])
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary center-block"><i class="fa fa-user"></i> Register</button>
                    </div>
                </div>
            </div>

            {{ Form::close() }}

        </div>

    </div>

@endsection