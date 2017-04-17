@extends('layouts.admin')

@section('title', 'Admin - Edit Product Attribute Option - ' . config('app.name'))

@section('content')

    <div class="row">

        <div class="col-md-5">

            <h2>Edit Option</h2>

            @include('snippets.errors')
            @include('snippets.flash')

            {{ Form::open(['route' => ['admin.attributes.attribute.options.update', $attribute['id'], $option['id']], 'role' => 'form']) }}

            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {{ Form::label('name', 'Option:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('name', old('name', $option['name']), [
                                'class'       => 'form-control',
                                'id'          => 'name',
                                'maxlength'   => 45,
                                'placeholder' => 'Option',
                                'required'    => true,
                            ])
                        }}

                        @include('snippets.errors_first', ['param' => 'name'])
                    </div>

                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                        {{ Form::label('order', 'Order:', ['class' => 'control-label required']) }}

                        {!!
                            Form::input('number', 'order', old('order', $option['order']), [
                                'class'       => 'form-control',
                                'id'          => 'order',
                                'maxlength'   => 10,
                                'placeholder' => '1',
                                'required'    => true,
                            ])
                        !!}

                        <p class="help-block">Set the order of this option.</p>

                        @include('snippets.errors_first', ['param' => 'order'])
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Update</button>

                        {{ Html::link(route('admin.attributes.attribute', $attribute['id']), 'Cancel', ['class' => 'btn', 'title' => 'Cancel']) }}
                    </div>
                </div>

            </div>

            {{ Form::close() }}

        </div>

        <div class="col-md-7">

            <h2>Associated Products</h2>

            @if (count($option->products()) > 0)
                <table class="table table-striped">
                    <tr>
                        <th>Product</th>
                        <th>Associated Inventory Items</th>
                        <th>Edit</th>
                    </tr>
                    @foreach ($option->products() as $product)
                        <tr>
                            <td>
                                {{ Html::link(route('admin.products.product', $product['id']), $product['name']) }}
                            </td>
                            <td>
                                {{ $option->inventoryItems()->count() }}
                            </td>
                            <td><a href="{{ route('admin.products.product', $product['id']) }}" class="btn btn-sm btn-primary" title="Edit this product"><i class="fa fa-pencil"></i> Edit</a></td>
                        </tr>
                    @endforeach
                </table>
            @else
                <div class="alert alert-warning">There are currently no products using this option.</div>
            @endif
        </div>

    </div>

@endsection