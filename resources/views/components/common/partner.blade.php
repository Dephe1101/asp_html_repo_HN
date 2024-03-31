<section id="partner">
    <div class="container">
        <h2 class="head-title text-center mt-5">ĐỐI TÁC</h2>
        <div id="slider-partner" class="carousel slide mt-4">
            <div class="carousel-indicators">
                @foreach ($partners as $key => $item)
                    <button type="button" data-bs-target="#slider-partner" data-bs-slide-to="{{ $key }}" class="{{ $key === 0 ? 'active' : '' }}" aria-current="true" aria-label="Slide {{ $key }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner">
                @foreach ($partners as $key => $partner)
                    <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            @foreach ($partner as $item)
                                <img src="{{ $item->value }}" class="" alt="{{ $item->name }}">
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>