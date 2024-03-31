@php
    use App\Helpers\DateHelper;
@endphp
<x-app-layout>
    <x-slot name="head">
        <script>
            function previewImage(event, idPreview, idBtn, idSpan) {
                console.log('event.target: ', event.target);
                const file = event.target.files[0];
                const previewImage = document.getElementById(idPreview);
                const span = document.getElementById(idSpan);
                const btn = document.getElementById(idBtn);

                if (file) {
                    const reader = new FileReader();
                
                    reader.onload = function (e) {
                        previewImage.src = e.target.result;
                        previewImage.style.display = 'block';
                        span.style.display = 'none';
                        btn.style.display = 'block';
                    };
                
                    reader.readAsDataURL(file);
                } else {
                    previewImage.src = '#';
                    previewImage.style.display = 'none';
                    span.style.display = 'block';
                    btn.style.display = 'none';
                }
            }
            function removeImage(idInput, idPreview, idBtn, idSpan) {
                const imageInput = document.getElementById(idInput);
                const previewImage = document.getElementById(idPreview);
                const btn = document.getElementById(idBtn);
                const span = document.getElementById(idSpan);

                imageInput.value = '';
                previewImage.src = '#';
                previewImage.style.display = 'none';
                btn.style.display = 'none';
                span.style.display = 'block';
            }
            function submitForm(event) {
                event.preventDefault();
                const idFront = document.getElementById('id_front');
                const imageIdFront = idFront.files[0];
                const idBehind = document.getElementById('id_behind');
                const imageIdBehind = idBehind.files[0];
                const portrait = document.getElementById('portrait');
                const imagePortrait = portrait.files[0];
                console.log('imageIdFront: ', imageIdFront);
                console.log('imageIdBehind: ', imageIdBehind);
                console.log('imagePortrait: ', imagePortrait);
                if (!imageIdFront || !imageIdBehind || !imagePortrait) {
                    alert('Bạn phải upload đủ thông tin!');
                    return;
                }
                const form = document.getElementById("account_verification");
                form.submit();
            }
        </script>
    </x-slot>
    <section id="breadcrumb-wrapper">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="/">Vay online</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Xác mình tài khoản</li>
                </ol>
            </nav>
        </div>
    </section>
    <section id="wrapper-content">
        <div class="container">
            <div class="box-credit-form py-5 d-flex flex-column">
                <form id="account_verification" method="POST" action="{{ route('account_verification.store') }}" enctype="multipart/form-data">
                    @csrf
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Rất tiếc!</strong> Có một số vấn đề với đầu vào của bạn.
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="box-upload-img">
                        <div class="mb-3 w-100 item-upload-img">
                            <label class="custom-file-upload">
                                <input type="file" name="id_front" id="id_front" onchange="previewImage(event, 'img_id_front', 'btn_id_front', 'span_id_front')"/>
                                <span id="span_id_front" style="vertical-align: middle;"> UPLOAD CMND/CCCD MẶT TRƯỚC</span>
                            </label>
                            <img id="img_id_front" src="assets/images/fb-a-3.png" alt="UPLOAD CMND/CCCD MẶT TRƯỚC" width="" height="" style="display: none;">
                            <div class="d-flex justify-content-center">
                                <button style="display: none;" id="btn_id_front" type="button" class="btn btn-danger mt-3" onclick="removeImage('id_front', 'img_id_front', 'btn_id_front', 'span_id_front')">XÓA HÌNH</button>
                            </div>
                        </div>
                        <div class="mb-3 w-100 item-upload-img">
                            <label class="custom-file-upload">
                                <input type="file" name="id_behind" id="id_behind" onchange="previewImage(event, 'img_id_behind', 'btn_id_behind', 'span_id_behind')"/>
                                <span id="span_id_behind" style="vertical-align: middle;"> UPLOAD CMND/CCCD MẶT SAU</span>
                            </label>
                            <img id="img_id_behind" src="assets/images/fb-a-3.png" alt="UPLOAD CMND/CCCD MẶT SAU" width="" height="" style="display: none;">
                            <div class="d-flex justify-content-center">
                                <button style="display: none;" id="btn_id_behind" type="button" class="btn btn-danger mt-3" onclick="removeImage('id_behind', 'img_id_behind', 'btn_id_behind', 'span_id_behind')">XÓA HÌNH</button>
                            </div>
                        </div>
                        <div class="w-100 item-upload-img">
                            <label class="custom-file-upload">
                                <input type="file" name="portrait" id="portrait" onchange="previewImage(event, 'img_portrait', 'btn_portrait', 'span_portrait')"/>
                                <span id="span_portrait" style="vertical-align: middle;"> ẢNH CHÂN DUNG NHÌN RÕ MẶT</span>
                            </label>
                            <img id="img_portrait" src="assets/images/fb-a-3.png" alt="ẢNH CHÂN DUNG NHÌN RÕ MẶT" width="" height="" style="display: none;">
                            <div class="d-flex justify-content-center">
                                <button style="display: none;" id="btn_portrait" type="button" class="btn btn-danger mt-3" onclick="removeImage('portrait', 'img_portrait', 'btn_portrait', 'span_portrait')">XÓA HÌNH</button>
                            </div>
                        </div>
                    </div>
                </form>
                <a href="#" class="btn btn-primary mt-4" onclick="submitForm(event)">TIẾP TỤC</a>
            </div>
        </div>
    </section>
</x-app-layout>
