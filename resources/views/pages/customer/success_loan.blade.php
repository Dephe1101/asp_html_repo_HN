<x-app-layout>
    <x-slot name="head"></x-slot>
    <section id="breadcrumb-wrapper">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="/">Vay online</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Vay thành công</li>
                </ol>
            </nav>
        </div>
    </section>
    <section id="wrapper-content">
        <div class="container">
            <div class="box-credit-form py-5 d-flex flex-column">
                <form>
                    <div class="box-upload-img">
                        <div class="mb-3 w-100 item-upload-img border-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="#198754" class="bi bi-check-circle" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                              </svg>
                              <p class="mt-3" style="color: #da2028; font-size: 20px;"><b>Gửi thông tin vay thành công !</b></p>
                              <p>Chúc mừng bạn đã gửi thông tin vay thành công vui lòng kiểm tra lại thông tin</p>
                        </div>
                    </div>
                </form>
                <div class="mb-3 ">
                    <a href="{{ route('contact') }}" class="btn btn-primary w-100 mb-3">LIÊN HỆ CHĂM SÓC KHÁCH HÀNG ĐỂ DUYỆT HỒ SƠ</a>
                    <p class="mt-3"><b>{{ $companyName }}</b></p>
                    <p class="mb-0"><b>MAIL: {{ $email }}</b></p>
                    @if (!empty($hotline))
                        <p class=""><b>HOTLINE: {{ $hotline[0]['value'] }}</b></p>
                    @endif
                    <p class=""><b>{{ $slogan2 }}</b></p>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
