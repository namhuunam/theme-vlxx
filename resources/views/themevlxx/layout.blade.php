@extends('themes::themevlxx.layout_core')

@php
    $menu = \Ophim\Core\Models\Menu::getTree();
    $logo = setting('site_logo', '');
    preg_match('@src="([^"]+)"@', $logo, $match);

    // will return /images/image.jpg
    $logo = array_pop($match);
@endphp

@push('header')

    @if (!(new \Jenssegers\Agent\Agent())->isDesktop())
        <link rel="stylesheet" type="text/css" href="/themes/vlxx/static/css/mobile-default.css?v=1.0.5" />
    @else
        <link rel="stylesheet" type="text/css" href="/themes/vlxx/static/css/desktop-default.css?v=1.0.5" />
    @endif
    <script type="text/javascript" src="/themes/vlxx/static/js/jquery.min.js"></script>
    @if (!(new \Jenssegers\Agent\Agent())->isDesktop())
        <script type="text/javascript" src="/themes/vlxx/static/js/mobile-default.js"></script>
    @else
        <script type="text/javascript" src="/themes/vlxx/static/js/desktop-default.js"></script>
    @endif
    <script type="text/javascript" src="/themes/vlxx/static/js/jquery.lazyload.min.js"></script>
@endpush

@section('body')
    <div id="wrapper">
        @include('themes::themevlxx.inc.header')
        @yield('content')
        @if (get_theme_option('ads_preload'))
            {!! get_theme_option('ads_preload') !!}
        @endif
        <div id="footer">
            <footer>
                <div class="web-link" style="display: none">
                    <h2 class="breadcrumb" style="margin: 10px 0 0 0 !important">Liên kết</h2>
                    <a title="Tai video Youtube" href="https://tainhanh.net/youtube" target="_blank"><span
                            class="icon icon-youtube" style="color: #ff9900;">Tải video YouTube</span></a>
                    <a title="Suongvl" href="https://suongvl.cc/" target="_blank"><span
                            class="icon icon-phim69">Suongvl</span></a>
                </div>
                <div class="search-history">
                    <h2 class="breadcrumb" style="margin: 0 5px !important;">Top tìm kiếm</h2>
                    @if (!empty($searchHistory))
                        @foreach ($searchHistory as $history)
                            <a href="?search={{ urlencode($history) }}" title="{{ $history }}">{{ $history }}</a>
                        @endforeach
                    @else
                        <p>Chưa có tìm kiếm nào.</p>
                    @endif
                </div>
                <div class="footer-wrap">
                    <p>VLXX.COM là web xem <a title="phim sex" href="https://vlxx.mobi"><span class="url">phim
                                sex</span></a> dành cho người lớn trên 19 tuổi, giúp bạn giải trí, thỏa mãn sinh lý, dưới 19
                        tuổi xin vui lòng quay ra.</p>
                    <p>Trang web này không đăng tải clip sex Việt Nam, video sex trẻ em. Nội dung phim được dàn dựng từ
                        trước, hoàn toàn không có thật, người xem tuyệt đối không bắt chước hành động trong phim, tránh vi
                        phạm pháp luật.</p>
                    <div style="font-size: 12px;text-align: center;color: #dadada;opacity: .8;">
                        <p>© 2023 VLXX.COM</p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
@endsection

@section('footer')
    @if (get_theme_option('ads_catfish'))
        <div id="catfish" style="width: 100%;position:fixed;bottom:0;left:0;z-index:222" class="mp-adz">
            <div style="margin:0 auto;text-align: center;overflow: visible;" id="container-ads">
                <div id="hide_catfish"><a
                        style="font-size:12px; font-weight:bold;background: #ff8a00; padding: 2px; color: #000;display: inline-block;padding: 3px 6px;color: #FFF;background-color: rgba(0,0,0,0.7);border: .1px solid #FFF;"
                        onclick="jQuery('#catfish').fadeOut();">Đóng quảng cáo</a></div>
                <div id="catfish_content" style="z-index:999999;">
                    {!! get_theme_option('ads_catfish') !!}
                </div>
            </div>
        </div>
    @endif

    <div id="footer_fixed_ads"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    <div id="fb-root"></div>

    <script>
        window.fbAsyncInit = function() {
            FB.init({
                appId: '{{ setting('social_facebook_app_id') }}',
                xfbml: true,
                version: 'v5.0'
            });
            FB.AppEvents.logPageView();
        };

        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) {
                return;
            }
            js = d.createElement(s);
            js.id = id;
            js.src = "https://connect.facebook.net/vi_VN/sdk.js";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));
    </script>

    {!! setting('site_scripts_google_analytics') !!}
@endsection
