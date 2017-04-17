@foreach ($products as $product)
    <div class="row product-listing" style="margin-bottom: 20px;">
        <div class="col-md-3">
            @if ($product->defaultPhoto() && $product->defaultPhoto()->count() > 0)
                <a href="{{ route('shop.product', [$product['uri'], $product['id']]) }}" style="display: inline-block; position: relative;" title="View this item">
                    @if ($product['new'])
                        <div class="ribbon-wrapper">
                            <div class="ribbon">NEW</div>
                        </div>
                    @endif

                    <img class="img-thumbnail" src="{!! CustomHelper::image($product->defaultPhoto['name'], true) !!}" alt="{!! $product['name'] !!}" class="image" />
                </a>
            @endif
        </div>
        <div class="col-md-9">
            <p class="title">{{ Html::link(route('shop.product', [$product['uri'], $product['id']]), $product['name'], ['title' => 'View this item']) }}</p>

            @if (!$product->inStock())
                <p class="label label-danger pull-left">Out of Stock</p>
            @endif

            <p class="price pull-right">
                @if ($product['special'])
                    <span class="price-old">${{ money_format('%i', $product['old_price']) }}</span>
                @endif

                ${{ money_format('%i', $product['price']) }}
            </p>

            <?php
                $availableOptions = $product->availableOptions();
            ?>

            @if (count($availableOptions) > 0)
                <ul class="list-unstyled">
                    @foreach ($availableOptions as $attribute)
                        <li>
                            {{ $attribute['name'] }}: {{ collect($attribute['options'])->pluck('name')->implode(', ') }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
@endforeach