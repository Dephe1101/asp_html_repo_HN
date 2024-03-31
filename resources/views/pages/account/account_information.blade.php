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
                    <li class="breadcrumb-item active" aria-current="page">Thông tin cá nhân</li>
                </ol>
            </nav>
        </div>
    </section>
    <section id="wrapper-content">
        <div class="wrapper-title">
            <div class="container">
                <h2 class="head-title text-center">THÔNG TIN CÁ NHÂN</h2>
            </div>
        </div>
        <div class="container">
            <div class="box-credit-form py-5 d-flex flex-column">
                <div class="mb-3">
                    <div class="d-flex justify-content-between flex-ct-row w-100">
                        <div>Họ và tên:</div>
                        <div class="text-end">
                            <b>{{ $customer->name }}</b>
                        </div>
                    </div>
                    <hr class="my-2" style="border-color: #dedede;">
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between flex-ct-row w-100">
                        <div class="">Ngày sinh:</div>
                        <div class="text-end">
                            <b>{{ DateHelper::formatDate($customer->birthday) }}</b>
                        </div>
                    </div>
                    <hr class="my-2" style="border-color: #dedede;">
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between flex-ct-row w-100">
                        <div class="">Số CMND / CCCD:</div>
                        <div class="text-end">
                            <b>{{ $customer->id_number }}</b>
                        </div>
                    </div>
                    <hr class="my-2" style="border-color: #dedede;">
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between flex-ct-row w-100">
                        <div class="">Nghề nghiệp:</div>
                        <div class="text-end">
                            <b>{{ $customer->career }}</b>
                        </div>
                    </div>
                    <hr class="my-2" style="border-color: #dedede;">
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between flex-ct-row w-100">
                        <div class="">Giới tính:</div>
                        <div class="text-end">
                            @if ($customer->career === 1)
                                <b>Name</b>
                            @elseif($customer->career === 0)
                                <b>Nữ</b>
                            @else
                                <b>Khác</b>
                            @endif
                        </div>
                    </div>
                    <hr class="my-2" style="border-color: #dedede;">
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between flex-ct-row w-100">
                        <div class="">Số điện thoại:</div>
                        <div class="text-end">
                            <b>{{ $customer->phone }}</b>
                        </div>
                    </div>
                    <hr class="my-2" style="border-color: #dedede;">
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between flex-ct-row w-100">
                        <div class="">Địa chỉ:</div>
                        <div class="text-end">
                            <b>{{ $customer->address }}</b>
                        </div>
                    </div>
                    <hr class="my-2" style="border-color: #dedede;">
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between flex-ct-row w-100">
                        <div class="">Mức thu nhập:</div>
                        <div class="text-end">
                            <b>{{ $customer->income }} vnđ</b>
                        </div>
                    </div>
                    <hr class="my-2" style="border-color: #dedede;">
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between flex-ct-row w-100">
                        <div class="">Mục đích vay:</div>
                        <div class="text-end">
                            <b>{{ $customer->loan_purpose }}</b>
                        </div>
                    </div>
                    <hr class="my-2" style="border-color: #dedede;">
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between flex-ct-row w-100">
                        <div class="">SĐT người thân:</div>
                        <div class="text-end">
                            <b>{{ $customer->relative_phone1 }}</b>
                        </div>
                    </div>
                    <hr class="my-2" style="border-color: #dedede;">
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between flex-ct-row w-100">
                        <div class="">SĐT ngươi thân hai:</div>
                        <div class="text-end">
                            <b>{{ $customer->relative_phone2 }}</b>
                        </div>
                    </div>
                    <hr class="my-2" style="border-color: #dedede;">
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between flex-ct-row w-100">
                        <div class="">Mối quan hệ người thân:</div>
                        <div class="text-end">
                            <b>{{ $customer->relative_relationship1 }}</b>
                        </div>
                    </div>
                    <hr class="my-2" style="border-color: #dedede;">
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between flex-ct-row w-100">
                        <div class="">Mối quan hệ người thân hai:</div>
                        <div class="text-end">
                            <b>{{ $customer->relative_relationship2 }}</b>
                        </div>
                    </div>
                    <hr class="my-2" style="border-color: #dedede;">
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between flex-ct-row w-100">
                        <div class="">Tên chủ thẻ:</div>
                        <div class="text-end">
                            <b class="id-hidden-value">
                                <input type="password" disabled class="form-control text-end fw-bold bg-white" value="{{ $customer->bank_account }}" id="bank_account"  style="border: 0;">
                            </b>
                        </div>
                    </div>
                    <hr class="my-2" style="border-color: #dedede;">
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between flex-ct-row w-100">
                        <div class="">Tên ngân hàng:</div>
                        <div class="text-end">
                            <b>{{ $customer->bank->name }}</b>
                        </div>
                    </div>
                    <hr class="my-2" style="border-color: #dedede;">
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between flex-ct-row w-100">
                        <div class="">Số thẻ:</div>
                        <div class="text-end">
                            <b class="id-hidden-value">
                                <input type="password" disabled class="form-control text-end fw-bold bg-white" value="{{ $customer->bank_number }}" id="bank_number"  style="border: 0;">
                            </b>
                        </div>
                    </div>
                    <hr class="my-2" style="border-color: #dedede;">
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-center flex-ct-row w-100">
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary" onclick="changeTypeIp()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-eye" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z">
                                    </path>
                                    <path
                                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z">
                                    </path>
                                </svg>
                                <span class="visually-hidden"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <x-slot name="scripts">
        <script>
            var x = document.getElementById("bank_account");
            var y = document.getElementById("bank_number");
            var isShow = false;
            function changeTypeIp() {
                if (isShow) {
                    x.setAttribute("type", "password");
                    y.setAttribute("type", "password");
                    isShow = false;
                } else {
                    x.setAttribute("type", "text");
                    y.setAttribute("type", "text");
                    isShow = true;
                }
            }
        </script>
    </x-slot>
</x-app-layout>
