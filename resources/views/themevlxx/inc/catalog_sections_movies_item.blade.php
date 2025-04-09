<div id="video-{{$movie->id}}" class="video-item">
    <a title="{{$movie->name}}"
        href="{{$movie->getUrl()}}">
        <img class="video-image lazyload" src="{{$movie->getPosterUrl()}}"
            data-original="{{$movie->getPosterUrl()}}" width="240px" height="180px"
            alt="{{$movie->name}}" style="">
        
        @php
            // Lưu giá trị thực tế của language
            $actualLanguage = $movie->language;
            
            // Chuẩn hóa chuỗi language để so sánh
            $languageFormatted = trim(strtolower($actualLanguage ?? ''));
            $isVietsub = ($languageFormatted === 'vietsub');
            
            // Kiểm tra các thể loại của phim
            $categoryMap = [];
            
            // Lập một mảng các slug category của phim để dễ kiểm tra
            foreach ($movie->categories as $category) {
                $categoryMap[$category->slug] = true;
            }
            
            // Kiểm tra các thể loại đặc biệt
            $isKhongChe = isset($categoryMap['khong-che']);
            $isVietnamClip = isset($categoryMap['viet-nam-clip']);
            $isChauAu = isset($categoryMap['chau-au']);
            $isTrungQuoc = isset($categoryMap['trung-quoc']);
            $isHanQuoc18 = isset($categoryMap['han-quoc-18-']);
            $isHentai = isset($categoryMap['hentai']);
            
            // Xác định loại nhãn (ribbon) theo thứ tự ưu tiên
            $ribbonText = 'Full HD'; // Mặc định là Full HD
            
            // Kiểm tra theo thứ tự ưu tiên
            if ($isVietsub) {
                if ($isKhongChe) {
                    $ribbonText = 'Vietsub - Không Che';
                } elseif ($isHentai) {
                    $ribbonText = 'Hentai Vietsub';
                } elseif ($isHanQuoc18) {
                    $ribbonText = 'Vietsub Hàn Quốc 18+';
                } elseif ($isChauAu) {
                    $ribbonText = 'Vietsub Âu Mỹ';
                } elseif ($isTrungQuoc) {
                    $ribbonText = 'Vietsub AV China';
                } else {
                    $ribbonText = 'Vietsub';
                }
            } else {
                // Không phải Vietsub
                if ($isVietnamClip) {
                    $ribbonText = 'Việt Nam Clip';
                } elseif ($isKhongChe) {
                    $ribbonText = 'Không Che';
                } elseif ($isHentai) {
                    $ribbonText = 'Hentai';
                } elseif ($isHanQuoc18) {
                    $ribbonText = 'Hàn Quốc 18+';
                } elseif ($isChauAu) {
                    $ribbonText = 'Âu Mỹ';
                } elseif ($isTrungQuoc) {
                    $ribbonText = 'AV China';
                }
            }
        @endphp
        
        <div class="ribbon">{{ $ribbonText }}</div>
    </a>
    <div class="video-name">
        <a title="{{$movie->name}}"
            href="{{$movie->getUrl()}}">{{$movie->name}}</a>
    </div>
</div>
