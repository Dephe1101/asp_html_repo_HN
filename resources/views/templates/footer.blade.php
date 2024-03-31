@php
    use App\Helpers\StringHelper;
@endphp
<footer>
    <div class="org-info">
      <div class="container d-flex align-items-center justify-content-between">
        @if(!empty($hotline))
            <a href="tel:{{$hotline[0]['value']}}" title="{{$hotline[0]['value']}}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z"/>
            </svg>
            <strong>{{$hotline[0]['value']}}</strong>
            </a>
        @endif
        @if(!empty($email))
            <a href="mailto:{{ $email }}" title="{{ $email }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope" viewBox="0 0 16 16">
                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/>
            </svg>
            <strong>{{ $email }}</strong>
            </a>
        @endif
        <a href="https://www.google.com/maps" title="maps">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
          </svg>
          <strong>Điểm giới thiệu dịch vụ gần nhất</strong>
        </a>
      </div>
    </div>
    <div class="container footer-main">
      <div class="row">
        <div class="col-lg-4">
            <p class="title">LIÊN KẾT NHANH</p>
            <ol class="list-group">
                @foreach ($customerSupportMenu as $item)
                    <li class="list-group-item"><a href="{{ StringHelper::getUrl($item->link, false) }}" title="{{ $item->title }}">{{ $item->title }}</a></li>
                @endforeach
            </ol>
        </div>
        {!! $footerInfo !!}
        <div class="col-lg-3">
            {!! $footerDownload !!}
        </div>
      </div>
    </div>
    <hr class="headline"/>
    {!! $copyright !!}
</footer>

<div class="sticky-bottom d-none-dk mt-4">
    <nav class="navbar box-footer">
        <a href="/" class="text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door"
            style="{{ request()->path() === '/' ? 'fill:#FF4C3B;' : '' }}" viewBox="0 0 16 16">
            <path
                d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146ZM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4H2.5Z" />
            </svg>
            <p class="mb-0 {{ request()->path() === '/' ? 'footer-nav-active' : '' }}">Trang chủ</p>
        </a>
        <a href="{{ route('wallet.show') }}" class="text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-wallet" style="{{ (request()->path() === 'vi-tien' || request()->path() === 'danh-sach-rut-tien') ? 'fill:#FF4C3B;' : '' }}"
            viewBox="0 0 16 16">
            <path
                d="M0 3a2 2 0 0 1 2-2h13.5a.5.5 0 0 1 0 1H15v2a1 1 0 0 1 1 1v8.5a1.5 1.5 0 0 1-1.5 1.5h-12A2.5 2.5 0 0 1 0 12.5V3zm1 1.732V12.5A1.5 1.5 0 0 0 2.5 14h12a.5.5 0 0 0 .5-.5V5H2a1.99 1.99 0 0 1-1-.268zM1 3a1 1 0 0 0 1 1h12V2H2a1 1 0 0 0-1 1z" />
            </svg>
            <p class="mb-0 {{ (request()->path() === 'vi-tien' || request()->path() === 'danh-sach-rut-tien') ? 'footer-nav-active' : '' }}">Ví tiền</p>
        </a>
        <a href="{{ route('contact') }}" class="text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
            class="bi bi-file-earmark-text" style="{{ request()->path() === 'lien-he' ? 'fill:#FF4C3B;' : '' }}" viewBox="0 0 16 16">
            <path
                d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5z" />
            <path
                d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5L9.5 0zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5z" />
            </svg>
            <p class="mb-0 {{ request()->path() === 'lien-he' ? 'footer-nav-active' : '' }}">Liên hệ</p>
        </a>
        <a href="{{ route('brief.show') }}" class="text-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" style="{{ request()->path() === 'ho-so' ? 'fill:#FF4C3B;' : '' }}"
            viewBox="0 0 16 16">
            <path
                d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4Zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10Z" />
            </svg>
            <p class="mb-0 {{ request()->path() === 'ho-so' ? 'footer-nav-active' : '' }}">Hồ sơ</p>
        </a>
    </nav>
</div>