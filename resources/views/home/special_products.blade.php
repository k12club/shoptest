<!-- Special Products Starts -->
@if (count($specialProducts) > 0)
    <h3 class="side-heading">Special Deals</h3>

    <ul class="side-products-list">
        @foreach ($specialProducts as $product)
            <li class="clearfix">
                @if ($product->defaultPhoto() && $product->defaultPhoto()->count() > 0)
                    <a href="{{ route('shop.product', [$product['uri'], $product['id']]) }}" title="View this item">
                        <img src="{!! CustomHelper::image($product->defaultPhoto['name'], true) !!}" class="img-responsive" alt="Special product - {!! $product['name'] !!}" />
                    </a>
                @endif

                <h5>{{ Html::link(route('shop.product', [$product['uri'], $product['id']]), $product['name'], ['title' => 'View this item']) }}</h5>

                <div class="price">
                    <span class="price-new">${{ money_format('%i', $product['price']) }}</span>

                    @if ($product['old_price'] > 0 && $product['old_price'] > $product['price'])
                        <span class="price-old">${{ money_format('%i', $product['old_price']) }}</span>
                    @endif
                </div>
            </li>
        @endforeach
    </ul>
@endif