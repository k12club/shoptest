@extends('layouts.master')

@section('title', 'Edit payment method - ' .config('app.name'))

@section('content')

    <div class="row content">

        <div class="col-md-12">
            <!-- Nav tabs -->
            @include('users.tabs')

            <ol class="breadcrumb">
                <li>{{ Html::link(route('payments'), 'Payment Methods', ['role' => 'tab']) }}</li>
                <li class="active">Edit payment method</li>
            </ol>

            <h1>Edit Credit Card</h1>

            @include('snippets.errors')
            @include('snippets.flash')

            {{ Form::open(['route' => ['payments.update', $paymentSource['id']]]) }}

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group{{ $errors->has('default') ? ' has-error' : '' }}">
                        {{ Form::label(null, 'Primary Card?', ['class' => 'control-label']) }}
                        &nbsp;&nbsp;&nbsp;
                        <label style="color: #666666; font-size: 14px; font-weight: normal;">
                            {{ Form::checkbox('default', true, old('default', $paymentSource['default'])) }}

                            Yes
                        </label>

                        <p class="help-block">
                            Check "Yes" to make this card as your primary card for payments when you make purchases with us.
                            Doing so will automatically make other cards, if any, non-primary.
                        </p>

                        @include('snippets.errors_first', ['param' => 'default'])
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success" title="Update this address"><i class="fa fa-save"></i> Update</button>
                </div>
            </div>

            {{ Form::close() }}

            <div class="row">
                <div class="col-md-12">
                    <br />
                    <label>Remove this card?</label>

                    {{ Form::open(['route' => ['payments.remove', $paymentSource['id']], 'method' => 'DELETE', 'onsubmit' => 'return confirm("Do you really want to remove this card?");']) }}
                        <button type="submit" class="btn btn-danger" title="Remove this card"><i class="fa fa-times"></i> Remove</button>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

    </div>

@endsection