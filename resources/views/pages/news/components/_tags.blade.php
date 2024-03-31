<div css-module="box-center">
    <div css-module="box-filter-title">Tags</div>
    <div css-module="list-item-filter lif-item">
        @foreach ($tags as $tag)
            <a href="{{route('common.slug', ['slug' => isset($tag->configSeo) ? $tag->configSeo->url : ''])}}" css-module="btn-link-filter" rel="nofollow">{{$tag->name}}</a>
        @endforeach
    </div>
</div>
