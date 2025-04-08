<h2 id="page-title" class="breadcrumb">{{ $item['label'] }}</h2>
<div id="video-list">
    @foreach ($item['data'] as $key => $movie)
        @php
            $xClass = 'item';
            if ($key === 0 || $key % 4 === 0) {
                $xClass .= ' no-margin-left';
            }
        @endphp
        @include('themes::themevlxx.inc.sections_movies_item')
    @endforeach
    <div class="clear"></div>
<div class="pagenavi">
    <a data-page="Xem thêm" href="{{ $item['link'] }}">Xem thêm</a>
</div>
</div>
