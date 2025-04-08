# THEME - Vlxx 2024 - OPHIM CMS


## Install
1. Tại thư mục của Project: `composer require hoanganh/theme-vlxx`
2. Kích hoạt giao diện trong Admin Panel

## Update
1. Tại thư mục của Project: `composer update hoanganh/theme-vlxx`
2. Re-Activate giao diện trong Admin Panel

## Document
### List
- Trang chủ: `display_label|relation|find_by_field|value|limit|show_more_url`
    ```
    Phim chiếu rạp mới||is_shown_in_theater|1|8|/danh-sach/phim-chieu-rap
    Phim bộ mới||type|series|8|/danh-sach/phim-bo
    Phim lẻ mới||type|single|8|/danh-sach/phim-le
    Phim hoạt hình|categories|slug|hoat-hinh|8|/the-loai/hoat-hinh
    ```

- Danh sách hot:  `Label|relation|find_by_field|value|sort_by_field|sort_algo|limit|show_template (top_text|top_thumb)`
    ```
    Sắp chiếu||status|trailer|publish_year|desc|10|top_text
    Top phim lẻ||type|single|view_week|desc|10|top_thumb
    Top phim bộ||type|series|view_week|desc|10|top_thumb
    ```
