@extends('layouts.admin')

@section('title', 'Admin - Out of Stock Inventory Items - ' . config('app.name'))

@section('content')

    <div class="row">

        <div class="col-md-12">

            <h1>Out of Stock Inventory Items</h1>

            @include('admin.products.inventory.index', ['inventoryItems' => $outOfStockItems])

        </div>

    </div>

@endsection