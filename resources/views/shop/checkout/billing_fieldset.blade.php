<fieldset id="billing-fieldset">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('billing.name') ? ' has-error' : '' }}">
                {{ Form::label('billing-name', 'Name:', ['class' => 'control-label required']) }}

                {{
                    Form::text('billing[name]', old('billing[name]'), [
                        'class'     => 'form-control',
                        'id'        => 'billing-name',
                        'maxlength' => 255,
                        'required'  => true,
                    ])
                }}

                @include('snippets.errors_first', ['param' => 'billing.name'])
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('billing.phone') ? ' has-error' : '' }}">
                {{ Form::label('billing-phone', 'Phone:', ['class' => 'control-label required']) }}

                {{
                    Form::text('billing[phone]', old('billing[phone]'), [
                        'class'       => 'form-control',
                        'id'          => 'billing-phone',
                        'maxlength'   => 20,
                        'placeholder' => '(XXX) XXX - XXXX',
                        'required'    => true,
                    ])
                }}

                @include('snippets.errors_first', ['param' => 'billing.phone'])
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('billing.address_1') ? ' has-error' : '' }}">
                {{ Form::label('billing-address-1', 'Address:', ['class' => 'control-label required']) }}

                {{
                    Form::text('billing[address_1]', old('billing[address_1]'), [
                        'class'     => 'form-control',
                        'id'        => 'billing-address-1',
                        'maxlength' => 255,
                        'required'  => true,
                    ])
                }}

                @include('snippets.errors_first', ['param' => 'billing.address_1'])
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('billing.address_2') ? ' has-error' : '' }}">
                {{ Form::label('billing-address-2', 'Address 2:', ['class' => 'control-label']) }}

                {{
                    Form::text('billing[address_2]', old('billing[address_2]'), [
                        'class'     => 'form-control',
                        'id'        => 'billing-address-2',
                        'maxlength' => 255,
                    ])
                }}

                @include('snippets.errors_first', ['param' => 'billing.address_2'])
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('billing.city') ? ' has-error' : '' }}">
                {{ Form::label('billing-city', 'City:', ['class' => 'control-label required']) }}

                {{
                    Form::text('billing[city]', old('billing[city]'), [
                        'class'     => 'form-control',
                        'id'        => 'billing-city',
                        'maxlength' => 255,
                        'required'  => true,
                    ])
                }}

                @include('snippets.errors_first', ['param' => 'billing.city'])
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('billing.state') ? ' has-error' : '' }}">
                {{ Form::label('billing-state', 'State:', ['class' => 'control-label required']) }}

                {{
                    Form::select('billing[state]', config('custom.states'), old('billing[state]'), [
                        'class'    => 'form-control',
                        'id'       => 'billing-state',
                        'required' => true,
                    ])
                }}

                @include('snippets.errors_first', ['param' => 'billing.state'])
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('billing.zipcode') ? ' has-error' : '' }}">
                {{ Form::label('billing-zipcode', 'Postal Code:', ['class' => 'control-label required']) }}

                {{
                    Form::text('billing[zipcode]', old('billing[zipcode]'), [
                        'class'     => 'form-control',
                        'id'        => 'billing-zipcode',
                        'maxlength' => 10,
                        'required'  => true,
                    ])
                }}

                @include('snippets.errors_first', ['param' => 'billing.zipcode'])
            </div>
        </div>
    </div>
</fieldset>