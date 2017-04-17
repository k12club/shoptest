@if (count($cartItems) > 0)
    <table class="basket-table">
        <thead>
        <tr class="row">
            <th class="col-md-8">Item</th>
            <th class="col-md-2">Quantity</th>
            <th class="col-md-2 text-right">Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($cartItems as $cartItem)
            @if (!empty($cartItem['inventory_item']))
                <tr class="row" id="item-{{ $cartItem['inventory_id'] }}" data-price="{{ $cartItem['price'] }}">
                    <td class="col-md-8">
                        @if ($cartItem['inventory_item']->product->defaultPhoto()->count() > 0)
                            <img class="img-thumbnail pull-left" src="{!! CustomHelper::image($cartItem['inventory_item']->product->defaultPhoto['name'], true) !!}" alt="{!! $cartItem['inventory_item']->product['name'] !!}" style="margin-right: 20px;" />
                        @endif

                        {{ Html::link(route('shop.product', [$cartItem['inventory_item']->product['uri'], $cartItem['inventory_item']->product['id']]), $cartItem['inventory_item']->product['name'], ['title' => 'View this item']) }}<br />

                        @if ($cartItem['inventory_item']->options()->count() > 0)
                            <ul class="list-unstyled">
                                @foreach ($cartItem['inventory_item']->options()->get() as $option)
                                    <li>{!! $option->attribute['name'] !!}: {!! $option['name'] !!}</li>
                                @endforeach
                            </ul>
                        @endif

                        <p class="text-muted" style="margin-top: 10px;">${{ money_format('%i', $cartItem['price']) }}</p>
                    </td>
                    <td class="col-md-2">
                        <div class="form-inline">
                            {{
                                Form::select('number', range(0, $cartItem['stock']), $cartItem['quantity'], [
                                    'class' => 'form-control text-right',
                                    'id'    => 'stock',
                                    'style' => 'width: 100px;'
                                ])
                            }}

                            <a href="javascript:void(0);" title="Remove this item" class="remove-btn"><i class="fa fa-times"></i></a>
                        </div>
                    </td>
                    <td class="col-md-2 text-right">$<span class="item-total">{{ money_format('%i', $cartItem['price'] * $cartItem['quantity']) }}</span></td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-warning">Your cart is currently empty, please add some items to cart first.</div>
@endif