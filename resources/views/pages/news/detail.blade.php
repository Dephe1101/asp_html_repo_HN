<x-app-layout>
    <section id="breadcrumb-wrapper">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="/">Tin tức</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết tin tức</li>
                </ol>
            </nav>
        </div>
    </section>
    <section id="wrapper-content">
        <div class="container">
            <div class="box-detail-news">
                <div class="detail-news">
                    @if (!empty($configSeo))
                        <div class="title-detail">
                            {!! $configSeo->title ?? '' !!}
                        </div>
                    @else
                        <div class="title-detail">
                            {!! $post->title ?? '' !!}
                        </div>
                    @endif
                    <div class="date-detail">
                        {{ $post->getPublishedAt() }}
                    </div>
                    <div class="content-detail">
                        {!! $post->content ?? '' !!}
                    </div>
                </div>

                @if (count($relatedPosts) > 0)
                    <div class="orther-news">
                        <div class="title-orther">
                            Các tin khác
                        </div>
                        @foreach ($relatedPosts as $post)
                            <x-post.post-card :data="$post"></x-post.post-card>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
</x-app-layout>
