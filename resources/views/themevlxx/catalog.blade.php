@extends('themes::themevlxx.layout')

@php
    $years = Cache::remember(
        'all_years',
        \Backpack\Settings\app\Models\Setting::get('site_cache_ttl', 5 * 60),
        function () {
            return \Ophim\Core\Models\Movie::select('publish_year')->distinct()->pluck('publish_year')->sortDesc();
        },
    );
@endphp



@section('content')
    <div id="container">
        <h2 id="page-title" class="breadcrumb">{{ $section_name }}</h2>
        <div id="video-list">
            @foreach ($data as $key => $movie)
                @include('themes::themevlxx.inc.catalog_sections_movies_item')
            @endforeach
            <div class="clear"></div>
            {{ $data->appends(request()->all())->links('themes::themevlxx.inc.pagination') }}
        </div>
    </div>
@endsection
