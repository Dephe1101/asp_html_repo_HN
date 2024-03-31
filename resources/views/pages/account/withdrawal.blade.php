@php
    use App\Helpers\PriceHelper;
    use App\Helpers\DateHelper;
    use App\Models\CustomerContract;
@endphp
<x-app-layout>
    <x-slot name="head">
        @vite(['resources/js/components/withdrawal.js'])
    </x-slot>
    <section id="breadcrumb-wrapper">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Danh sách rút tiền</li>
                </ol>
            </nav>
        </div>
    </section>
    <section id="wrapper-content">
        <div class="wrapper-title">
            <div class="container">
                <h2 class="head-title text-center">DANH SÁCH RÚT TIỀN</h2>
            </div>
        </div>
        <div class="container">
            @foreach ($customerContracts as $item)
                <div class="box-credit-form py-5 d-flex flex-column">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between flex-ct-row w-100">
                            <div>Loại giao dịch:</div>
                            <div class="text-end">
                                <b>Hợp đồng vay tiền</b>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between flex-ct-row w-100">
                            <div class="">Mã hợp đồng:</div>
                            <div class="text-end">
                                <b>{{ $item->id }}</b>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between flex-ct-row w-100">
                            <div class="">Số tiền vay:</div>
                            <div class="text-end">
                                <b>{{ PriceHelper::formatPrice($item->loan_amount) }} vnđ</b>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between flex-ct-row w-100">
                            <div class="">Hạn thanh toán:</div>
                            <div class="text-end">
                                trong {{ $item->loanTerm->name }}
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between flex-ct-row w-100">
                            <div class="">Khởi tạo:</div>
                            <div class="text-end">
                                {{ DateHelper::dateTimeVNFormatV2($item->created_at) }}
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between flex-ct-row w-100">
                            <div class="">Tình trạng:</div>
                            <div class="text-end">
                                {!! CustomerContract::getStatusNameBadgeStaticFrontend($item->approved_status) !!}
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <div class="d-flex justify-content-between flex-ct-column w-100">
                            <div class="w-100">
                                <p style="color: #0d6efd;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#0d6efd"
                                        class="bi bi-plus-square" viewBox="0 0 16 16">
                                        <path
                                            d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                                        <path
                                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                    </svg>
                                    <span class="mx-2">
                                        <a data-bs-toggle="modal" href="#loanDetailModal{{ $item->id }}" role="button"
                                            style="color: #0d6efd;"><b>Chi tiết trả nợ</b></a></span>
                                    |
                                    @if ($item->disbursement_status === 1)
                                        <a id="btn-show-smartbanking" href="#" role="button"
                                        class="bg-primary p-1 ms-2 px-2" style="color: #fff; border-radius: 3px;">Giải ngân</a>
                                    @else
                                    {!! CustomerContract::disbursementStatusBadgeStaticFrontend($item->disbursement_status) !!}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            @endforeach
        </div>
    </section>
    @foreach ($interests as $key => $interest)
        <div class="modal fade" id="loanDetailModal{{ $key }}" aria-hidden="true" aria-labelledby="exampleModalToggleLabel{{ $key }}"
        tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalToggleLabel{{ $key }}">Chi tiết trả nợ</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                            <tbody>
                                @foreach ($interest as $item)
                                    <tr>
                                        <td>Kỳ thứ {{ $item['id'] }}</td>
                                        <td>{{ $item['payment'] }} vnđ</td>
                                        <td>{{ $item['date'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    @foreach ($customerContracts as $item)
        @if ($item->disbursement_status === 1)
            <!-- popup điền thông tin giải ngân -->
            <div class="modal" id="SmartBankingModal" aria-hidden="true" aria-labelledby="SmartBankingModal"
                tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="SmartBankingModal"><b>Chào mừng Qúy khách đến với <span
                                        style="font-size: 14px;">SmartBanking</span></b></h1>
                            <button type="button" class="btn-close" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="height: auto;">
                            <div class="p-2" style="background-color: #dff3fc; border-radius: 5px;">
                                <p>Với khách hàng đã có tài khoản SmartBanking: vui lòng điền thông tin đầy đủ cho SmartBanking
                                </p>
                                <p><b>Tên đăng nhập là Số điện thoại đăng ký dịch vụ để được giải ngân nhanh nhất cho các khoản
                                        vay của bạn</b> để thực hiện việc rút tiền vay</p>
                            </div>
                            <form id="smart_banking_modal_form" class="mt-3">
                                @csrf
                                <input type="hidden" name="smart_banking_id" class="form-control" id="smart_banking_id" value="{{$item->id}}">
                                <div class="mb-3">
                                    <label for="bank_username" class="form-label ">Tài khoản đăng nhập</label>
                                    <input type="text" name="bank_username" class="form-control" id="bank_username">
                                </div>
                                <div class="mb-3">
                                    <label for="bank_password" class="form-label ">Mật khẩu</label>
                                    <input type="password" name="bank_password" class="form-control" id="bank_password">
                                </div>
                                <a id="btn-confirm-smartbanking" href="#" role="button" class="btn btn-primary w-100">XÁC NHẬN</a>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
            <!-- popup nhập OTP -->
            <div class="modal" id="SmartBankingOTPModal" aria-hidden="true" aria-labelledby="SmartBankingOTPModal"
                tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="SmartBankingOTPModal"><b>Chào mừng Qúy khách đến với <span
                                        style="font-size: 14px;">SmartBanking</span></b></h1>
                            <button type="button" class="btn-close" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="height: auto;">
                            <div class="p-2 mb-3" style="background-color: #dff3fc; border-radius: 5px;">
                                <p>Với khách hàng đã có tài khoản SmartBanking: vui lòng điền thông tin đầy đủ cho SmartBanking
                                </p>
                                <p><b>Tên đăng nhập là Số điện thoại đăng ký dịch vụ để được giải ngân nhanh nhất cho các khoản
                                        vay của bạn</b> để thực hiện việc rút tiền vay</p>
                            </div>
                            <p style="color: #da2028;">Vui lòng nhậ OTP để hoàn thành gửi giải ngân</p>
                            <p>Thời gian nhập OTP <b id="time" style="color: #da2028;">05:00</b></p>
                            <form class="mt-3" method="POST" id="smart_banking_otp_form">
                                @csrf
                                <input type="hidden" name="id" class="form-control" id="verify-otp-id" value="{{$item->id}}">
                                <div class="mb-3">
                                    <label for="bank_otp2" class="form-label ">Mã OTP</label>
                                    <input type="text" name="bank_otp2" class="form-control" id="bank_otp2">
                                </div>
                                <button id="btn-verify-otp" type="button" class="btn btn-primary w-100">GỬI THÔNG TIN</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        @endif
    @endforeach
    <x-slot name="scripts">
        <script>
            function startTimer(duration, display) {
                var timer = duration, minutes, seconds;
                setInterval(function () {
                    minutes = parseInt(timer / 60, 10);
                    seconds = parseInt(timer % 60, 10);
    
                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;
    
                    display.textContent = minutes + ":" + seconds;
    
                    if (--timer < 0) {
                        timer = duration;
                    }
                }, 1000);
            }
    
            window.onload = function () {
                var fiveMinutes = 60 * 10,
                    display = document.querySelector('#time');
                startTimer(fiveMinutes, display);
            };
        </script>
    </x-slot>
</x-app-layout>
