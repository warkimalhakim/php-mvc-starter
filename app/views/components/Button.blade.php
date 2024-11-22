@isset($url) <a @else <button @endisset @isset($type) type="{{ $type }}" @else type="button" @endisset @isset($url)
    href="{{ $url }}" @endisset @isset($class) class="{{ $class }}" @else class="btn btn-primary" @endisset
    @isset($target) target="{{ $target }}" @endisset @isset($id) id="{{ $id }}" @endisset>
    {!! $slot !!}
    @isset($url) </a @else </button @endisset>