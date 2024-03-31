@if (!empty($customerReviews))
    <section id="customer-feedback">
        <div class="container">
            <h2 class="head-title text-center mt-5">Ý KIẾN NGƯỜI TIÊU DÙNG</h2>
            <div class="fb-list row mt-4">
                @foreach ($customerReviews as $item)
                    <div class="fb-item col-sm-6 col-md-4">
                        <div class="card h-100">
                            <div class="card-header d-flex">
                                <img src="{{ $item->avatar }}" class="cus-avatar" alt="{{ $item->name }}" width="70" height="70">
                                <div class="d-flex flex-column align-self-center ms-2">
                                    <h5 class="card-title">{{ $item->name }}</h5>
                                    <h6 class="card-subtitle">{{ $item->career }}</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="card-text">{{ $item->note }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
