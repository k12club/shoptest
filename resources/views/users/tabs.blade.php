<ul class="nav nav-tabs" role="tablist">
    <li role="presentation"{!! strpos(Route::currentRouteName(), 'account') === 0 ? ' class="active"' : '' !!}>{{ Html::link(route('account'), 'Profile', ['role' => 'tab']) }}</li>
    <li role="presentation"{!! strpos(Route::currentRouteName(), 'orders') === 0 || Route::currentRouteName() == 'user_order' ? ' class="active"' : '' !!}>{{ Html::link(route('orders'), 'My Orders', ['role' => 'tab']) }}</li>
    <li role="presentation"{!! strpos(Route::currentRouteName(), 'addresses') === 0 ? ' class="active"' : '' !!}>{{ Html::link(route('addresses'), 'Addresses', ['role' => 'tab']) }}</li>
    <li role="presentation"{!! strpos(Route::currentRouteName(), 'payments') === 0 ? ' class="active"' : '' !!}>{{ Html::link(route('payments'), 'Payments', ['role' => 'tab']) }}</li>
</ul>