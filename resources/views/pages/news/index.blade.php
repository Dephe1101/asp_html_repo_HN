<x-app-layout>
    <x-slot name="head">
        @vite(['resources/js/components/loadMoreNews.js'])
    </x-slot>
    <section id="breadcrumb-wrapper">
        <div class="container">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
              <li class="breadcrumb-item active" aria-current="page">Tin tức</li>
            </ol>
          </nav>
        </div>
      </section>
      <section id="wrapper-content">
        <div class="wrapper-title">
          <div class="container">
            <h2 class="head-title text-center">TIN TỨC</h2>
          </div>
        </div>
        <div class="container">
            <div class="news-list row mt-4">
                @if (!empty($posts))
                    @foreach ($posts as $item)
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
            @if ($posts->hasPages())
                <div class="text-center load-more-news">
                    <a href="" id="btn-load-more-news" title="" class="pagi-view-all">XEM THÊM</a>
                </div>
            @endif
        </div>
    </section>
</x-app-layout>
