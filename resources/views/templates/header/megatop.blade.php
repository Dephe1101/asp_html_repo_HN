<div css-module="header">
    <div css-module="container">
        <div css-module="cls">
            <div css-module="head_wrapper">
                <div css-module="top-header-l">
                    <div css-module="logo-wrap">
                        <a href="/" title="{{ $logo->name ?? '' }}" css-module="logo" rel="nofollow">
                            @if (isset($logo->value) && !empty($logo->value))
                                <img css-module="logo_img" src="{{ $logo->value }}" alt="{{ $logo->name }}" />
                            @else
                                <span>
                                    {{ $siteName ? $siteName : config('app.name') }}
                                </span>
                            @endif
                        </a>
                    </div>
                    @if (!empty($hotline[0]['text']))
                    <div css-module="contact_head">
                        <div css-module="contact_top">
                            <div css-module="contact_top_l">
                                <span>
                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                        viewBox="0 0 480.56 480.56" style="enable-background: new 0 0 480.56 480.56"
                                        xml:space="preserve">
                                        <g>
                                            <g>
                                                <path
                                                    d="M365.354,317.9c-15.7-15.5-35.3-15.5-50.9,0c-11.9,11.8-23.8,23.6-35.5,35.6c-3.2,3.3-5.9,4-9.8,1.8 c-7.7-4.2-15.9-7.6-23.3-12.2c-34.5-21.7-63.4-49.6-89-81c-12.7-15.6-24-32.3-31.9-51.1c-1.6-3.8-1.3-6.3,1.8-9.4 c11.9-11.5,23.5-23.3,35.2-35.1c16.3-16.4,16.3-35.6-0.1-52.1c-9.3-9.4-18.6-18.6-27.9-28c-9.6-9.6-19.1-19.3-28.8-28.8 c-15.7-15.3-35.3-15.3-50.9,0.1c-12,11.8-23.5,23.9-35.7,35.5c-11.3,10.7-17,23.8-18.2,39.1c-1.9,24.9,4.2,48.4,12.8,71.3 c17.6,47.4,44.4,89.5,76.9,128.1c43.9,52.2,96.3,93.5,157.6,123.3c27.6,13.4,56.2,23.7,87.3,25.4c21.4,1.2,40-4.2,54.9-20.9 c10.2-11.4,21.7-21.8,32.5-32.7c16-16.2,16.1-35.8,0.2-51.8C403.554,355.9,384.454,336.9,365.354,317.9z">
                                                </path>
                                                <path
                                                    d="M346.254,238.2l36.9-6.3c-5.8-33.9-21.8-64.6-46.1-89c-25.7-25.7-58.2-41.9-94-46.9l-5.2,37.1 c27.7,3.9,52.9,16.4,72.8,36.3C329.454,188.2,341.754,212,346.254,238.2z">
                                                </path>
                                                <path
                                                    d="M403.954,77.8c-42.6-42.6-96.5-69.5-156-77.8l-5.2,37.1c51.4,7.2,98,30.5,134.8,67.2c34.9,34.9,57.8,79,66.1,127.5 l36.9-6.3C470.854,169.3,444.354,118.3,403.954,77.8z">
                                                </path>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                                Hotline:
                                <strong>
                                    @foreach ($hotline ?? '' as $item)
                                        @if (count($hotline ?? '') > 1 && $loop->index > 0)
                                            -
                                        @endif
                                        <a href="tel:{{ $item['value'] }}" css-module="cl_white" rel="nofollow" title="{{ $item['text'] }}">{{ $item['text'] }}</a>
                                    @endforeach
                                </strong>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div css-module="top-mega-menu">
                    <ul id="megatop" css-module="menu megatop" class="mypopup">
                        @foreach ($megatop as $item)
                            <li css-module="level_0" class="sort">
                                <a href="{{ $item->link }}" css-module="menu_item_t" title="{{ $item->title }}" rel="nofollow">
                                    {{ $item->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
