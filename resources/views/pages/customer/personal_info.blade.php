@php
    use App\Helpers\DateHelper;
@endphp
<x-app-layout>
    <x-slot name="head">
        <script>
            function onChangeInCome() {
                let loanAmount = document.getElementById("income");
                let numericValue = Number(loanAmount.value.replace(/[^0-9]/g, ''));
                let formattedValue = formatNumberWithCommas(numericValue);
                loanAmount.value = formattedValue;
            }

            function formatNumberWithCommas(number) {
                let parts = number.toString().split(".");
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                return parts.join(".");
            }

            function submitForm() {
                const form = document.getElementById("form_personal_info");
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
                    <li class="breadcrumb-item active" aria-current="page">Thông tin cá nhân</li>
                </ol>
            </nav>
        </div>
    </section>
    <section id="wrapper-content">
        <div class="wrapper-title">
            <div class="container">
                <h2 class="head-title text-center">Thông tin cá nhân</h2>
            </div>
        </div>
        <div class="container">
            <div class="box-credit-form py-5 d-flex flex-column">
                <form id="form_personal_info" method="POST" action="{{ route('personal_info.store') }}">
                    @csrf
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100 ">
                            <div class="w-35 pt-2"><b class="fs-6">Họ và tên</b> <span
                                    class="text-danger fw-bold">*</span></div>
                            <div class="w-65">
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100">
                            <div class="w-35 pt-2"><b class="fs-6">Số CMND / CCCD</b> <span
                                class="text-danger fw-bold">*</span></div>
                            <div class="w-65">
                                <input type="text" name="id_number" value="{{ old('id_number') }}" class="form-control @error('id_number') is-invalid @enderror" required autofocus>
                                @error('id_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100">
                            <div class="w-35 pt-2"><b class="fs-6">Điện thoại di động</b> <span
                                    class="text-danger fw-bold">*</span></div>
                            <div class="w-65">
                                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control @error('phone') is-invalid @enderror" required autofocus>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100">
                            <div class="w-35 pt-2"><b class="fs-6">Giới tính</b> <span
                                    class="text-danger fw-bold">*</span></div>
                            <div class="w-65">
                                <select name="gender" value="{{ old('gender') }}" class="form-select @error('gender') is-invalid @enderror" required autofocus>
                                    <option selected>Lựa chọn giới tính</option>
                                    <option value="1">Nam</option>
                                    <option value="0">Nữ</option>
                                    <option value="">Khác</option>
                                </select>
                                @error('gender')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100">
                            <div class="w-35 pt-2"><b class="fs-6">Ngày tháng năm sinh</b> <span
                                    class="text-danger fw-bold">*</span></div>
                            <div class="w-65">
                                <input class="form-control @error('birthday') is-invalid @enderror" required autofocus type="date" name="birthday" value="{{ old('birthday') }}"/>
                                @error('birthday')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100">
                            <div class="w-35 pt-2"><b class="fs-6">Nghề nghiệp</b> <span
                                    class="text-danger fw-bold">*</span></div>
                            <div class="w-65">
                                <input type="text" name="career" value="{{ old('career') }}" class="form-control @error('career') is-invalid @enderror" required autofocus>
                                @error('career')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100">
                            <div class="w-35 pt-2"><b class="fs-6">Khoản thu nhập</b> <span
                                class="text-danger fw-bold">*</span></div>
                            <div class="w-65">
                                <input type="text" name="income" value="{{ old('income') }}" id="income" class="form-control @error('income') is-invalid @enderror" required autofocus oninput="onChangeInCome()">
                                @error('income')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100">
                            <div class="w-35 pt-2"><b class="fs-6">Mục đích vay</b> <span
                                class="text-danger fw-bold">*</span></div>
                            <div class="w-65">
                                <input type="text" name="loan_purpose" value="{{ old('loan_purpose') }}" class="form-control @error('loan_purpose') is-invalid @enderror" required autofocus>
                                @error('loan_purpose')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100">
                            <div class="w-35 pt-2"><b class="fs-6">Địa chỉ</b> <span
                                class="text-danger fw-bold">*</span></div>
                            <div class="w-65">
                                <input type="text" name="address" value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror" required autofocus>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100">
                            <div class="w-35 pt-2"><b class="fs-6">SĐT người thân</b> <span
                                class="text-danger fw-bold">*</span></div>
                            <div class="w-65">
                                <input type="text" name="relative_phone1" value="{{ old('relative_phone1') }}" class="form-control @error('relative_phone1') is-invalid @enderror" required autofocus>
                                @error('relative_phone1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100">
                            <div class="w-35 pt-2"><b class="fs-6">Mối quan hệ người thân</b> <span
                                class="text-danger fw-bold">*</span></div>
                            <div class="w-65">
                                <input type="text" name="relative_relationship1" value="{{ old('relative_relationship1') }}" class="form-control @error('relative_relationship1') is-invalid @enderror" required autofocus>
                                @error('relative_relationship1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100">
                            <div class="w-35 pt-2"><b class="fs-6">SĐT người thân</b> <span
                                class="text-danger fw-bold">*</span></div>
                            <div class="w-65">
                                <input type="text" name="relative_phone2" value="{{ old('relative_phone2') }}" class="form-control @error('relative_phone2') is-invalid @enderror" required autofocus>
                                @error('relative_phone2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100">
                            <div class="w-35 pt-2"><b class="fs-6">Mối quan hệ người thân</b> <span
                                class="text-danger fw-bold">*</span></div>
                            <div class="w-65">
                                <input type="text" name="relative_relationship2" value="{{ old('relative_relationship2') }}" class="form-control @error('relative_relationship2') is-invalid @enderror" required autofocus>
                                @error('relative_relationship2')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-around flex-ct-column w-100">
                            <div class="w-35 pt-2"><b class="fs-6">Mã bảo vệ</b> </div>
                            <div class="w-65 d-flex justify-content-between flex-ct-row">
                                <input type="text" name="captcha" class="form-control me-2 @error('captcha') is-invalid @enderror" required autofocus>
                                <img src="{{ captcha_src() }}" alt="Captcha" width="100" height="40" />
                            </div>
                            @error('captcha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </form>
                <a href="#" class="btn btn-primary mt-4" onclick="submitForm()">TIẾP TỤC</a>
            </div>
        </div>
    </section>
</x-app-layout>
