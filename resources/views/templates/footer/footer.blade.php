@php
use KSVTServiceManager\Helpers\StringHelper;
@endphp
<div id="layout_footer_wrapper">
    <div css-module="footer" style="background-image: url({{asset('assets/images/bg-footer.jpeg')}})">
        <div css-module="footer_inner container">
            <div css-module="flex flex-column-mobile">
                <!-- Left footer -->
                <div css-module="left footer-left">
                    <div class="left-top">
                        <p css-module="uppercase bold h4-text">
                            Trung tâm phân phân phối sim số đẹp và thẻ cào các mạng
                        </p>
                        {!!$footerInfo!!}
                    </div>
                </div>

                <div css-module="center footer-center">
                    <div css-module="navigation_sub">
                            <ul css-module="menu-bottom">
                                <li css-module="level0" class="first-item">
                                    <span css-module="click-mobile" data-id="menu-sub1"></span>
                                    <span>Hỗ trợ khách hàng</span>
                                    @if (count($customerSupportMenu) > 0)
                                        <ul id="menu-sub1">
                                            @foreach ($customerSupportMenu as $key => $item)
                                                <li class="level1 {{$key == 0 ? 'first-sitem' : 'mid-sitem'}}">
                                                    <a href="{{StringHelper::getUrl($item->link, false)}}" title="{{$item->title}}" rel="nofollow" data-relforced="true">
                                                        {{$item->title}}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            </ul>
                        <div css-module="clear"></div>
                    </div>
                </div>

                <div css-module="footer-right" class="right">
                    <div css-module="block" class="block_facebook facebook_0 blocks_banner blocks0" id="block_id_125">
                        <div css-module="block_title">
                            <span>Kết nối</span>
                        </div>
                        <div css-module="share_column">
                            <div css-module="wrapper_column">
                                <span>
                                    <svg width="30px" height="30px" version="1.1" id="Capa_1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        x="0px" y="0px" viewBox="0 0 430.113 430.114"
                                        style="enable-background: new 0 0 430.113 430.114" xml:space="preserve">
                                        <g>
                                            <path id="Facebook"
                                                d="M158.081,83.3c0,10.839,0,59.218,0,59.218h-43.385v72.412h43.385v215.183h89.122V214.936h59.805 c0,0,5.601-34.721,8.316-72.685c-7.784,0-67.784,0-67.784,0s0-42.127,0-49.511c0-7.4,9.717-17.354,19.321-17.354 c9.586,0,29.818,0,48.557,0c0-9.859,0-43.924,0-75.385c-25.016,0-53.476,0-66.021,0C155.878-0.004,158.081,72.48,158.081,83.3z">
                                            </path>
                                        </g>
                                    </svg>
                                </span>
                                <span>
                                    <svg width="30px" height="30px" version="1.1" id="Capa_1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        x="0px" y="0px" viewBox="0 0 491.858 491.858"
                                        style="enable-background: new 0 0 491.858 491.858" xml:space="preserve">
                                        <g>
                                            <g>
                                                <g>
                                                    <path d="M377.472,224.957H201.319v58.718H308.79c-16.032,51.048-63.714,88.077-120.055,88.077 c-69.492,0-125.823-56.335-125.823-125.824c0-69.492,56.333-125.823,125.823-125.823c34.994,0,66.645,14.289,89.452,37.346 l42.622-46.328c-34.04-33.355-80.65-53.929-132.074-53.929C84.5,57.193,0,141.693,0,245.928s84.5,188.737,188.736,188.737 c91.307,0,171.248-64.844,188.737-150.989v-58.718L377.472,224.957L377.472,224.957z"></path>
                                                    <polygon points="491.858,224.857 455.827,224.857 455.827,188.826 424.941,188.826 424.941,224.857 388.91,224.857  388.91,255.74 424.941,255.74 424.941,291.772 455.827,291.772 455.827,255.74 491.858,255.74 			"></polygon>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                                <span>
                                    <svg width="30px" height="30px" version="1.1" id="Capa_1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        x="0px" y="0px" viewBox="0 0 612 612" style="enable-background: new 0 0 612 612"
                                        xml:space="preserve">
                                        <g>
                                            <g>
                                                <path class="svg" style="fill: #010002" d="M612,116.258c-22.525,9.981-46.694,16.75-72.088,19.772c25.929-15.527,45.777-40.155,55.184-69.411 c-24.322,14.379-51.169,24.82-79.775,30.48c-22.907-24.437-55.49-39.658-91.63-39.658c-69.334,0-125.551,56.217-125.551,125.513 c0,9.828,1.109,19.427,3.251,28.606C197.065,206.32,104.556,156.337,42.641,80.386c-10.823,18.51-16.98,40.078-16.98,63.101 c0,43.559,22.181,81.993,55.835,104.479c-20.575-0.688-39.926-6.348-56.867-15.756v1.568c0,60.806,43.291,111.554,100.693,123.104 c-10.517,2.83-21.607,4.398-33.08,4.398c-8.107,0-15.947-0.803-23.634-2.333c15.985,49.907,62.336,86.199,117.253,87.194 c-42.947,33.654-97.099,53.655-155.916,53.655c-10.134,0-20.116-0.612-29.944-1.721c55.567,35.681,121.536,56.485,192.438,56.485 c230.948,0,357.188-191.291,357.188-357.188l-0.421-16.253C573.872,163.526,595.211,141.422,612,116.258z">
                                                </path>
                                            </g>
                                        </g>
                                    </svg>
                                </span>
                                <span>
                                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="90px"
                                        height="90px" viewBox="0 0 90 90" style="enable-background: new 0 0 90 90"
                                        xml:space="preserve">
                                        <g>
                                            <path id="YouTube" d="M70.939,65.832H66l0.023-2.869c0-1.275,1.047-2.318,2.326-2.318h0.315c1.282,0,2.332,1.043,2.332,2.318 L70.939,65.832z M52.413,59.684c-1.253,0-2.278,0.842-2.278,1.873V75.51c0,1.029,1.025,1.869,2.278,1.869 c1.258,0,2.284-0.84,2.284-1.869V61.557C54.697,60.525,53.671,59.684,52.413,59.684z M82.5,51.879v26.544 C82.5,84.79,76.979,90,70.23,90H19.771C13.02,90,7.5,84.79,7.5,78.423V51.879c0-6.367,5.52-11.578,12.271-11.578H70.23 C76.979,40.301,82.5,45.512,82.5,51.879z M23.137,81.305l-0.004-27.961l6.255,0.002v-4.143l-16.674-0.025v4.073l5.205,0.015v28.039 H23.137z M41.887,57.509h-5.215v14.931c0,2.16,0.131,3.24-0.008,3.621c-0.424,1.158-2.33,2.388-3.073,0.125 c-0.126-0.396-0.015-1.591-0.017-3.643l-0.021-15.034h-5.186l0.016,14.798c0.004,2.268-0.051,3.959,0.018,4.729 c0.127,1.357,0.082,2.939,1.341,3.843c2.346,1.69,6.843-0.252,7.968-2.668l-0.01,3.083l4.188,0.005L41.887,57.509L41.887,57.509z C55.137,84.072,58.578,80.631,58.57,74.607z M74.891,72.96l-3.91,0.021c-0.002,0.155-0.008,0.334-0.01,0.529v2.182 c0,1.168-0.965,2.119-2.137,2.119h-0.766c-1.174,0-2.139-0.951-2.139-2.119V75.45v-2.4v-3.097h8.954v-3.37 c0-2.463-0.063-4.925-0.267-6.333c-0.641-4.454-6.893-5.161-10.051-2.881c-0.991,0.712-1.748,1.665-2.188,2.945 c-0.444,1.281-0.665,3.031-0.665,5.254v7.41C61.714,85.296,76.676,83.555,74.891,72.96z M54.833,32.732 c0.269,0.654,0.687,1.184,1.254,1.584c0.56,0.394,1.276,0.592,2.134,0.592c0.752,0,1.418-0.203,1.998-0.622 c0.578-0.417,1.065-1.04,1.463-1.871l-0.099,2.046h5.813V9.74H62.82v19.24c0,1.042-0.858,1.895-1.907,1.895 c-1.043,0-1.904-0.853-1.904-1.895V9.74h-4.776v16.674c0,2.124,0.039,3.54,0.102,4.258C54.4,31.385,54.564,32.069,54.833,32.732z c1.345,0,2.497,0.264,3.459,0.781c0.967,0.52,1.713,1.195,2.23,2.028c0.527,0.836,0.885,1.695,1.076,2.574 c0.195,0.891,0.291,2.235,0.291,4.048v6.252c0,2.293-0.092,3.98-0.271,5.051c-0.177,1.074-0.557,2.07-1.146,3.004 c-0.58,0.924-1.329,1.615-2.237,2.056c-0.918,0.445-1.968,0.663-3.154,0.663c-1.325,0-2.441-0.183-3.361-0.565 c-0.923-0.38-1.636-0.953-2.144-1.714c-0.513-0.762-0.874-1.69-1.092-2.772c-0.219-1.081-0.323-2.707-0.323-4.874L37.217,18.77 L37.217,18.77z M41.77,28.59c0,1.4,1.042,2.543,2.311,2.543c1.27,0,2.308-1.143,2.308-2.543V15.43c0-1.398-1.038-2.541-2.308-2.541 c-1.269,0-2.311,1.143-2.311,2.541V28.59z M25.682,35.235h5.484l0.006-18.96l6.48-16.242h-5.998l-3.445,12.064L24.715,0h-5.936 l6.894,16.284L25.682,35.235z"></path>
                                        </g>
                                    </svg>
                                </span>
                            </div>
                            <div css-module="img_register">
                                <p>
                                    <a class="dmca-badge" href="" title="Tình trạng bảo vệ DMCA.com" rel="nofollow" data-relforced="true"><img
                                            alt="Tình trạng bảo vệ DMCA.com"
                                            src="https://images.dmca.com/Badges/dmca-badge-w150-5x1-09.png?ID=0ede9a19-5d2e-4939-aece-a476acfcfa82" /></a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div css-module="clear"></div>

        <div css-module="footer_bottom">
            <div css-module="container">
                <div css-module="flex flex-center">
                    <div css-module="copy-right">
                        {!! $copyright ?? '' !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ mix('assets/js/app.js') }}" defer></script>
