<div class="row">
    <div class="col-md-12">
        <ul class="main-nav clearfix">
            <li{!! strpos(Route::currentRouteName(), 'admin.home') === 0 ? ' class="active"' : '' !!}>{{ Html::link(route('admin.home'), 'Dashboard') }}</li>
            <li{!! strpos(Route::currentRouteName(), 'admin.orders') === 0 ? ' class="active"' : '' !!}>{{ Html::link(route('admin.orders'), 'Orders') }}</li>
            <li{!! strpos(Route::currentRouteName(), 'admin.categories') === 0 ? ' class="active"' : '' !!}>{{ Html::link(route('admin.categories'), 'Categories') }}</li>
            <li{!! strpos(Route::currentRouteName(), 'admin.attributes') === 0 ? ' class="active"' : '' !!}><a href="{{ route('admin.attributes') }}">Attributes</a></li>
            <li{!! strpos(Route::currentRouteName(), 'admin.products') === 0 ? ' class="active"' : '' !!}><a href="{{ route('admin.products') }}">Products</a></li>
            <li{!! strpos(Route::currentRouteName(), 'admin.users') === 0 ? ' class="active"' : '' !!}><a href="{{ route('admin.users') }}">Users</a></li>
        </ul>
    </div>
</div>