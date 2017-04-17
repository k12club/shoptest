@extends('layouts.master')

@section('title', 'Reset Password - ' . config('app.name'))

@section('content')

	<div class="row content">
		<div class="col-md-6 col-md-offset-3">

			<h2>Reset Password</h2>

			@include('snippets.errors')
			@include('snippets.flash')

			{{ Form::open(['url' => 'password/email', 'role' => 'form']) }}

				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
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
						</div>

						<div class="form-group">
							<button type="submit" class="btn btn-primary center-block"><i class="fa fa-envelope-o"></i> Send Password Reset Link</button>
						</div>
					</div>
				</div>

			{{ Form::close() }}

		</div>
	</div>

@endsection