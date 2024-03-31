<div class="container">
    <h2 class="head-title text-center mt-5">TIN TỨC</h2>
    <div class="news-list row mt-4">
        @if (!empty($news))
            @foreach ($news as $item)
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card">
                        @php
                        $slug = '';
                        if (isset($item->configSeo) && isset($item->configSeo->url)) {
                            $slug = $item->configSeo->url;
                        }
                        @endphp
                        <a href="{{ route('news.detail', ['slug' => $slug]) }}" class="card-text" title="{{ $item->title }}">
                            <img src="{{ $item->configSeo->image }}" class="card-img-top" alt="{{ $item->title }}">
                        </a>
                        <div class="card-body">
                            <a href="{{ route('news.detail', ['slug' => $slug]) }}" class="card-text" title="{{ $item->title }}"><strong>{{ $item->title }}</strong></a>
                        </div>
                    </div>
                </div>
            @endforeach
            
        @endif
    </div>
    <div class="text-center">
    <a href="{{ route('news') }}" title="" class="pagi-view-all">XEM TẤT CẢ Tin tức</a>
    </div>
</div>