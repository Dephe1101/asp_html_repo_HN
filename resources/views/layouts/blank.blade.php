@php
// use KSVTServiceManager\Helpers\SeoHelper;
@endphp

<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" xmlns="http://www.w3.org/1999/xhtml">

<head>
    <x-meta></x-meta>
    <x-head>{{ $head ?? '' }}</x-head>
</head>

<body>
    <!-- Body -->
    {{ $slot }}
    <!-- Body -->
    <x-scripts>
        {{ $scripts ?? '' }}
    </x-scripts>

    @if (!empty($codeSeosFooter))
        @foreach ($codeSeosFooter as $item)
            {!! $item->content !!}
        @endforeach
    @endif
</body>

</html>
