@php
    use App\Helpers\PriceHelper;
    use App\Helpers\DateHelper;
    use App\Models\CustomerContract;

@endphp
<x-app-layout>
    <x-slot name="head">
    </x-slot>
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
        <div class="container">
            <div class="box-detail-news">
                <div class="detail-news">
                    {!! $contract !!}
                    <div class="d-flex justify-content-around w-100 mb-2">
                        <a href="{{ route('loan_contract.show') }}" class="btn btn-secondary w-25 me-2">ĐÓNG</a>
                        <a id="btnPrint" onclick="printContent()" class="btn btn-danger w-25">IN PDF</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <x-slot name="scripts">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
        <script>
            function printContent() {
                printJS({
                    printable: 'content-detail',
                    type: 'html',
                    targetStyles: ['*']
                });
            }
        </script>
    </x-slot>
    
</x-app-layout>
