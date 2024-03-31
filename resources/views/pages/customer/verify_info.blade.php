@php
    use App\Helpers\PriceHelper;
@endphp
<x-app-layout>
    <x-slot name="head"></x-slot>
    <section id="breadcrumb-wrapper">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="/">Vay online</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Xác minh thông tin</li>
                </ol>
            </nav>
        </div>
    </section>
    <section id="wrapper-content">
        <div class="container">
            <div class="box-credit-form py-5 d-flex flex-column">
                <form>
                    <div class="box-upload-img">
                        <div class="mb-3 w-100 item-upload-img border-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#198754" class="bi bi-check-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                              </svg>
                              <p class="mt-3" style="color: #da2028; font-size: 20px;"><b>Thông báo thành công</b></p>
                              <p>Chúc mừng quý khách đã đăng ký thành công khoản vay <b style="color: #da2028;">{{ PriceHelper::formatPrice($customer->loan_amount) }} đ</b>.</p>
                        </div>
                        
                    </div>
                </form>
                <a href="{{ route('signature_confirm.show', ['c' => $customer->id]) }}" class="btn btn-primary mt-4">TIẾP TỤC</a>
            </div>
        </div>
    </section>
</x-app-layout>
