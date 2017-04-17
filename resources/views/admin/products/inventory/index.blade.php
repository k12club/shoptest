<h1>Inventory ({{ count($inventoryItems) }})</h1>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <tr>
            <th class="text-center">SKU</th>
            <th class="text-center">Options</th>
            <th class="text-center">Price</th>
            <th class="text-center">Stock</th>
            <th class="text-center">Edit</th>
            <th class="text-center">Remove</th>
        </tr>
        @foreach ($inventoryItems as $inventoryItem)
            <tr>
                <td>{{ $inventoryItem['sku'] }}</td>
                <td>
                    @if ($inventoryItem->options() && $inventoryItem->options()->count() > 0)
                        <ul>
                            @foreach ($inventoryItem->options()->get() as $inventoryOption)
                                <li>{{ $inventoryOption->attribute['name'] }}: {{ $inventoryOption['name'] }}</li>
                            @endforeach
                        </ul>
                    @endif
                </td>
                <td class="text-right">${{ number_format($inventoryItem['price'] == 0 ? $inventoryItem->product['price'] : ($inventoryItem->product['price'] + $inventoryItem['price']), 2) }}</td>
                <td class="text-right">
                    @if ($inventoryItem['stock'] == 0)
                        <span class="label label-danger">Out of Stock</span>
                    @else
                        {{ $inventoryItem['stock'] }}
                    @endif
                </td>
                <td class="text-center"><a href="{{ route('admin.products.product.inventory.inventory', [$inventoryItem->product['id'], $inventoryItem['id']]) }}" class="btn btn-sm btn-primary"><i class="fa fa-pencil"></i> Edit</a></td>
                <td class="text-center">
                    {{ Form::open(['route' => ['admin.products.product.inventory.remove', $inventoryItem->product['id'], $inventoryItem['id']], 'method' => 'DELETE', 'onsubmit' => 'return confirm("Do you really want to remove this inventory item?");']) }}

                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> Remove</button>

                    {{ Form::close() }}
                </td>
            </tr>
        @endforeach
    </table>
</div>

<a href="{{ route('admin.products.product.inventory.create', $product['id']) }}" class="btn btn-sm btn-success" title="Add a new inventory item to this product"><i class="fa fa-plus"></i> Add new Product Inventory</a><br /><br />