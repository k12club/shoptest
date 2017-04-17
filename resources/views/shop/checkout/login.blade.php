@if (!auth()->check())
    <div class="row">
        <div class="col-md-3">
            <h5 style="line-height: normal; margin-top: 0;">Login</h5>
        </div>
        <div class="col-md-9">
            <label style="font-weight: normal;">
                {{ Form::radio('login_choice', 'guest', true) }}

                Checkout as Guest
            </label><br />

            <label style="font-weight: normal;">
                {{ Form::radio('login_choice', 'login') }}

                Login
            </label>

            {{ Form::open(['route' => ['shop.checkout.login'], 'id' => 'login-box', 'class' => 'material hide']) }}

            <div class="form-group">
                {{ Form::label('email', 'Email:', ['class' => 'control-label required']) }}

                {{
                    Form::email( 'email', '', [
                        'class'       => 'form-control',
                        'id'          => 'email',
                        'maxlength'   => 255,
                        'placeholder' => 'Your email address',
                        'required'    => true,
                    ])
                }}
            </div>

            <div class="form-group">
                {{ Form::label('password', 'Password:', ['class' => 'control-label required']) }}

                {{
                    Form::input( 'password', 'password', '', [
                        'class'     => 'form-control',
                        'id'        => 'password',
                        'maxlength' => 255,
                        'required'   => true,
                    ])
                }}
            </div>

            <p>
                <button class="btn btn-primary" type="submit">Login</button>
                {{ Html::link(url('password/reset'), 'Forgot Your Password?', ['class' => 'btn btn-link']) }}<br />
            </p>

            <p>
                Don't have an account yet? {{ Html::link(url('register'), 'Register', ['title' => 'Register a new account']) }}
            </p>

            {{ Form::close() }}

            <hr class="separator" /><br />
        </div>
    </div>
@endif

@section('bottom_block')
    @parent
    <script>
        $( document ).ready(function() {
            $('input[name="login_choice"]').click(function(){
                $('#login-box').toggleClass('hide', $(this).val() != 'login');
            });
        });
    </script>
@endsection