<div class="flash-message">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if (session()->has('alert-' . $msg))
            <div class="alert alert-{{ $msg }}">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                {{ session('alert-' . $msg) }}
            </div>
        @endif
    @endforeach
    @if (session()->has('status'))
        <div class="alert alert-info">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            {{ session('status') }}
        </div>
    @endif
</div>