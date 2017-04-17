@extends('layouts.master')

@section('title', $product['name'] . ' - ' . config('app.name'))

@section('header')
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.css') }}
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick-theme.min.css') }}
    {{ Html::style('https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/4.0.1/ekko-lightbox.min.css') }}

    <style>
        #product-photos a {
            margin: 3px;
        }
        #product-photos a img {
            display: block;
            width: auto;
            height: 150px;
        }
        .checkbox label.strong {
            font-weight: bold;
        }
    </style>
@stop

@section('content')

    <div class="row">
        <div class="col-md-12">
            <h1>{!! $product['name'] !!}</h1>

            @include('snippets.errors')
            @include('snippets.flash')
        </div>
    </div>

    <!-- Product Details -->
    <div class="row">

        <div class="col-md-8">
            @if ($product->defaultPhoto())
                <a href="{{ CustomHelper::image($product->defaultPhoto['name']) }}" class="item" data-toggle="lightbox" data-gallery="multiimages">
                    <img class="product-image" src="{{ CustomHelper::image($product->defaultPhoto['name'], true) }}" alt="{!! htmlspecialchars($product['name']) !!}">
                </a>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div id="product-photos">
                        @if ($product->photos() && $product->photos()->count() > 1)
                            @foreach($product->photos as $photo)
                                @if (!$photo['default'])
                                    <a href="{{ CustomHelper::image($photo['name']) }}" data-toggle="lightbox" data-gallery="multiimages">
                                        <img src="{{ CustomHelper::image($photo['name'], true) }}" alt="{!! htmlspecialchars($product['name']) !!}" class="thumbnail" />
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            @if ($product['description'])
                <div class="row">
                    <div class="col-md-12">
                        <p>{!! nl2br($product['description']) !!}</p>
                    </div>
                </div>
            @endif

            @include('snippets.getsocial')

        </div>

        <div class="col-md-4">

            {{ Form::open(['route' => 'shop.cart.add', 'id' => 'add-to-cart-form']) }}

            <input type="hidden" name="id" value="{{ $product['id'] }}" />
            <input type="hidden" name="quantity" value="1" />
            <input type="hidden" name="inventory_id" value="{{ $product->inventoryItems()->count() == 1 ? $product->inventoryItems()->first()['id'] : '' }}" />

            <div class="add-basket-box clearfix">

                @if ($product->defaultPhoto())
                    <img class="product-image" src="{{ CustomHelper::image($product->defaultPhoto['name'], true) }}" alt="{!! htmlspecialchars($product['name']) !!}">
                @endif

                <p>
                    {!! $product['name'] !!}<br />
                    <span id="price">${{ number_format($product['price'], 2) }}</span>
                </p>

                @if (env('SHOP_ACTIVE') === true && $product->inStock())
                    <button class="btn btn-primary" type="submit" title="Add to Cart"><i class="fa fa-shopping-cart"></i></button>
                @endif

            </div>

            @if (env('SHOP_ACTIVE') === false)
                <div class="alert alert-danger">This item is not available for sales at the moment. Please come back later.</div>
            @endif

            @if (count($product->availableOptions()) > 0)
                <h3>
                    Options

                    <span id="availability" class="pull-right"></span>
                </h3>

                @foreach ($product->availableOptions() as $attributeOptionsAttributeId => $attributeOptionsAttribute)

                    @if (count($attributeOptionsAttribute['options']) > 0)
                        <strong>{!! $attributeOptionsAttribute['name'] !!}</strong>

                        @if ($attributeOptionsAttribute['display'] == 'select')
                            {{
                                Form::select('options[' . $attributeOptionsAttributeId . ']', collect($attributeOptionsAttribute['options'])->map(function ($item) use ($product) {
                                    return $item['name'];
                                })->toArray(), '', [
                                    'class'    => 'form-control',
                                    'required' => true,
                                ])
                            }}

                            <br />
                        @else
                            <div data-toggle="buttons">
                                @foreach ($attributeOptionsAttribute['options'] as $optionId => $option)
                                    <label class="btn btn-primary btn-circle">
                                        {{ Form::radio('options[' . $attributeOptionsAttributeId . ']', $optionId) }}

                                        {!! $option['name'] !!}
                                    </label>
                                @endforeach
                            </div>

                            <br />
                        @endif
                    @endif
                @endforeach
            @endif

            {{ Form::close() }}

            <h3>Stock</h3>

            <div id="stock-info">
                @if ($product->inStock())
                    <p>
                        This item is in stock and ready to ship.

                        @if (count($product->availableOptions()) > 0)
                            Please select product options above.
                        @endif
                    </p>
                @else
                    <p class="alert alert-danger">This item is currently Out Of Stock, please come back later.</p>
                @endif
            </div>

            <h3>Delivery</h3>

            <p>Usually shipped within 24 hours.</p>

        </div>

    </div>

