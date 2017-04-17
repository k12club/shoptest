@if (!auth()->check())
    <div class="row">
        <div class="col-md-3">
            <h5 style="line-height: normal; margin-top: 0;">Contact</h5>
        </div>
        <div class="col-md-9">
            <div class="form-group {{ $errors->has('contact_email') ? ' has-error' : '' }}">
                {{ Form::label('contact-email', 'Contact Email:', ['class' => 'control-label required']) }}

                {{
                    Form::email('contact_email', old('contact_email'), [
                        'class'     => 'form-control',
                        'id'        => 'contact-email',
                        'maxlength' => 255,
                        'required'  => true,
                    ])
                }}

                @include('snippets.errors_first', ['param' => 'contact_email'])
            </div>

            <hr class="separator" /><br />
        </div>
    </div>
@endif