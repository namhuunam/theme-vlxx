@php
    $logo = setting('site_logo', '');
    $brand = setting('site_brand', '');
    $title = isset($title) ? $title : setting('site_homepage_title', '');
@endphp
<div id="header">
    <header>
        <h1 class="hidden">VLXX.COM</h1>
        <div id="logo">
            <div itemscope="" itemtype="https://schema.org/Organization" class="logoWrapper">
                <a itemprop="url" href="/">
                    @if ($logo)
                        {!! $logo !!}
                    @else
                        {!! $brand !!}
                    @endif
                </a>
            </div>
        </div>
    </header>
    <div class="clear"></div>
    <div id="primary-nav">
        <ul class="menu">
            @foreach ($menu as $item)
                @if (count($item['children']))
                    <li>
                        <a href="javascript:void(0)" title="{{ $item['name'] }}">
                            {{ $item['name'] }}
                            <svg width="10" height="10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512">
                                <path fill="#abb7c4"
                                    d="M192 384c-8.188 0-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25s32.75-12.5 45.25 0L192 306.8l137.4-137.4c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25l-160 160C208.4 380.9 200.2 384 192 384z" />
                            </svg>
                        </a>
                        <ul class="sub">
                            @foreach ($item['children'] as $children)
                                <li><a href="{{ $children['link'] }}"
                                        title="{{ $children['name'] }}">{{ $children['name'] }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @else
                    <li class="{{ $item['link'] === '/' ? 'active' : 'hidden-sm' }}">
                        <a title="{{ $item['name'] }}" href="{{ $item['link'] }}">{{ $item['name'] }}</a></li>
                @endif
            @endforeach
        </ul>
    </div>
    <div id="search-box">
        <form id="search-form" class="search" action="/" method="get">
            <span class="icon-search"></span>
            <input type="text" placeholder="Thể loại, diễn viên, code,..." name="search" id="key-search" autocomplete="off" value="" class="searchTxt">
            <input type="submit" value="Tìm kiếm" class="searchBtn">
        </form>
    </div>
</div>
