@php
use App\Helpers\StringHelper;
@endphp
<div class="container">
    <h2 class="head-title text-center mt-5">{{ $config_product->name }}</h2>
    <div class="card-group row mt-4">
        @if (!empty($products))
            @foreach ($products as $item)
                <a href="{{ StringHelper::getUrl($item->link, false) }}" class="card col-6 col-lg-auto" title="{{ $item->title }}">
                    <img src="{{ $item->image }}" alt="{{ $item->title }}" width="100" height="100" />
                    <span class="card-body">
                    <span class="card-title">{{ $item->title }}</span>
                    </span>
                </a>
            @endforeach
        @endif
    </div>
</div>