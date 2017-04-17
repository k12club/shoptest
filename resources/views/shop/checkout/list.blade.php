@if (count($cartItems) > 0)
    <table class="basket-table">
        <thead>
        <tr>
            <th colspan="2">Item</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($cartItems as $cartItem)
            <?php
                $item = $items[$cartItem['id']];
                if (!empty($item['photos']) && !empty($item['photos'][0])) {
                    $item['thumbnail'] = CustomHelper::image($item['photos'][0]['name'], true);
                }

                $inventoryItem = $inventoryItems[$cartItem['sku']];
            ?>
            <tr id="cart-item-{!! $cartItem['sku'] !!}" data-price="{!! $cartItem['price'] !!}">
                <td class="image">
                    @if (!empty($item['thumbnail']))
                        <a href="{!! url(action('ShopController@show', ['uri' => $item['uri'], 'id' => $item['id']])) !!}"><img class="image" src="{!! $item['thumbnail'] !!}" alt="{!! $item['name'] !!}" /></a>
                    @endif
                </td>
                <td class="title">
                    <a href="{!! url(action('ShopController@show', ['uri' => $item['uri'], 'id' => $item['id']])) !!}">{!! $item['name'] !!}</a>

                    @if (!empty($inventoryItem['options']))
                        <?php
                            $inventoryItemOptions = !empty($inventoryItem['options']) ? json_decode($inventoryItem['options'], true) : [];
                        ?>
                        <ul>
                            @foreach($inventoryItemOptions as $attributeId => $optionIds)
                                <li>
                                    {!! $attributes[$attributeId] !!}:
                                    <?php
                                    $optionValues = [];
                                    foreach($optionIds as $optionId) {
                                        foreach($options[$attributeId] as $option) {
                                            if ($option['id'] == $optionId && $option['attribute_id'] == $attributeId) {
                                                $optionValues[] = $option['value'];
                                                break;
                                            }
                                        }
                                    }
                                    if (count($optionValues) > 0) {
                                        echo implode(', ', $optionValues);
                                    }
                                    ?>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </td>
                <td class="qty">{!! $cartItem['count'] !!}</td>
                <td class="price">${!! money_format('%i', $cartItem['price']) !!}</td>
                <td class="total">${!! money_format('%i', $cartItem['price'] * $cartItem['count']) !!}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-warning">Your cart is currently empty, please add some items to cart first.</div>
@endif