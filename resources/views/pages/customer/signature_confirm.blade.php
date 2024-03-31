@php
    use App\Helpers\PriceHelper;
    use App\Helpers\DateHelper;
@endphp
<x-app-layout>
    <x-slot name="head">
    </x-slot>
    <section id="breadcrumb-wrapper">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="/">Vay online</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Xác nhận vay</li>
                </ol>
            </nav>
        </div>
    </section>
    <section id="wrapper-content">
        <div class="wrapper-title">
            <div class="container">
                <h2 class="head-title text-center">Thông tin khoản vay</h2>
            </div>
        </div>
        <div class="container">
            <div class="box-credit-form py-5 d-flex flex-column">
                <form id="form_signature_confirm" method="POST" action="{{ route('signature_confirm.store') }}">
                    @csrf
                    <div class="mb-3">
                        <div class="d-flex justify-content-between flex-ct-row w-100">
                            <div>Khoản tiền vay:</div>
                            <div class="text-end">
                                <b>{{ PriceHelper::formatPrice($customer->loan_amount) }} đ</b>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between flex-ct-row w-100">
                            <div class="">Hạn thanh toán:</div>
                            <div class="text-end">
                                {{ $loanTerm->name }}
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between flex-ct-row w-100">
                            <div class="">ngày vay:</div>
                            <div class="text-end">
                                {{ DateHelper::dateVNFormat($customer->created_at) }}
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between flex-ct-column w-100">
                            <div class="w-65">
                                <div class="input-group">
                                    <div class="input-group-text border-0 bg-white ps-0">
                                        <input id="confirm" name="confirm" class="form-check-input mt-0" type="checkbox" value=""><span
                                            class="ms-2">Xác nhận đồng ý với hợp đồng</span>
                                    </div>
                                </div>
                            </div>
                            <div class="w-35 text-end">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between flex-ct-column w-100">
                            <div class="w-35"><b>Ký vào khung bên dưới</b></div>
                            <div class="w-65 text-end">
                                <button class="btn btn-primary" id="clear-canvas"><span> làm mới </span></button>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="wrapper w-100">
                            <canvas id="signature-pad" width="" style="height: 300px;"></canvas>
                            <input type="hidden" name="signature" id="signatureImageInput">
                        </div>
                    </div>
                </form>
                <a href="#" class="btn btn-primary mt-4" onclick="saveSignature(event)">XÁC NHẬN CHỮ KÝ</a>  
            </div>
        </div>
    </section>
    <x-slot name="scripts">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/signature_pad/1.3.5/signature_pad.min.js" integrity="sha512-kw/nRM/BMR2XGArXnOoxKOO5VBHLdITAW00aG8qK4zBzcLVZ4nzg7/oYCaoiwc8U9zrnsO9UHqpyljJ8+iqYiQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script>
            var canvas = document.getElementById("signature-pad");
            function resizeCanvas() {
                var ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
            }
            window.onresize = resizeCanvas;
            resizeCanvas();

            var signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(250,250,250)'
            });

            document.getElementById("clear-canvas").addEventListener('click', function (e) {
                event.preventDefault();
                signaturePad.clear();
            })

            function isCanvasEmpty(canvas) {
                const context = canvas.getContext('2d');
                const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                const pixelData = imageData.data;
                for (let i = 0; i < pixelData.length; i += 4) {
                    if (pixelData[i] !== 250 || pixelData[i + 1] !== 250 || pixelData[i + 2] !== 250) {
                        return false;
                    }
                }
                return true; 
            }

            // Lưu chữ ký
            function saveSignature(event) {
                event.preventDefault();
                const signatureData = canvas.toDataURL(); // Lấy dữ liệu hình ảnh từ canvas dưới dạng base64
                const checkbox = document.getElementById('confirm');
                console.log('checkbox: ', checkbox.checked);
                if (isCanvasEmpty(canvas) && !checkbox.checked) {
                    alert('Bạn phải tích vào xác nhận và tạo chữ ký!');
                    return;
                }
                checkbox.value = 1;
                // Gán base64 vào trường ẩn
                document.getElementById('signatureImageInput').value = signatureData;
                // // Submit form
                document.getElementById('form_signature_confirm').submit();
            }
        </script>
    </x-slot>
</x-app-layout>
