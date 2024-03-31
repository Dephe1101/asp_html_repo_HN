@php
    use App\Helpers\PriceHelper;
    use App\Helpers\DateHelper;
    use App\Models\CustomerContract;
@endphp
<x-app-layout>
    <x-slot name="head"></x-slot>
    <section id="breadcrumb-wrapper">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Biến động tài khoản</li>
                </ol>
            </nav>
        </div>
    </section>
    <section id="wrapper-content">
        <div class="container">
            @foreach ($customerContracts as $item)
                <div class="box-credit-form py-5 pb-0 d-flex flex-column">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between flex-ct-row w-100">
                            <div><b style="color:#6f42c1 ;">Khoản vay:</b></div>
                            <div class="text-end">
                                <b>{{ PriceHelper::formatPrice($item->loan_amount) }} vnđ</b>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between flex-ct-row w-100">
                            <div class=""><b>{{ DateHelper::dateTimeVNFormatV2($item->created_at) }}</b></div>
                            <div class="text-end">
                                {!! CustomerContract::getStatusNameBadgeStaticFrontend($item->approved_status) !!}
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between flex-ct-row w-100">
                            <div style="color: #da2028;">{{ $item->note }}</div>
                        </div>
                    </div>
                    <hr>
                </div>
            @endforeach
        </div>
    </section>
</x-app-layout>
