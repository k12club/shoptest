@foreach ($products as $product)
    <div class="item">
        <div class="product-col">
            @if ($product->defaultPhoto() && $product->defaultPhoto()->count() > 0)
                <div class="image">
                    <a href="{{ route('shop.product', [$product['uri'], $product['id']]) }}" title="View this item">
                        @if ($product['new'])
                            <div class="ribbon-wrapper">
                                <div class="ribbon">NEW</div>
                            </div>
                        @endif

                        <img class="image" src="{!! CustomHelper::image($product->defaultPhoto['name'], true) !!}" alt="{!! $product['name'] !!}" class="img-responsive" />
                    </a>
                </div>
            @endif

            <div class="caption">
                <h4>
                    {{ Html::link(route('shop.product', [$product['uri'], $product['id']]), $product['name'], ['title' => 'View this item']) }}
                </h4>

                <div class="price">
                    <span class="price-new">${{ money_format('%i', $product['price']) }}</span>

                    @if ($product['old_price'] > 0 && $product['old_price'] > $product['price'])
                        <span class="price-old">${{ money_format('%i', $product['old_price']) }}</span>
                    @endif
                </div>

                @if (!$product->inStock())
                    <p class="label label-danger pull-left">Out of Stock</p>
                @endif
            </div>
        </div>
    </div>
@endforeach