@stop

@section('bottom_block')
    {{ Html::script('https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.6.0/slick.min.js') }}
    {{ Html::script('https://cdnjs.cloudflare.com/ajax/libs/ekko-lightbox/4.0.1/ekko-lightbox.min.js') }}

    <script>
        $(document).ready(function() {

            var availableInventoryItems = {!! json_encode($product->availableInventoryItems()) !!};

            var selectedOptions = [],
                inventoryIdx = 0;

            @if ($product->inventoryItems()->count() == 1)
                var inventoryItem = $('input[name="inventory_id"]').val();
            @else
                var inventoryItem = availableInventoryItems[inventoryIdx]['inventory'];
            @endif

            // Check if a given inventory contains the given option
            var matchOption = function(option, inventoryArr) {
                for (var i = 0; i < inventoryArr['options'].length; i++) {
                    if (inventoryArr['options'][i]['attribute'] == option['attribute'] && inventoryArr['options'][i]['option'] == option['option']) {
                        return true;
                    }
                }
                return false;
            };

            // Check availability of the selected options
            var checkAvailability = function() {
                // Walk through each availableInventoryItem to find an inventory item that has all selected options
                for (var i = 0; i < availableInventoryItems.length; i++) {
                    var totalMatched = 0;

                    // For each selected option
                    for (var j = 0; j < selectedOptions.length; j++) {
                        // If the current inventory item contains this option
                        if (matchOption(selectedOptions[j], availableInventoryItems[i])) {
                            totalMatched++;
                        }
                    }

                    if (totalMatched == selectedOptions.length) {
                        inventoryIdx = i;
                        return availableInventoryItems[i]['inventory'];
                    }
                }

                return false;
            };

            // Reset
            var reset = function() {
                selectedOptions = [];
                $('input[name="inventory_id"]').val('');
            };

            reset();

            // On options selected/checked
            $('select[name^="options"], input[type="radio"][name^="options"]').change(function() {
                reset();

                // Walk through each selected/checked option to update selectedOptions array
                $('select[name^="options"] option:selected, input[type="radio"][name^="options"]:checked').each(function () {
                    var name = $(this).attr('type') == 'radio' ? $(this).attr('name') : $(this).closest('select').attr('name'),
                        attribute = name.substr(8, name.length - 9);

                    selectedOptions.push({
                        'attribute': attribute,
                        'option': $(this).val()
                    })
                });

                inventoryItem = checkAvailability();

                if (!inventoryItem) {
                    $('#availability').html('<small class="text-danger" title="The selected options are not available. Please try other options."><i class="fa fa-times"></i> Not available</small>');
                    $('#stock-info').html('<p class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> The selected options are not available. Please try other options.</p>');
                } else {
                    $('#availability').html('<small class="text-success" title="The selected options are available."><i class="fa fa-check"></i> Available</small>');
                    $('#stock-info').html('<p class="alert alert-success"><i class="fa fa-check-circle"></i> The selected options are available.</p>');
                }

                $('#price').text('$' + availableInventoryItems[inventoryIdx]['price']);
            });

            // On form submit
            $('#add-to-cart-form').submit(function(e) {
                if (!inventoryItem) {
                    e.preventDefault();
                    alert('Sorry, a product with your selected options is not available, please try different options or come back later.');
                } else {
                    $('input[name="inventory_id"]').val(inventoryItem);
                    return true;
                }
            });

            // Photos carousel
            $('#product-photos').slick({
                dots: true,
                infinite: true,
                slidesToShow: 3,
                slidesToScroll: 3,
                autoplay: true,
                autoplaySpeed: 5000,
                speed: 1000,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ]
            });

            // delegate calls to data-toggle="lightbox"
            $(document).delegate('*[data-toggle="lightbox"]', 'click', function(event) {
                event.preventDefault();
                return $(this).ekkoLightbox({
                    always_show_close: true
                });
            });

        });
    </script>
@endsection