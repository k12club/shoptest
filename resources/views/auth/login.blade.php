@extends('layouts.master')

@section('title', 'Sign In - ' . config('app.name'))

@section('content')

    <div class="row content">

        <div class="col-md-6 col-md-offset-3">

            <h2>Sign In</h2>

            @include('snippets.errors')
            @include('snippets.flash')

            @if (env('SHOP_DEMO'))
                <p class="alert alert-info">
                    <i class="fa fa-exclamation-circle"></i> Demo credentials:<br /><br />
                    <label>Admin:</label> Email: admin@gmail.com / Password: 123456<br />
                    <label>Customer:</label> Email: customer@gmail.com / Password: 123456<br />
                </p>
            @endif

            {{ Form::open(['route' => 'login', 'role' => 'form']) }}

            <div class="row">
                <div class="col-md-12">
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

                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
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

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-lock"></i> Login</button>

                        <label class="control-label btn text-muted">
                            {{ Form::checkbox('remember') }}

                            <span style="vertical-align: top;">Remember Me</span>
                        </label>

                        {{ Html::link(url('password/reset'), 'Forgot Your Password?', ['class' => 'btn btn-link pull-right']) }}

                    </div>

                    <hr class="separator" />

                    <div class="form-group">
                        {{ Form::label(null, 'Need an account?', ['class' => 'control-label']) }}

                        {{ Html::link(url('register'), 'Sign Up Now &raquo;', ['class' => 'btn btn-success']) }}
                    </div>
                </div>
            </div>

            {{ Form::close() }}

        </div>

    </div>

@endsection