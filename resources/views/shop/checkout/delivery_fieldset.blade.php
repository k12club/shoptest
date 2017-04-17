<fieldset id="delivery-fieldset">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('delivery.name') ? ' has-error' : '' }}">
                {{ Form::label('delivery-name', 'Name:', ['class' => 'control-label required']) }}

                {{
                    Form::text('delivery[name]', old('delivery[name]'), [
                        'class'     => 'form-control',
                        'id'        => 'delivery-name',
                        'maxlength' => 255,
                        'required'  => true,
                    ])
                }}

                @include('snippets.errors_first', ['param' => 'delivery.name'])
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('delivery.phone') ? ' has-error' : '' }}">
                {{ Form::label('delivery-phone', 'Phone:', ['class' => 'control-label required']) }}

                {{
                    Form::text('delivery[phone]', old('delivery[phone]'), [
                        'class'       => 'form-control',
                        'id'          => 'delivery-phone',
                        'maxlength'   => 20,
                        'placeholder' => '(XXX) XXX - XXXX',
                        'required'    => true,
                    ])
                }}

                @include('snippets.errors_first', ['param' => 'delivery.phone'])
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('delivery.address_1') ? ' has-error' : '' }}">
                {{ Form::label('delivery-address-1', 'Address:', ['class' => 'control-label required']) }}

                {{
                    Form::text('delivery[address_1]', old('delivery[address_1]'), [
                        'class'     => 'form-control',
                        'id'        => 'delivery-address-1',
                        'maxlength' => 255,
                        'required'  => true,
                    ])
                }}

                @include('snippets.errors_first', ['param' => 'delivery.address_1'])
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group{{ $errors->has('delivery.address_2') ? ' has-error' : '' }}">
                {{ Form::label('delivery-address-2', 'Address 2:', ['class' => 'control-label']) }}

                {{
                    Form::text('delivery[address_2]', old('delivery[address_2]'), [
                        'class'     => 'form-control',
                        'id'        => 'delivery-address-2',
                        'maxlength' => 255,
                    ])
                }}

                @include('snippets.errors_first', ['param' => 'delivery.address_2'])
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('delivery.city') ? ' has-error' : '' }}">
                {{ Form::label('delivery-city', 'City:', ['class' => 'control-label required']) }}

                {{
                    Form::text('delivery[city]', old('delivery[city]'), [
                        'class'     => 'form-control',
                        'id'        => 'delivery-city',
                        'maxlength' => 255,
                        'required'  => true,
                    ])
                }}

                @include('snippets.errors_first', ['param' => 'delivery.city'])
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('delivery.state') ? ' has-error' : '' }}">
                {{ Form::label('delivery-state', 'State:', ['class' => 'control-label required']) }}

                {{
                    Form::select('delivery[state]', config('custom.states'), old('delivery[state]'), [
                        'class'    => 'form-control',
                        'id'       => 'delivery-state',
                        'required' => true,
                    ])
                }}

                @include('snippets.errors_first', ['param' => 'delivery.state'])
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group{{ $errors->has('delivery.zipcode') ? ' has-error' : '' }}">
                {{ Form::label('delivery-zipcode', 'Postal Code:', ['class' => 'control-label required']) }}

                {{
                    Form::text('delivery[zipcode]', old('delivery[zipcode]'), [
                        'class'     => 'form-control',
                        'id'        => 'delivery-zipcode',
                        'maxlength' => 10,
                        'required'  => true,
                    ])
                }}

                @include('snippets.errors_first', ['param' => 'delivery.zipcode'])
            </div>
        </div>
    </div>
</fieldset>