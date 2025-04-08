@extends('themes::themevlxx.layout')

@php
use Illuminate\Support\Facades\Cache;
use Ophim\Core\Models\Movie;

// Lấy số lượng phim trên mỗi trang từ cấu hình theme
$perPage = get_theme_option('per_page_limit', 24);

// Lấy dữ liệu phim mới với phân trang
$movies = Cache::remember('site.movies.homepage_paginated', setting('site_cache_ttl', 5 * 60), function () use ($perPage) {
    return Movie::where('is_copyright', 0)
        ->orderBy('updated_at', 'desc')
        ->paginate($perPage);
});

// Tiêu đề cho trang
$pageTitle = "Phim mới cập nhật";
@endphp

@section('breadcrumb')
    <ol class="breadcrumb" itemScope itemType="https://schema.org/BreadcrumbList">
        <li itemProp="itemListElement" itemScope itemType="http://schema.org/ListItem">
            <a itemprop="item" href="/" title="{{ setting('site_homepage_title') }}">
                <span itemprop="name">Trang chủ</span>
            </a>
            <meta itemprop="position" content="1" />
        </li>
        <li class="active">{{ $pageTitle }}</li>
    </ol>
@endsection

@section('content')
    <div id="container">
        <h2 id="page-title" class="breadcrumb">{{ $pageTitle }}</h2>
        <div id="video-list">
            @foreach ($movies as $key => $movie)
                @include('themes::themevlxx.inc.catalog_sections_movies_item')
            @endforeach
            <div class="clear"></div>
            
            {{-- Custom Pagination --}}
            <div class="pagenavi">
                @if ($movies->onFirstPage())
                    <a class="disabled"><i class="fa fa-angle-left"></i></a>
                @else
                    <a href="javascript:void(0)" onclick="goToPage({{ $movies->currentPage() - 1 }})"><i class="fa fa-angle-left"></i></a>
                @endif
                
                @php
                    $window = 2; // Số trang hiển thị trước và sau trang hiện tại
                    $lastPage = $movies->lastPage();
                    $currentPage = $movies->currentPage();
                    
                    // Tính toán phạm vi trang hiển thị
                    $startPage = max(1, $currentPage - $window);
                    $endPage = min($lastPage, $currentPage + $window);
                    
                    // Hiển thị ba chấm nếu cần
                    $showDotsStart = ($startPage > 1);
                    $showDotsEnd = ($endPage < $lastPage);
                @endphp
                
                @if($showDotsStart)
                    <a href="javascript:void(0)" onclick="goToPage(1)">1</a>
                    <a class="disabled">...</a>
                @endif
                
                @for ($i = $startPage; $i <= $endPage; $i++)
                    <a href="javascript:void(0)" onclick="goToPage({{ $i }})" class="{{ $i == $currentPage ? 'active' : '' }}">{{ $i }}</a>
                @endfor
                
                @if($showDotsEnd)
                    <a class="disabled">...</a>
                    <a href="javascript:void(0)" onclick="goToPage({{ $lastPage }})">{{ $lastPage }}</a>
                @endif
                
                @if ($movies->hasMorePages())
                    <a href="javascript:void(0)" onclick="goToPage({{ $movies->currentPage() + 1 }})"><i class="fa fa-angle-right"></i></a>
                @else
                    <a class="disabled"><i class="fa fa-angle-right"></i></a>
                @endif
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script>
    function goToPage(page) {
        if (page == 1) {
            // Trang 1 trỏ về trang chủ
            window.location.href = '/';
        } else {
            // Các trang khác sử dụng URL tùy chỉnh
            window.location.href = '/danh-sach/phim-sex-moi?page=' + page;
        }
    }
    
    // Thêm xử lý khi tải trang
    document.addEventListener('DOMContentLoaded', function() {
        // Xử lý khi trang được tải từ URL không đúng
        if (window.location.pathname === '/' && window.location.search.includes('page=')) {
            var currentPage = new URLSearchParams(window.location.search).get('page');
            if (currentPage && currentPage != 1) {
                window.history.replaceState(null, '', '/danh-sach/phim-sex-moi' + window.location.search);
            }
        }
    });
</script>
@endpush