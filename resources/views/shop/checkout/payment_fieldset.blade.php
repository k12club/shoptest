<div class="row">
    <div class="col-md-12">
        <div class="payment-errors alert alert-danger hide"></div>
    </div>
</div>

<fieldset id="payment-fieldset">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{
                    Form::text( 'payment[cc_number]', '', [
                        'class'       => 'form-control',
                        'data-stripe' => 'number',
                        'id'          => 'payment-cc-number',
                        'maxlength'   => 20,
                        'placeholder' => 'Credit card number',
                        'required'    => true,
                    ])
                }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <div class="col-xs-6 col-lg-6 pl-zero">
                    {{
                        Form::text( 'payment[cc_expiry_month]', '', [
                        'class'       => 'form-control',
                        'data-stripe' => 'exp-month',
                        'id'          => 'payment-cc-expiry-month',
                        'placeholder' => 'MM',
                        'required'    => true,
                        ])
                    }}
                </div>

                <div class="col-xs-6 col-lg-6 pl-zero">
                    {{
                        Form::text( 'payment[cc_expiry_year]', '', [
                            'class'       => 'form-control',
                            'data-stripe' => 'exp-year',
                            'id'          => 'payment-cc-expiry-year',
                            'placeholder' => 'YY',
                            'required'    => true,
                        ])
                    }}
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{
                    Form::text( 'payment[cc_cvc]', '', [
                        'class'       => 'form-control',
                        'data-stripe' => 'cvc',
                        'id'          => 'payment-cc-cvc',
                        'placeholder' => 'CVC',
                        'required'    => true,
                    ])
                }}
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{
                    Form::text( 'payment[cc_name]', '', [
                        'class'       => 'form-control',
                        'data-stripe' => 'name',
                        'id'          => 'payment-cc-name',
                        'maxlength'   => 100,
                        'placeholder' => 'Name on card',
                        'required'    => true,
                    ])
                }}
            </div>
        </div>
    </div>
</fieldset>