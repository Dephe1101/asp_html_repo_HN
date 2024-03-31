<x-app-layout>
    <x-slot name="head">
    </x-slot>
    <section id="breadcrumb-wrapper">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    {{-- <li class="breadcrumb-item"><a href="/">Sản phẩm</a></li> --}}
                    @if (!empty($configSeo))
                        <li class="breadcrumb-item active" aria-current="page">{{ $configSeo->title ?? '' }}</li>
                    @else
                        <li class="breadcrumb-item active" aria-current="page">{!! $page->title ?? '' !!}</li>
                    @endif
                </ol>
            </nav>
        </div>
    </section>
    <section id="wrapper-content">
        <div class="box-banner">
            <div class="container">
                @if (!empty($configSeo))
                    <h2 class="head-title">{!! $configSeo->title ?? '' !!}</h2>
                @else
                    <h2 class="head-title">{!! $page->title ?? '' !!}</h2>
                @endif
                <a href="buoc-3.html" class="btn-dangky mt-2" style="font-weight: 400;">ĐĂNG KÝ VAY</a>
            </div>
        </div>
        {{-- <div class="box-link-tag">
            <div class="container">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Lợi thế của sản phẩm</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Ưu đãi</a>
                    </li>
                </ul>
            </div>
        </div> --}}
        <div class="container">
            <div class="row my-5">
                {!! $page->content !!}
            </div>
        </div>
    </section>
</x-app-layout>
