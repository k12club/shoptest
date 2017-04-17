<div class="row">

    <div class="col-md-12">

        <ul class="main-nav clearfix">
            <li{!! Route::currentRouteName() == 'home' ? ' class="active"' : '' !!}><a href="{{ route('home') }}" title="Homepage"><i class="fa fa-home"></i> Home</a></li>

            <li{!! Route::currentRouteName() == 'shop' ? ' class="active"' : '' !!}><a href="{{ route('shop') }}">Shop</a></li>

            @if (isset($categories))
                @foreach($categories as $category)
                    <li{!! Route::getCurrentRoute()->getParameter('uri') == $category['uri'] ? ' class="active"' : '' !!}><a href="{{ route('shop.category', $category['uri']) }}">{!! $category['name'] !!}</a></li>
                @endforeach
            @endif

            <li{!! Route::currentRouteName() == 'page.services' ? ' class="active"' : '' !!}><a href="{{ route('page.services') }}">Services</a></li>
        </ul>

    </div>

</div>