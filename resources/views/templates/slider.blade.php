<section id="slider-main" class="carousel slide">
    <div class="carousel-inner">
        @php
            $index = 0;
        @endphp
        @foreach ($sliders as $item)
            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                <img src="{{ $item->value }}" class="d-block w-100" alt="{{ $item->name }}">
            </div>
            @php
                $index++;
            @endphp
        @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#slider-main" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#slider-main" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</section>