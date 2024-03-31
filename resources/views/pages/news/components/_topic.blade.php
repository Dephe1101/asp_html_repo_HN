<div css-module="box-slide">
    <div css-module="box-filter-title">Chủ đề</div>
    <div css-module="list-topic">
        @foreach ($topics as $topic)
            @if (!is_null($topic) && !is_null($topic->configSeo) && !is_null($topic->configSeo->url))
                <a href="{{route('common.slug', ['slug' => $topic->configSeo->url])}}" css-module="topic-title" rel="nofollow">{{$topic->title}}</a>
            @endif
        @endforeach
    </div>
</div>
