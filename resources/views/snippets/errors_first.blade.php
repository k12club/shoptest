@if ($errors->has($param))
    <p class="help-block">
        {{ $errors->first($param) }}
    </p>
@endif