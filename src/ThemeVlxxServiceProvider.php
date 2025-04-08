<?php

namespace Ophim\ThemeVlxx;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class ThemeVlxxServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->setupDefaultThemeCustomizer();
    }

    public function boot()
    {
        try {
            foreach (glob(__DIR__ . '/Helpers/*.php') as $filename) {
                require_once $filename;
            }
        } catch (\Exception $e) {
            //throw $e;
        }
        
        $this->loadViewsFrom(__DIR__ . '/../resources/views/', 'themes');

        $this->publishes([
            __DIR__ . '/../resources/assets' => public_path('themes/vlxx')
        ], 'vlxx-assets');
    }

    protected function setupDefaultThemeCustomizer()
    {
        config(['themes' => array_merge(config('themes', []), [
            'vlxx' => [
                'name' => 'Theme Vlxx',
                'author' => 'contact.animehay@gmail.com',
                'package_name' => 'namhuunam/theme-vlxx',
                'publishes' => ['vlxx-assets'],
                'preview_image' => '',
                'options' => [
                    [
                        'name' => 'recommendations',
                        'label' => 'Phim đề cử',
                        'type' => 'code',
                        'hint' => 'display_label|find_by_field|value|limit|sort_by_field|sort_algo',
                        'value' => <<<EOT
                        Phim đề cử|is_recommended|1|10|view_week|desc
                        Phim HOT|is_copyright|0|10|view_week|desc
                        Phim ngẫu nhiên|random|random|10|view_week|desc
                        EOT,
                        'attributes' => [
                            'rows' => 5
                        ],
                        'tab' => 'List'
                    ],
                    [
                        'name' => 'per_page_limit',
                        'label' => 'Pages limit',
                        'type' => 'number',
                        'value' => 48,
                        'wrapperAttributes' => [
                            'class' => 'form-group col-md-4',
                        ],
                        'tab' => 'List'
                    ],
                    [
                        'name' => 'movie_related_limit',
                        'label' => 'Movies related limit',
                        'type' => 'number',
                        'value' => 24,
                        'wrapperAttributes' => [
                            'class' => 'form-group col-md-4',
                        ],
                        'tab' => 'List'
                    ],
                    [
                        'name' => 'latest',
                        'label' => 'Home Page',
                        'type' => 'code',
                        'hint' => 'display_label|relation|find_by_field|value|limit|show_more_url',
                        'value' => <<<EOT
                        Phim mới cập nhật||is_copyright|0|12|/danh-sach/phim-moi
                        EOT,
                        'attributes' => [
                            'rows' => 5
                        ],
                        'tab' => 'List'
                    ],
                    [
                        'name' => 'hotest',
                        'label' => 'Danh sách hot',
                        'type' => 'code',
                        'hint' => 'Label|relation|find_by_field|value|sort_by_field|sort_algo|limit|show_template (top_thumb|top_trending)',
                        'value' => <<<EOT
                        Trending|trending|||||6|top_trending
                        Top phim lẻ||type|single|view_week|desc|6|top_thumb
                        Top phim bộ||type|series|view_week|desc|6|top_thumb
                        Bảng xếp hạng||is_copyright|0|view_week|desc|6|top_thumb
                        EOT,
                        'attributes' => [
                            'rows' => 5
                        ],
                        'tab' => 'List'
                    ],
                    [
                        'name' => 'additional_css',
                        'label' => 'Additional CSS',
                        'type' => 'code',
                        'value' => "",
                        'tab' => 'Custom CSS'
                    ],
                    [
                        'name' => 'body_attributes',
                        'label' => 'Body attributes',
                        'type' => 'text',
                        'value' => '',
                        'tab' => 'Custom CSS'
                    ],
                    [
                        'name' => 'additional_header_js',
                        'label' => 'Header JS',
                        'type' => 'code',
                        'value' => "",
                        'tab' => 'Custom JS'
                    ],
                    [
                        'name' => 'additional_body_js',
                        'label' => 'Body JS',
                        'type' => 'code',
                        'value' => "",
                        'tab' => 'Custom JS'
                    ],
                    [
                        'name' => 'additional_footer_js',
                        'label' => 'Footer JS',
                        'type' => 'code',
                        'value' => "",
                        'tab' => 'Custom JS'
                    ],
                    [
                        'name' => 'footer',
                        'label' => 'Footer',
                        'type' => 'code',
                        'value' => <<<EOT
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
                        EOT,
                        'tab' => 'Custom HTML'
                    ],
                    [
                        'name' => 'ads_header',
                        'label' => 'Ads header',
                        'type' => 'code',
                        'value' => '',
                        'tab' => 'Ads'
                    ],
                    [
                        'name' => 'ads_catfish',
                        'label' => 'Ads catfish',
                        'type' => 'code',
                        'value' => '',
                        'tab' => 'Ads'
                    ],
                    [
                        'name' => 'show_fb_comment_in_single',
                        'label' => 'Show FB Comment In Single',
                        'type' => 'boolean',
                        'value' => false,
                        'tab' => 'FB Comment'
                    ]
                ],
            ]
        ])]);
    }
}
