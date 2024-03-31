@php
$requestPath = request()->path() == '/' ? null : request()->path();
@endphp
<x-app-layout>
    <x-slot name="head">
        
    </x-slot>
    <section id="breadcrumb-wrapper">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Đổi mật khẩu</li>
                </ol>
            </nav>
        </div>
    </section>
    <section id="wrapper-content">
        <div class="wrapper-title">
            <div class="container">
                <h2 class="head-title text-center">ĐỔI MẬT KHẨU</h2>
            </div>
        </div>
        <div class="container">
            <div class="box-credit-form py-5 d-flex flex-column">
                <form id="update_password" method="POST" action="{{ route('update_password.update') }}">
                    @csrf
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @elseif (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100">
                            <div class="w-35 pt-2"><b class="fs-6">Mật khẩu cũ</b> <span
                                    class="text-danger fw-bold">*</span></div>
                            <div class="w-65">
                                <div class="input-group">
                                    <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" required autofocus value="" id="InputPass2"  style="border-right: 0;">
                                    <span class="input-group-text bg-white" onclick="changeTypeIp2()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16" id="icon-eye-slash">
                                            <path
                                                d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z" />
                                            <path
                                                d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z" />
                                        </svg>
                                    </span>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100">
                            <div class="w-35 pt-2"><b class="fs-6">Mật khẩu mới</b> <span
                                    class="text-danger fw-bold">*</span></div>
                            <div class="w-65">
                                <div class="input-group">
                                    <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autofocus value="" id="InputPass"  style="border-right: 0;">
                                    <span class="input-group-text bg-white" onclick="changeTypeIp()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16" id="icon-eye-slash">
                                            <path
                                                d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z" />
                                            <path
                                                d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z" />
                                        </svg>
                                    </span>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100">
                            <div class="w-35 pt-2"><b class="fs-6">Xác nhận mật khẩu</b> <span
                                    class="text-danger fw-bold">*</span></div>
                            <div class="w-65">
                                <div class="input-group">
                                    <input type="password" name="password_confirmation" class="form-control" value="" id="InputPass1"  style="border-right: 0;">
                                    <span class="input-group-text bg-white" onclick="changeTypeIp1()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-eye-slash-fill" viewBox="0 0 16 16" id="icon-eye-slash">
                                            <path
                                                d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z" />
                                            <path
                                                d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <a href="" class="btn btn-primary mt-4" onclick="submitForm(event)">Đổi mật khẩu</a>
                <p class="txt-register text-center mt-3">Quay về <a href="/">Trang chủ</a> ?</p>
            </div>
        </div>
    </section>
    <x-slot name="scripts">
        <script>
            function changeTypeIp() {
                var x = document.getElementById("InputPass");
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
            }
            function changeTypeIp1() {
                var y = document.getElementById("InputPass1");
                if (y.type === "password") {
                    y.type = "text";
                } else {
                    y.type = "password";
                }
            }
            function changeTypeIp2() {
                var y = document.getElementById("InputPass2");
                if (y.type === "password") {
                    y.type = "text";
                } else {
                    y.type = "password";
                }
            }
            function submitForm(e) {
                e.preventDefault();
                const form = document.getElementById("update_password");
                form.submit();
            }
        </script>
    </x-slot>
</x-app-layout>
