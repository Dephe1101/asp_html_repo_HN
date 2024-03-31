@if(!empty($email))

@php
    
@endphp
    <div class="container">
        <h2 class="slogan text-center mt-4">{{ $slogan1 }}</h2>
        <h1 class="mt-3"><strong>{{ $companyName }}</strong>
            <strong><br><br>MAIL:</strong> 
            <strong><a
            href="mailto:{{ $email }}">{{ $email }}</a><br></strong>
            <strong>HOTLINE:</strong>
            <strong>{{$hotline[0]['value']}}<br></strong>
            <em><strong><br></strong></em><strong>{{ $slogan2 }}</strong></h1>
        <div class="text-center mt-4 mission-content">
            <button type="button" class="btn btn-outline-primary">100% Online</button>
            <button type="button" class="btn btn-outline-success">Thủ Tục Đơn giản</button>
            <button type="button" class="btn btn-outline-danger">Không mất phí</button>
            <button type="button" class="btn btn-outline-warning">Giải Ngân Nhanh</button>
        </div>
    </div>
@endif
