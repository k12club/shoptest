@extends('layouts.admin')

@section('title', 'Admin - Edit Product Inventory Item - ' . config('app.name'))

@section('content')

    <div class="row">

        <div class="col-md-12">

            <h2>Admin - Edit Product Inventory Item</h2>

            @include('snippets.errors')
            @include('snippets.flash')

            {{ Form::open(['route' => ['admin.products.product.inventory.update', $product['id'], $inventoryItem['id']], 'role' => 'form']) }}

            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="form-group{{ $errors->has('sku') ? ' has-error' : '' }}">
                        {{ Form::label('sku', 'SKU:', ['class' => 'control-label required']) }}

                        {{
                            Form::text('sku', $inventoryItem['sku'], [
                                'class'       => 'form-control',
                                'id'          => 'sku',
                                'maxlength'   => 100,
                                'placeholder' => 'SKU',
                                'required'    => true,
                            ])
                        }}

                        <p class="help-block">
                            A unique code name for each inventory item. Convention: (capital product name with option keys,
                            separated by underscores).<br />

                            For example, if the product name is Single Lashes, and it has 3 options with the following option
                            keys: J, 015, 11, then the suggested SKU value is <strong>SL_J_015_11</strong>.
                        </p>

                        @include('snippets.errors_first', ['param' => 'sku'])
                    </div>

                    <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
                        {{ Form::label('price', 'Price Difference:', ['class' => 'control-label required']) }}

                        {{
                            Form::input('number', 'price', number_format(old('price', $inventoryItem['price']), 2), [
                                'class'       => 'form-control',
                                'id'          => 'price',
                                'maxlength'   => 6,
                                'placeholder' => '0.00',
                                'step'        => 0.01,
                                'required'    => true,
                            ])
                        }}

                        <p class="help-block">
                            Set this value if you want this item to have different price (in dollars) from its product's price (${{ money_format('%i', $inventoryItem->product['price']) }}).<br />
                            For example, if the product's price is ${{ money_format('%i', $inventoryItem->product['price']) }}, then putting a value of <strong>1.50</strong> will make this item's price as <strong>${{ money_format('%i', ($inventoryItem->product['price'] + 1.50)) }}</strong>,<br />
                            or <strong>-1.00</strong> will make this item's price as <strong>${{ money_format('%i', ($inventoryItem->product['price'] - 1.00)) }}</strong><br />
                            Leave this field with value <strong>0.00</strong> will make this item has the same price as its product's price (${{ money_format('%i', $inventoryItem->product['price']) }}).
                        </p>

                        @include('snippets.errors_first', ['param' => 'price'])
                    </div>

                    <div class="form-group{{ $errors->has('stock') ? ' has-error' : '' }}">
                        {{ Form::label('stock', 'Stock:', ['class' => 'control-label required']) }}

                        {{
                            Form::input('number', 'stock', $inventoryItem['stock'], [
                                'class'       => 'form-control',
                                'id'          => 'stock',
                                'maxlength'   => 10,
                                'placeholder' => '100',
                                'required'    => true,
                            ])
                        }}

                        <p class="help-block">How many of this item (with the selected options) are in your inventory.</p>

                        @include('snippets.errors_first', ['param' => 'stock'])
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" title="Update this inventory item"><i class="fa fa-save"></i> Update</button>

                        {{ Html::link(route('admin.products.product', $inventoryItem->product['id']), 'Cancel', ['class' => 'btn']) }}
                    </div>
                </div>

                <div class="col-sm-12 col-md-6">
                    <div class="form-group">
                        {{ Form::label('options', 'Options:', ['class' => 'control-label']) }}

                        <br />
                        @foreach ($attributes as $attribute)
                            @if ($attribute->options() && $attribute->options()->count() > 0)
                                <strong>{{ $attribute['name'] }}</strong>

                                @foreach ($attribute->options as $option)
                                    <div class="checkbox">
                                        <label>
                                            {{ Form::checkbox('options[]',
                                                $option['id'],
                                                in_array($option['id'], collect($inventoryItem->options()->get()->toArray())->pluck('id')->all())
                                            ) }}

                                            {{ $option['name'] }}
                                        </label>
                                    </div>
                                @endforeach
                            @endif
                        @endforeach
                    </div>
                </div>

            </div>

            {{ Form::close() }}

        </div>

    </div>

@endsection