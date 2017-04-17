<ul class="top-nav">
    @if (auth()->check())
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-user"></i>&nbsp; {{ auth()->user()->first_name }} <b class="caret"></b>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
                <li>{{ Html::link(route('account'), 'Account Settings') }}</li>
                <li>{{ Html::link(route('orders'), 'My Orders') }}</li>
                <li class="divider"></li>
                @if (auth()->user()->type == 'admin')
                    <li>{{ Html::link(route('admin.home'), ' &nbsp;Admin', ['class' => 'fa fa-desktop', 'title' => 'Admin Panel']) }}</li>
                @endif
                <li>
                    <a href="{{ url('/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-power-off"></i> Log out
                    </a>

                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </li>
    @else
        <li>{{ Html::link(route('login'), 'Login') }}</li>
        <li>{{ Html::link(url('register'), 'Register') }}</li>
    @endif
</ul>