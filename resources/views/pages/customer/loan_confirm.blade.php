@php
    use App\Helpers\DateHelper;
@endphp
<x-app-layout>
    <x-slot name="head">
        @vite(['resources/js/components/loanConfirm.js'])
    </x-slot>
    <section id="breadcrumb-wrapper">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="/">Vay online</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Xác nhận khoản vay</li>
                </ol>
            </nav>
        </div>
    </section>
    <section id="wrapper-content">
        <div class="wrapper-title">
            <div class="container">
                <h2 class="head-title text-center">Chọn khoản vay</h2>
            </div>
        </div>
        <div class="container">
            <div class="box-credit-form py-5 d-flex flex-column">
                <form id="form_loan_confirm" method="POST" action="{{ route('loan_confirm.store') }}">
                    @csrf
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100 ">
                            <div class="w-35 pt-2"><b class="fs-6">Số tiền cần vay</b> <span
                                    class="text-danger fw-bold">*</span></div>
                            <div class="w-65">
                                <input id="loan_amount" name="loan_amount" type="text" class="form-control @error('phone') is-invalid @enderror" required autofocus>
                                <div class="w-100 mt-1 d-flex justify-content-between">
                                    <span><b>Từ 30 Triệu VNĐ</b></span>
                                    <span><b>Đến 500 Triệu VNĐ</b></span>
                                </div>
                                @error('loan_amount')
                                    <div class="mt-1" id="message"><b style="color:#da2028">{{ $message }}</b></div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100">
                            <div class="w-35 pt-2"><b class="fs-6">Kỳ hạn vay</b> <span
                                    class="text-danger fw-bold">*</span></div>
                            <div class="w-65">
                                <select class="form-select @error('password') is-invalid @enderror" required id="loan_term_id" name="loan_term_id">
                                    <option selected value="">Lựa chọn kỳ hạn vay</option>
                                    @foreach ($loanTermOptions as $key => $item)
                                        <option value="{{$key}}">{{$item}}</option>
                                    @endforeach
                                </select>
                                @error('loan_term_id')
                                    <div class="mt-1" id="message"><b style="color:#da2028">{{ $message }}</b></div>
                                @enderror
                                <p class="mt-1" id="message"><b style="color:#da2028">Khoản vay từ 30 triệu đến 500 triệu đồng</b></p>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="mb-3">
                        <p><b>Thông tin khoản vay</b></p>
                        <div class="d-flex justify-content-between flex-ct-row w-100">
                            <div>Số tiền:</div>
                            <div class="text-end">
                                <b id="loan_amount_info">0 đ</b>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between flex-ct-row w-100">
                            <div>Thời hạn vay:</div>
                            <div class="text-end">
                                <b id="loan_term_info">......</b>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between flex-ct-row w-100">
                            <div>Ngày vay:</div>
                            <div class="text-end">
                                <b style="color: #da2028;">{{ DateHelper::curDateVNFormat() }}</b>
                            </div>
                        </div>
                        <hr>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <p><b>Kết quả</b></p>
                            <a id="rate_detail" href="#" role="button"
                                style="color: #0d6efd;"><b>Chi tiết trả nợ</b></a></span>
                        </div>

                        <div class="d-flex justify-content-between flex-ct-row w-100">
                            <div>Trả nợ kì đầu:</div>
                            <div class="text-end">
                                <b id="amount_info">0 vnđ</b>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between flex-ct-row w-100">
                            <div>Lãi suất ngân hàng:</div>
                            <div class="text-end">
                                <b style="color:#198754 ;" id="interest_info">0.9 %</b>
                            </div>
                        </div>
                    </div>
                </form>
                <a href="#" class="btn btn-primary mt-4 btn_loan_confirm">XÁC NHẬN KHOẢN VAY</a>
            </div>
        </div>
    </section>
    <div class="modal" id="model_rate" aria-hidden="true" aria-labelledby="model_rate"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="model_rate">Chi tiết trả nợ</h1>
                    <button type="button" class="btn-close" aria-label="Close"></button>
                </div>
                <div class="modal-body modal-custom">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Kỳ</th>
                                <th scope="col">Số tiền</th>
                                <th scope="col">Ngày đóng</th>
                            </tr>
                        </thead>
                        <tbody id="item_rate">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
