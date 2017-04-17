@extends('layouts.master')

@section('title', 'Reset Password - ' . config('app.name'))

@section('content')

	<div class="row content">
		<div class="col-md-6 col-md-offset-3">

			<h2>Reset Password</h2>

			@include('snippets.errors')
			@include('snippets.flash')

			{{ Form::open(['url' => 'password/reset', 'role' => 'form']) }}

				{{ Form::hidden('token', $token)  }}

				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							{{ Form::label('email', 'Email:', ['class' => 'control-label required']) }}

							{{
								Form::email( 'email', old('email'), [
									'class'       => 'form-control',
									'id'          => 'email',
									'maxlength'   => 255,
									'placeholder' => 'Email address',
									'required'    => true,
								])
							}}
						</div>

						<div class="form-group">
							{{ Form::label('password', 'Password:', ['class' => 'control-label required']) }}

							{{
								Form::input( 'password', 'password', old('password'), [
									'class'       => 'form-control',
									'id'          => 'password',
									'maxlength'   => 255,
									'placeholder' => 'Password',
									'required' => true,
								])
							}}
						</div>

						<div class="form-group">
							{{ Form::label('password-confirmation', 'Confirm Password:', ['class' => 'control-label required']) }}

							{{
								Form::input( 'password', 'password_confirmation', old('password_confirmation'), [
									'class'       => 'form-control',
									'id'          => 'password-confirmation',
									'maxlength'   => 255,
									'placeholder' => 'Confirm password',
									'required'    => true,
								])
							}}
						</div>

						<div class="form-group">
							<button type="submit" class="btn btn-primary center-block"><i class="fa fa-save"></i> Reset Password</button>
						</div>
					</div>
				</div>

			{{ Form::close() }}

		</div>

	</div>

@endsection