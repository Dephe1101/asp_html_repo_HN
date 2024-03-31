@php
$hotline = !empty($hotline) ? $hotline[array_rand($hotline)] : null;
@endphp

@if (isset($hotline['value']) && !empty($hotline['value']))
    <div css-module="hot_call">
        <div css-module="hotline-phone-ring-wrap">
            <div css-module="hotline-phone-ring">
                <div css-module="quick-alo-ph-circle"></div>
                <div css-module="quick-alo-ph-circle-fill"></div>
                <div css-module="quick-alo-ph-img-circle">
                    <a href="tel:{{ $hotline['value'] }}" css-module="pps-btn-img" rel="nofollow" data-relforced="true" title="{{ $hotline['value'] }}">
                        <img src="{{ asset('assets/images/icon-phone.png') }}" alt="Số điện thoại" width="50" />
                    </a>
                </div>
            </div>
            <div css-module="hotline-bar" class="d-none d-md-block">
                <a href="tel:{{ $hotline['value'] }}" rel="nofollow" data-relforced="true" title="{{ $hotline['text'] }}">
                    <span class="text-hotline">{{ $hotline['text'] }}</span>
                </a>
            </div>
        </div>
    </div>
@endif
