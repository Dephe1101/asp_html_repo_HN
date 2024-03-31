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
                    <li class="breadcrumb-item active" aria-current="page">Hợp đồng vay</li>
                </ol>
            </nav>
        </div>
    </section>
    <section id="wrapper-content">
        <div class="wrapper-title">
            <div class="container">
                <h2 class="head-title text-center">DANH SÁCH KHOẢN VAY</h2>
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
                                <b>***{{ $item->id }}</b>
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
                                    <span class="bg-danger p-1 ms-2" style="border-radius: 3px;">
                                        <a href="{{ route('loan_contract_pdf.show') }}"
                                        style="color: #fff;">Xem hợp đồng</a></span></span>
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
</x-app-layout>
