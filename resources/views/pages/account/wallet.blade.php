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
                    <li class="breadcrumb-item active" aria-current="page">Thông tin ví tiền</li>
                </ol>
            </nav>
        </div>
    </section>
    <section id="wrapper-content">
        <div class="container">
            <div class="box-credit-form py-5 d-flex flex-column">
                <form>
                    <div class="box-upload-img">
                        <div class="mb-3 w-100 item-upload-img border-0 p-0 position-relative">
                            <img src="{{Vite::asset('resources/assets/images/card.jpg')}}" alt="ảnh thẻ ngân hàng" width="" height="">
                            <div class="box-info d-flex justify-content-between w-100 px-3">
                                <span class="stk-card">{{ substr($customer->bank_number, 0, 4) }}</span>
                                <span class="stk-card">{{ substr($customer->bank_number, -4) }}</span>
                                <span class="stk-card">****</span>
                                <span class="stk-card">****</span>
                            </div>
                            <span class="chu-tai-khoan">{{ $customer->bank_account }}</span>
                            {{-- <span class="ngay-mo-the">12/22</span> --}}
                        </div>
                        <div class="d-flex justify-content-around flex-ct-column w-100">
                            <div class="w-100">
                                <div class="box-so-du d-flex justify-content-between">
                                    <p class="mb-1 ps-3 w-50">Số dư ví</p>
                                    @if ($customerContract->approved_status === 1)
                                        <input type="text" class="form-control w-50 text-end" value="{{ PriceHelper::formatPrice($customerContract->loan_amount) }} VNĐ">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                @if ($customerContract->approved_status === 1)
                    <a href="{{ route('withdrawals.show') }}" class="btn btn-success mt-4">TÀI KHOẢN ĐƯỢC PHÉP RÚT TIỀN</a>
                @else
                    <a data-bs-toggle="modal" href="#exampleModalToggle" role="button"
                    class="btn btn-danger mt-4">TÀI KHOẢN CHƯA ĐƯỢC RÚT TIỀN</a>
                @endif
                <img src="{{Vite::asset('resources/assets/images/bank.jpg')}}" alt="" class="mt-4">
            </div>
        </div>
    </section>
    <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-custom text-center" style="height: auto;">
                    <div class="mb-3">
                        <svg xmlns="http://www.w3.org/2000/svg" style="" width="40" height="40" fill="#ff4d4f"
                            class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                            <path
                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
                        </svg>
                    </div>
                    <p><b>Rút tiền thất bại</b></p>
                    <p style="font-size: 14px; color: #da2028;">Chưa có khoản tiền được duyệt</p>
                    <p style="font-size: 14px;">Liên hệ CSKH trực tuyến để được hỗ trợ</p>
                    <a href="{{ route('contact') }}" class="btn btn-success">LIÊN HỆ CHĂM SÓC KHÁCH HÀNG</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
