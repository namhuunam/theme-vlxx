@extends('themes::themevlxx.layout')

@section('content')
    <style>
        #player-wrapper {
            position: absolute;
            /* Position it absolutely within the parent */
            top: 0;
            left: 0;
            width: 100%;
            /* Full width of the parent */
            height: 100%;
            /* Full height of the parent */
        }
        
        /* Thêm style cho overlay logo khi sử dụng iframe embed */
        .video-player {
            position: relative;
        }
        
        .embed-logo {
            position: absolute;
            z-index: 10;
            pointer-events: none; /* Logo không chặn tương tác với video */
        }
        
        .embed-logo.top-right {
            top: 10px;
            right: 10px;
        }
        
        .embed-logo.top-left {
            top: 12px;
            left: 160px;
        }
        
        .embed-logo.bottom-right {
            bottom: 10px;
            right: 10px;
        }
        
        .embed-logo.bottom-left {
            bottom: 10px;
            left: 10px;
        }
        
        .embed-logo img {
            max-height: 22px;
            opacity: 0.8;
        }
    </style>
    <div id="container">
        <h2 id="page-title" class="breadcrumb" style="text-transform: none;">{{ $currentMovie->name }}</h2>
        <div id="video" data-id="{{ $currentMovie->id }}">
            <div class="video-player">
                <div id="player-wrapper" style="aspect-ratio: 16/9"></div>
                <!-- Logo overlay sẽ được thêm vào bằng JavaScript khi cần thiết -->
            </div>
            <div id="video-actions">
                @foreach ($currentMovie->episodes->where('slug', $episode->slug)->where('server', $episode->server) as $server)
                    <a onclick="chooseStreamingServer(this)" data-type="{{ $server->type }}" data-id="{{ $server->id }}"
                        data-link="{{ $server->link }}" class="streaming-server video-server bt_normal">
                        #{{ $loop->iteration }}
                    </a>
                @endforeach
                <div class="video-stats" style="float: right">
                    <span class="rating" id="rating-percentage">{{ $currentMovie->getRatingStar() }}</span>
                    <span class="views"><span>{{ vlxx_format_view($currentMovie->view_total) }}</span></span>
                </div>
            </div>
            <div class="clear"></div>
            <div class="video-info">
                <span class="video-code">{{ $currentMovie->origin_name }}</span>
                <span class="video-link">{{ $currentMovie->language }}</span>
            </div>
            <div class="clear"></div>
            <div class="video-content">
                <div class="video-description">{!! $currentMovie->content !!}</div>
                <div class="video-tags">
                    <div class="actress-tag">
                        {!! count($currentMovie->actors)
                            ? $currentMovie->actors->map(function ($actor) {
                                    return '<a href="' . $actor->getUrl() . '" tite="Diễn viên ' . $actor->name . '">' . $actor->name . '</a>';
                                })->implode(' ')
                            : 'Đang cập nhật' !!}
                    </div>
                    <div class="category-tag">
                        {!! $currentMovie->categories->map(function ($category) {
                                return '<a href="' . $category->getUrl() . '" tite="' . $category->name . '">' . $category->name . '</a>';
                            })->implode(' ') !!}
                    </div>
                </div>
            </div>
        </div>
        <div id="video-list">
            <h2 class="breadcrumb">Phim liên quan</h2>
            @foreach ($movie_related as $movie)
                <div id="video-1944" class="video-item">
                    <a title="{{$movie->name}}"
                        href="{{$movie->getUrl()}}">
                        <img class="video-image lazyload" src="{{$movie->getPosterUrl()}}"
                            data-original="{{$movie->getPosterUrl()}}" width="240px" height="180px"
                            alt="{{$movie->name}}" style="">
                        <div class="ribbon">{{$movie->language}}</div>
                    </a>
                    <div class="video-name">
                        <a title="{{$movie->name}}"
                            href="{{$movie->getUrl()}}">{{$movie->name}}</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <script>
        // Function to check if the device is mobile
        function isMobile() {
            return /Mobi|Android/i.test(navigator.userAgent);
        }

        // Select the video player div
        const videoPlayer = document.querySelector('.video-player');

        // Add the appropriate class
        if (isMobile()) {
            videoPlayer.classList.add('mobile');
        } else {
            videoPlayer.classList.add('desktop');
        }
    </script>
@endsection

