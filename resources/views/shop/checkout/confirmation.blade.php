@extends('layouts.master')

@section('title', 'Shop - Check Out - Confirmation - ' . config('app.name'))

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success">
                Thank you for shopping with us. You have successfully placed the order #<strong>{{ $orderNumber }}</strong><br /><br />

                Your Confirmation Code is: <strong>{{ $confirmationCode }}</strong><br /><br />

                Please print or save your Order Number and Confirmation Code for a reference.
            </div>
        </div>
    </div>

@endsection