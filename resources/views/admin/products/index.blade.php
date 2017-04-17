@extends('layouts.admin')

@section('title', 'Admin - Manage Products - ' . config('app.name'))

@section('content')

    <div class="row">

        <div class="col-md-12">

            <h1>Products ({{ $products->count() }})</h1>

            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <th class="text-center">Category</th>
                        <th class="text-center">Order</th>
                        <th class="text-center">Product</th>
                        <th class="text-center">Inventory Items</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Old Price</th>
                        <th class="text-center">Edit</th>
                        <th class="text-center">Remove</th>
                    </tr>
                    <?php $categoryId = 0; ?>
                    @foreach ($products as $product)
                        <tr>
                            @if ($product['category_id'] != $categoryId)
                                <td rowspan="{{ $product->category->products()->count() }}">{{ Html::link(route('admin.categories.category', $product['category_id']), collect($categories->toArray())->keyBy('id')[$product['category_id']]['name']) }}</td>

                                <?php $categoryId = $product['category_id']; ?>
                            @endif
                            <td class="text-right">{{ $product['order'] }}</td>
                            <td>
                                @if ($product->defaultPhoto()->count() > 0)
                                    <img src="{{ CustomHelper::image($product->defaultPhoto['name'], true) }}" alt="{!! $product['name'] !!}" width="50px" />
                                @endif

                                {{ Html::link(route('admin.products.product', $product['id']), $product['name']) }}
                                &middot;
                                {{ Html::link(route('shop.product', [$product['uri'], $product['id']]), '', ['target' => '_blank', 'class' => 'fa fa-external-link']) }}

                                @if ($product['new'])
                                    &middot; <span class="label label-success">New</span>
                                @endif

                                @if ($product['special'])
                                    &middot; <span class="label label-primary">Special</span>
                                @endif

                                @if (!$product->inStock())
                                    &middot; <span class="label label-danger">Out of Stock</span>
                                @elseif ($product->hasOutOfStock())
                                    &middot; <span class="label label-warning">Inventory Out of Stock</span>
                                @endif
                            </td>
                            <td class="text-right">{{ $product->inventoryItems()->count() }}</td>
                            <td class="text-right">${{ number_format($product['price'], 2) }}</td>
                            <td class="text-right text-muted">
                                @if ($product['old_price'] > 0)
                                    <del>${{ number_format($product['old_price'], 2) }}</del>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="text-center"><a href="{{ route('admin.products.product', $product['id']) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a></td>
                            <td class="text-center">
                                {{ Form::open(['route' => ['admin.products.remove', $product['id']], 'method' => 'DELETE', 'onsubmit' => 'return confirm("Do you really want to remove this product?");']) }}
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Remove</button>
                                {{ Form::close() }}
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>

            <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Add new Product</a><br /><br />

        </div>

    </div>

@endsection