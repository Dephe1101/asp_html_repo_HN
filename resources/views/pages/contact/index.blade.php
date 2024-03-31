@php
    use App\Helpers\PriceHelper;
    use App\Helpers\DateHelper;
@endphp
<x-app-layout>
    <x-slot name="head"></x-slot>
    <section id="breadcrumb-wrapper">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Liên hệ</li>
                </ol>
            </nav>
        </div>
    </section>
    <section id="wrapper-content">
        <div class="container">
            <div class="box-credit-form py-5 d-flex flex-column">
                <form>
                    <div class="box-upload-img">
                        <div class="mb-3 w-100 item-upload-img border-0 p-0">
                           <img src="{{Vite::asset('resources/assets/images/contact-us-banner-1.jpg')}}" alt="Liên hệ">
                        </div>
                    </div>
                </form>
                <div class="mb-3 ">
                    <a href="" class="btn btn-primary w-100 mb-3">LIÊN HỆ THẨM ĐỊNH VIÊN</a >
                    <p class="mt-3"><b>{{ $companyName }}</b></p>
                    <p class="mb-0"><b>MAIL: {{ $email }}</b></p>
                    @if (!empty($hotline))
                        <p class=""><b>HOTLINE: {{ $hotline[0]['value'] }}</b></p>
                    @endif
                    <p class=""><b>{{ $slogan2 }}</b></p>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
