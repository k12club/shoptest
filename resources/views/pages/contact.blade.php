@extends('layouts.master')

@section('title', 'Contact Us - ' . config('app.name'))

@section('bottom_block')
    <script>
        (function($) {

            $('#form-contact').submit(function(event) {
                event.preventDefault();

                var _token = $('input[name="_token"]').val(),
                    name = $('input[name="name"]').val(),
                    email = $('input[name="email"]').val(),
                    phone = $('input[name="phone"]').val(),
                    message = $('textarea[name="message"]').val();

                $('input[type="submit"]').val('Sending...');

                $.ajax({
                    url: "/contact",
                    type: "POST",
                    data: {
                        _token: _token,
                        name: name,
                        email: email,
                        phone: phone,
                        message: message
                    },
                    cache: false,
                    success: function() {
                        // Success message
                        $('#success').html("<div class='alert alert-success'>");
                        $('#success > .alert-success').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                                .append("</button>");
                        $('#success > .alert-success')
                                .append("<strong>Your message has been sent. </strong>");
                        $('#success > .alert-success')
                                .append('</div>');

                        $('#form-contact').hide();
                    },
                    error: function() {
                        // Fail message
                        $('#success').html("<div class='alert alert-danger'>");
                        $('#success > .alert-danger').html("<button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;")
                                .append("</button>");
                        $('#success > .alert-danger').append("<strong>Sorry " + name + ", it seems that my mail server is not responding. Please try again later!");
                        $('#success > .alert-danger').append('</div>');

                        $('#form-contact').hide();
                    }
                });
            });

        })(jQuery);
    </script>
@stop

@section('content')

    <div class="row">

        <div class="col-md-6">

            <div class="checkout-box">

                <h2>Contact Us</h2>

                <p>If you would like to send us an email please fill out the form below.</p>

                <div id="success"></div>

                {!! Form::open(['action' => 'PagesController@contactSend', 'id' => 'form-contact']) !!}

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name" class="control-label required">Name:</label>
                            {!! Form::text( 'name', '', [
                                'placeholder' => 'Your name',
                                'maxlength' => 100,
                                'required' => true,
                                'class' => 'form-control',
                            ] ) !!}
                            <p class="help-block text-danger"></p>
                        </div>

                        <div class="form-group">
                            <label for="email" class="control-label required">Email:</label>
                            {!! Form::email( 'email', '', [
                                'placeholder' => 'Your email address',
                                'maxlength' => 100,
                                'required' => true,
                                'class' => 'form-control',
                                'type' => 'email'
                            ] ) !!}
                            <p class="help-block text-danger"></p>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="control-label">Phone:</label>
                            {!! Form::text( 'phone', '', [
                                'placeholder' => 'Your phone number',
                                'maxlength' => 20,
                                'class' => 'form-control',
                            ] ) !!}
                            <p class="help-block text-danger"></p>
                        </div>

                        <div class="form-group">
                            <label for="email" class="control-label required">Message:</label>
                            {!! Form::textarea( 'message', '', [
                                'placeholder' => 'Message',
                                'maxlength' => 2000,
                                'required' => true,
                                'class' => 'form-control',
                            ] ) !!}
                            <p class="help-block text-danger"></p>
                        </div>
                    </div>

                    <div class="clearfix"></div>
                    <div class="col-md-6 col-md-offset-3">
                        {!! Form::submit( 'Send', [
                            'class' => 'btn btn-warning',
                        ] ) !!}
                    </div>
                </div>

                {!! Form::close() !!}

            </div>

        </div>

        <div class="col-md-6">

            <div class="checkout-box">

                <h2>Where Are We?</h2>

                <p class="address">
                    200 N Spring St<br />
                    Los Angeles, CA. 90012
                </p>

                <iframe width="100%" height="100%" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="http://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;q=200+N+Spring+St,+Los Angeles,+CA200 N Spring St Los Angeles, CA. 90012&amp;t=m&amp;hq=&amp;output=embed"></iframe>

                <h2>Or Call Us</h2>

                <button onclick="window.open('tel:7141234567');" class="btn btn-warning"><i class="fa fa-phone"></i> &nbsp;<strong>(714) 123 - 4567</strong></button>

            </div>

        </div>

    </div>

@endsection