@push('scripts')
    <script src="/themes/vlxx/static/player/skin/juicycodes.js"></script>
    <link href="/themes/vlxx/static/player/skin/juicycodes.css" rel="stylesheet" type="text/css">
    <script src="/themes/vlxx/static/player/jwplayer.js"></script>


    <script>
        var episode_id = {{ $episode->id }};
        const wrapper = document.getElementById('player-wrapper');
        const vastAds = "{{ Setting::get('jwplayer_advertising_file') }}";
        
        // Lưu thông tin logo để sử dụng
        const logoFile = "{{ Setting::get('jwplayer_logo_file') }}";
        const logoLink = "{{ Setting::get('jwplayer_logo_link') }}";
        const logoPosition = "{{ Setting::get('jwplayer_logo_position') }}" || "bottom-right";

        function chooseStreamingServer(el) {
            const type = el.dataset.type;
            const link = el.dataset.link.replace(/^http:\/\//i, 'https://');
            const id = el.dataset.id;

            const newUrl =
                location.protocol +
                "//" +
                location.host +
                location.pathname.replace(`-${episode_id}`, `-${id}`);

            history.pushState({
                path: newUrl
            }, "", newUrl);
            episode_id = id;

            Array.from(document.getElementsByClassName('streaming-server')).forEach(server => {
                server.classList.remove('bt_active');
            })
            el.classList.add('bt_active')

            renderPlayer(type, link, id);
        }

        function renderPlayer(type, link, id) {
            // Xóa logo overlay cũ nếu có
            const oldLogo = document.querySelector('.embed-logo');
            if (oldLogo) oldLogo.remove();
            
            if (type == 'embed') {
                if (vastAds) {
                    wrapper.innerHTML = `<div id="fake_jwplayer"></div>`;
                    const fake_player = jwplayer("fake_jwplayer");
                    const objSetupFake = {
                        key: "{{ Setting::get('jwplayer_license') }}",
                        aspectratio: "16:9",
                        width: "100%",
                        file: "/themes/vlxx/static/player/1s_blank.mp4",
                        volume: 100,
                        mute: false,
                        autostart: true,
                        advertising: {
                            tag: "{{ Setting::get('jwplayer_advertising_file') }}",
                            client: "vast",
                            vpaidmode: "insecure",
                            skipoffset: {{ (int) Setting::get('jwplayer_advertising_skipoffset') ?: 5 }}, // Bỏ qua quảng cáo trong vòng 5 giây
                            skipmessage: "Bỏ qua sau xx giây",
                            skiptext: "Bỏ qua"
                        }
                    };
                    fake_player.setup(objSetupFake);
                    fake_player.on('complete', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe sandbox = "allow-same-origin allow-scripts" width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                        addLogoOverlay(); // Thêm logo sau khi iframe được tạo
                    });
                    fake_player.on('adSkipped', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe sandbox = "allow-same-origin allow-scripts" width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                        addLogoOverlay(); // Thêm logo sau khi iframe được tạo
                    });
                    fake_player.on('adComplete', function(event) {
                        $("#fake_jwplayer").remove();
                        wrapper.innerHTML = `<iframe sandbox = "allow-same-origin allow-scripts" width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                        fake_player.remove();
                        addLogoOverlay(); // Thêm logo sau khi iframe được tạo
                    });
                } else {
                    if (wrapper) {
                        wrapper.innerHTML = `<iframe sandbox = "allow-same-origin allow-scripts" width="100%" height="100%" src="${link}" frameborder="0" scrolling="no"
                        allowfullscreen="" allow='autoplay'></iframe>`
                        addLogoOverlay(); // Thêm logo sau khi iframe được tạo
                    }
                }
                return;
            }

            if (type == 'm3u8' || type == 'mp4') {
                wrapper.innerHTML = `<div id="jwplayer"></div>`;
                const player = jwplayer("jwplayer");
                const objSetup = {
                    key: "{{ Setting::get('jwplayer_license') }}",
                    aspectratio: "16:9",
                    width: "100%",
                    file: link,
                    image: "{{ $currentMovie->getPosterUrl() }}",
                    autostart: true,
                    controls: true,
                    primary: "html5",
                    playbackRateControls: true,
                    playbackRates: [0.5, 0.75, 1, 1.5, 2],
                    // sharing: {
                    //     sites: [
                    //         "reddit",
                    //         "facebook",
                    //         "twitter",
                    //         "googleplus",
                    //         "email",
                    //         "linkedin",
                    //     ],
                    // },
                    volume: 100,
                    mute: false,
                    logo: {
                        file: logoFile,
                        link: logoLink,
                        position: logoPosition,
                        hide: false,
                        margin: 20
                    },
                    advertising: {
                        tag: "{{ Setting::get('jwplayer_advertising_file') }}",
                        client: "vast",
                        vpaidmode: "insecure",
                        skipoffset: {{ (int) Setting::get('jwplayer_advertising_skipoffset') ?: 5 }}, // Bỏ qua quảng cáo trong vòng 5 giây
                        skipmessage: "Bỏ qua sau xx giây",
                        skiptext: "Bỏ qua",
                        admessage: "Quảng cáo còn xx giây."
                    },
                    tracks: [{
                        "file": "/sub.vtt",
                        "kind": "captions",
                        label: "VN",
                        default: "true"
                    }],
                };

                if (type == 'm3u8') {
                    const segments_in_queue = 50;

                    var engine_config = {
                        debug: !1,
                        segments: {
                            forwardSegmentCount: 50,
                        },
                        loader: {
                            cachedSegmentExpiration: 864e5,
                            cachedSegmentsCount: 1e3,
                            requiredSegmentsPriority: segments_in_queue,
                            httpDownloadMaxPriority: 9,
                            httpDownloadProbability: 0.06,
                            httpDownloadProbabilityInterval: 1e3,
                            httpDownloadProbabilitySkipIfNoPeers: !0,
                            p2pDownloadMaxPriority: 50,
                            httpFailedSegmentTimeout: 500,
                            simultaneousP2PDownloads: 20,
                            simultaneousHttpDownloads: 2,
                            // httpDownloadInitialTimeout: 12e4,
                            // httpDownloadInitialTimeoutPerSegment: 17e3,
                            httpDownloadInitialTimeout: 0,
                            httpDownloadInitialTimeoutPerSegment: 17e3,
                            httpUseRanges: !0,
                            maxBufferLength: 300,
                            // useP2P: false,
                        },
                    };
                    // if (Hls.isSupported() && p2pml.hlsjs.Engine.isSupported()) {
                    //     var engine = new p2pml.hlsjs.Engine(engine_config);
                    //     player.setup(objSetup);
                    //     jwplayer_hls_provider.attach();
                    //     p2pml.hlsjs.initJwPlayer(player, {
                    //         liveSyncDurationCount: segments_in_queue, // To have at least 7 segments in queue
                    //         maxBufferLength: 300,
                    //         loader: engine.createLoaderClass(),
                    //     });
                    // } else {
                    player.setup(objSetup);
                    // }
                } else {
                    player.setup(objSetup);
                }

                player.addButton(
                    '<svg xmlns="http://www.w3.org/2000/svg" class="jw-svg-icon jw-svg-icon-rewind2" viewBox="0 0 240 240" focusable="false"><path d="m 25.993957,57.778 v 125.3 c 0.03604,2.63589 [...]',
                    "Forward 10 Seconds", () => player.seek(player.getPosition() + 10), "Forward 10 Seconds");
                player.addButton(
                    '<svg xmlns="http://www.w3.org/2000/svg" class="jw-svg-icon jw-svg-icon-rewind" viewBox="0 0 240 240" focusable="false"><path d="M113.2,131.078a21.589,21.589,0,0,0-17.7-10.6,2[...]',
                    "Rewind 10 Seconds", () => player.seek(player.getPosition() - 10), "Rewind 10 Seconds");

                const resumeData = 'OPCMS-PlayerPosition-' + id;

                player.on('ready', function() {
                    if (typeof(Storage) !== 'undefined') {
                        if (localStorage[resumeData] == '' || localStorage[resumeData] == 'undefined') {
                            console.log("No cookie for position found");
                            var currentPosition = 0;
                        } else {
                            if (localStorage[resumeData] == "null") {
                                localStorage[resumeData] = 0;
                            } else {
                                var currentPosition = localStorage[resumeData];
                            }
                            console.log("Position cookie found: " + localStorage[resumeData]);
                        }
                        player.once('play', function() {
                            console.log('Checking position cookie!');
                            console.log(Math.abs(player.getDuration() - currentPosition));
                            if (currentPosition > 180 && Math.abs(player.getDuration() - currentPosition) >
                                5) {
                                player.seek(currentPosition);
                            }
                        });
                        window.onunload = function() {
                            localStorage[resumeData] = player.getPosition();
                        }
                    } else {
                        console.log('Your browser is too old!');
                    }
                });

                player.on('complete', function() {
                    if (typeof(Storage) !== 'undefined') {
                        localStorage.removeItem(resumeData);
                    } else {
                        console.log('Your browser is too old!');
                    }
                })

                function formatSeconds(seconds) {
                    var date = new Date(1970, 0, 1);
                    date.setSeconds(seconds);
                    return date.toTimeString().replace(/.*(\d{2}:\d{2}:\d{2}).*/, "$1");
                }
            }
        }
        
        // Hàm thêm logo overlay cho embed videos
        function addLogoOverlay() {
            if (!logoFile) return; // Nếu không có logo thì không thêm
            
            const videoPlayer = document.querySelector('.video-player');
            const logoDiv = document.createElement('div');
            logoDiv.className = `embed-logo ${logoPosition || 'bottom-right'}`;
            
            // Tạo link bao quanh logo nếu có logoLink
            if (logoLink) {
                const logoLink = document.createElement('a');
                logoLink.href = logoLink;
                logoLink.target = "_blank";
                
                const logoImg = document.createElement('img');
                logoImg.src = logoFile;
                logoImg.alt = "Logo";
                
                logoLink.appendChild(logoImg);
                logoDiv.appendChild(logoLink);
            } else {
                // Nếu không có link, chỉ thêm hình ảnh
                const logoImg = document.createElement('img');
                logoImg.src = logoFile;
                logoImg.alt = "Logo";
                
                logoDiv.appendChild(logoImg);
            }
            
            videoPlayer.appendChild(logoDiv);
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const episode = '{{ $episode->id }}';
            let playing = document.querySelector(`[data-id="${episode}"]`);
            if (playing) {
                playing.click();
                return;
            }

            const servers = document.getElementsByClassName('streaming-server');
            if (servers[0]) {
                servers[0].click();
            }
        });
    </script>
@endpush