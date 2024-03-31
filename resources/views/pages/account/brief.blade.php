@php
    use App\Helpers\PriceHelper;
    use App\Helpers\DateHelper;
@endphp
<x-blank-layout>
    <x-slot name="head">
        <script>
            function submitForm() {
                const form = document.getElementById("logout");
                form.submit();
            }
        </script>
    </x-slot>
    <div class="container p-0">
        <div class="box-info-mb d-flex flex-column" style="background-color: #dadada;">
            <div class="card p-3 border-0 mb-3" style="background-color: #dadada;">
                <div class="row d-flex justify-content-between">
                    <div class="col-4">
                        @php
                            $avatar = $customer->portrait ? $customer->portrait : Vite::asset('resources/assets/images/teamwork.png');
                        @endphp
                        <img src="{{ $avatar }}" width="100" height="100" class="img-fluid rounded-start"
                            alt="ACS VIỆT NAM">
                    </div>
                    <div class="col-8">
                        <div class="card-body">
                            <p class="mb-2 fs-6 text"><b>{{ $customer->name }}</b></p>
                            <p class="fs-6 text"><b>{{ $customer->phone }}</b></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-info-mb d-flex flex-column">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                <li class="nav-item">
                  <a class="nav-link row d-flex justify-content-around" aria-current="page" href="{{ route('account_information.show') }}">
                    <div class="col-3 ps-4 pt-2 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z"></path>
                          </svg>
                    </div>
                    <div class="col-9 ps-0">
                      <p class="footer-nav-active mb-0"><b style="font-size: 14px; color: #222;">Thông tin tài khoản</b><br> <span style="font-size: 12px;color: #777; text-transform: capitalize;">Hiển thị thông tin tài khoản</span></p>
                    </div>
                  </a>  
                </li>
                <li class="nav-item">
                  <a class="nav-link row d-flex justify-content-around" aria-current="page" href="{{ route('loan_contract.show') }}">
                    <div class="col-3 ps-4 pt-2 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        fill="currentColor" class="bi bi-file-text" viewBox="0 0 16 16">
                        <path
                            d="M5 4a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8a.5.5 0 0 0 0 1h6a.5.5 0 0 0 0-1H5zm0 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1H5z" />
                        <path
                            d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2zm10-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1z" />
                    </svg>
                    </div>
                    <div class="col-9 ps-0">
                      <p class="footer-nav-active mb-0"><b style="font-size: 14px; color: #222;">Hợp đồng vay</b><br>
                         <span style="font-size: 12px;color: #777; text-transform: capitalize;">Danh sách các hợp đồng vay của khách</span></p>
                    </div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link row d-flex justify-content-around" aria-current="page" href="{{ route('bank_link.show') }}">
                    <div class="col-3 ps-4 pt-2 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        fill="currentColor" class="bi bi-plus-square" viewBox="0 0 16 16">
                        <path
                            d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                        <path
                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                    </svg>
                    </div>
                    <div class="col-9 ps-0">
                      <p class="footer-nav-active mb-0"><b style="font-size: 14px; color: #222;">Liên kết tài khoản ngân hàng</b><br> 
                        <span style="font-size: 12px;color: #777; text-transform: capitalize;">Tiến hành liên kết tài khoản ngân hàng</span></p>
                    </div>
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link row d-flex justify-content-around" aria-current="page" href="{{ route('account_volatility.show') }}">
                    <div class="col-3 ps-4 pt-2 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-currency-exchange" viewBox="0 0 16 16">
                            <path d="M0 5a5.002 5.002 0 0 0 4.027 4.905 6.46 6.46 0 0 1 .544-2.073C3.695 7.536 3.132 6.864 3 5.91h-.5v-.426h.466V5.05c0-.046 0-.093.004-.135H2.5v-.427h.511C3.236 3.24 4.213 2.5 5.681 2.5c.316 0 .59.031.819.085v.733a3.46 3.46 0 0 0-.815-.082c-.919 0-1.538.466-1.734 1.252h1.917v.427h-1.98c-.003.046-.003.097-.003.147v.422h1.983v.427H3.93c.118.602.468 1.03 1.005 1.229a6.5 6.5 0 0 1 4.97-3.113A5.002 5.002 0 0 0 0 5zm16 5.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0zm-7.75 1.322c.069.835.746 1.485 1.964 1.562V14h.54v-.62c1.259-.086 1.996-.74 1.996-1.69 0-.865-.563-1.31-1.57-1.54l-.426-.1V8.374c.54.06.884.347.966.745h.948c-.07-.804-.779-1.433-1.914-1.502V7h-.54v.629c-1.076.103-1.808.732-1.808 1.622 0 .787.544 1.288 1.45 1.493l.358.085v1.78c-.554-.08-.92-.376-1.003-.787H8.25zm1.96-1.895c-.532-.12-.82-.364-.82-.732 0-.41.311-.719.824-.809v1.54h-.005zm.622 1.044c.645.145.943.38.943.796 0 .474-.37.8-1.02.86v-1.674l.077.018z"/>
                          </svg>
                    </div>
                    <div class="col-9 ps-0">
                      <p class="footer-nav-active mb-0"><b style="font-size: 14px; color: #222;">Biến động tài khoản</b><br>
                         <span style="font-size: 12px;color: #777; text-transform: capitalize;">Lịch sử thay đổi thông tin tài chính</span></p>
                    </div>
                  </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link row d-flex justify-content-around" aria-current="page" href="{{ route('update_password.show') }}">
                      <div class="col-3 ps-4 pt-2 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        fill="currentColor" class="bi bi-shield-lock" viewBox="0 0 16 16">
                        <path
                            d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z" />
                        <path
                            d="M9.5 6.5a1.5 1.5 0 0 1-1 1.415l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99a1.5 1.5 0 1 1 2-1.415z" />
                    </svg>
                      </div>
                      <div class="col-9 ps-0">
                        <p class="footer-nav-active mb-0"><b style="font-size: 14px; color: #222;">Đổi mật khẩu</b><br>
                           <span style="font-size: 12px;color: #777; text-transform: capitalize;">Thay đổi mật khẩu của tài khoản</span></p>
                      </div>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link row d-flex justify-content-around" aria-current="page" href="{{ route('contact') }}">
                      <div class="col-3 ps-4 pt-2 text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        fill="currentColor" class="bi bi-shield-lock" viewBox="0 0 16 16">
                        <path
                            d="M5.338 1.59a61.44 61.44 0 0 0-2.837.856.481.481 0 0 0-.328.39c-.554 4.157.726 7.19 2.253 9.188a10.725 10.725 0 0 0 2.287 2.233c.346.244.652.42.893.533.12.057.218.095.293.118a.55.55 0 0 0 .101.025.615.615 0 0 0 .1-.025c.076-.023.174-.061.294-.118.24-.113.547-.29.893-.533a10.726 10.726 0 0 0 2.287-2.233c1.527-1.997 2.807-5.031 2.253-9.188a.48.48 0 0 0-.328-.39c-.651-.213-1.75-.56-2.837-.855C9.552 1.29 8.531 1.067 8 1.067c-.53 0-1.552.223-2.662.524zM5.072.56C6.157.265 7.31 0 8 0s1.843.265 2.928.56c1.11.3 2.229.655 2.887.87a1.54 1.54 0 0 1 1.044 1.262c.596 4.477-.787 7.795-2.465 9.99a11.775 11.775 0 0 1-2.517 2.453 7.159 7.159 0 0 1-1.048.625c-.28.132-.581.24-.829.24s-.548-.108-.829-.24a7.158 7.158 0 0 1-1.048-.625 11.777 11.777 0 0 1-2.517-2.453C1.928 10.487.545 7.169 1.141 2.692A1.54 1.54 0 0 1 2.185 1.43 62.456 62.456 0 0 1 5.072.56z" />
                        <path
                            d="M9.5 6.5a1.5 1.5 0 0 1-1 1.415l.385 1.99a.5.5 0 0 1-.491.595h-.788a.5.5 0 0 1-.49-.595l.384-1.99a1.5 1.5 0 1 1 2-1.415z" />
                    </svg>
                      </div>
                      <div class="col-9 ps-0">
                        <p class="footer-nav-active mb-0"><b style="font-size: 14px; color: #222;">Liên hệ hỗ trợ</b><br>
                           <span style="font-size: 12px;color: #777; text-transform: capitalize;">Chăm sóc khách hàng 24/7</span></p>
                      </div>
                    </a>
                  </li>
              </ul>
        </div>
        <div class="px-2">
            <form id="logout" action="{{ route('logout') }}" method="POST">
                @csrf
                <a href="#" class="btn btn-danger w-100 mt-4" onclick="submitForm()">Đăng xuất</a>
            </form>
        </div>
    </div>
</x-blank-layout>