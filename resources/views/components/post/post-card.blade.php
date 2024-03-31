@props(['data'])

@php
$slug = '';
if (isset($data->configSeo) && isset($data->configSeo->url)) {
    $slug = $data->configSeo->url;
}
@endphp

<div class="item-orther-news row mt-4">
    <div class="col-12 col-md-3">
        <a href="{{ route('news.detail', ['slug' => $slug]) }}">
            <img src="{{ $data->configSeo->image }}" class="" alt="{{ $data->title }}" width=""
                height="">
        </a>
    </div>
    <div class="col-12 col-md-9">
        <a href="{{ route('news.detail', ['slug' => $slug]) }}" class="item-orther-title">
            {{ $data->title }}
        </a>
    </div>
</div>
