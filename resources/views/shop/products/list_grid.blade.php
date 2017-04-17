<?php
    $row = 0;
    $rows = [];
    for ($i = 0; $i < count($products); $i++) {
        $rows[$row][] = $products[$i];
        if (($i + 1) % 4 == 0) {
            $row++;
        }
    }
?>

@foreach ($rows as $row)
    <div class="row">
        @foreach($row as $product)
            <div class="col-md-3 col-sm-6 col-xs-12 product-listing">

                <p class="title">{{ Html::link(route('shop.product', [$product['uri'], $product['id']]), $product['name'], ['title' => 'View this item']) }}</p>

                @if ($product->defaultPhoto() && $product->defaultPhoto()->count() > 0)
                    <a href="{{ route('shop.product', [$product['uri'], $product['id']]) }}" class="thumbnail-link" title="View this item">
                        @if ($product['new'])
                            <div class="ribbon-wrapper">
                                <div class="ribbon">NEW</div>
                            </div>
                        @endif

                        <img class="img-thumbnail image" src="{!! CustomHelper::image($product->defaultPhoto['name'], true) !!}" alt="{!! $product['name'] !!}" class="image" />
                    </a>
                @endif

                @if (!$product->inStock())
                    <p class="label label-danger pull-left">Out of Stock</p>
                @endif

                <p class="price pull-right">
                    @if ($product['special'])
                        <span class="price-old">${{ money_format('%i', $product['old_price']) }}</span>
                    @endif

                    ${{ money_format('%i', $product['price']) }}
                </p>

            </div>
        @endforeach
    </div>
@endforeach