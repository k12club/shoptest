@extends('layouts.master')

@section('title', 'Edit your Profile - ' . config('app.name'))

@section('content')

    <div class="row content">

        <div class="col-md-12">
            <!-- Nav tabs -->
            @include('users.tabs')

            <h1>Profile</h1>

            @include('snippets.errors')
            @include('snippets.flash')

            {{ Form::open(['route' => 'account.update', 'role' => 'form']) }}

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('first_name') ? ' has-error' : '' }}">
                        {{ Form::label('first-name', 'First Name:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('first_name', old('first_name', auth()->user()['first_name']), [
                                'class'        => 'form-control',
                                'id'          => 'first-name',
                                'maxlength'   => 255,
                                'placeholder' => 'First Name',
                                'required'    => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'first_name'])
                    </div>

                    <div class="form-group{{ $errors->has('last_name') ? ' has-error' : '' }}">
                        {{ Form::label('last-name', 'Last Name:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('last_name', old('last_name', auth()->user()['last_name']), [
                                'class'       => 'form-control',
                                'id'          => 'last-name',
                                'maxlength'   => 255,
                                'placeholder' => 'Last Name',
                                'required'    => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'last_name'])
                    </div>

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        {{ Form::label('email', 'Email address:', ['class' => 'control-label required']) }}

                        {{
                            Form::email('email', old('email', auth()->user()['email']), [
                                'autocomplete' => 'off',
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
                                'autocomplete' => 'off',
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
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
                </div>
            </div>

            {{ Form::close() }}
        </div>

    </div>

@endsection