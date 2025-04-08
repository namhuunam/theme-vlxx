<?php

use Illuminate\Support\Facades\Route;
use Ophim\ThemeVlxx\Controllers\ThemeVlxxController;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
    ),
], function () {
    Route::get('/', [ThemeVlxxController::class, 'index']);

    Route::get('/ajax-list-ep', [ThemeVlxxController::class, 'getListEpisodeAjax'])
        ->name('movies.ajax_list_ep');


    Route::get('/playeropt', [ThemeVlxxController::class, 'getPlayerOptAjax'])
        ->name('movies.ajax_playeropt');

    Route::get(setting('site_routes_category', '/the-loai/{category}'), [ThemeVlxxController::class, 'getMovieOfCategory'])
        ->where(['category' => '.+', 'id' => '[0-9]+'])
        ->name('categories.movies.index');

    Route::get(setting('site_routes_region', '/quoc-gia/{region}'), [ThemeVlxxController::class, 'getMovieOfRegion'])
        ->where(['region' => '.+', 'id' => '[0-9]+'])
        ->name('regions.movies.index');

    Route::get(setting('site_routes_tag', '/tu-khoa/{tag}'), [ThemeVlxxController::class, 'getMovieOfTag'])
        ->where(['tag' => '.+', 'id' => '[0-9]+'])
        ->name('tags.movies.index');

    Route::get(setting('site_routes_types', '/danh-sach/{type}'), [ThemeVlxxController::class, 'getMovieOfType'])
        ->where(['type' => '.+', 'id' => '[0-9]+'])
        ->name('types.movies.index');

    Route::get(setting('site_routes_actors', '/dien-vien/{actor}'), [ThemeVlxxController::class, 'getMovieOfActor'])
        ->where(['actor' => '.+', 'id' => '[0-9]+'])
        ->name('actors.movies.index');

    Route::get(setting('site_routes_directors', '/dao-dien/{director}'), [ThemeVlxxController::class, 'getMovieOfDirector'])
        ->where(['director' => '.+', 'id' => '[0-9]+'])
        ->name('directors.movies.index');

    Route::get(setting('site_routes_episode', '/phim/{movie}/{episode}-{id}'), [ThemeVlxxController::class, 'getEpisode'])
        ->where(['movie' => '.+', 'movie_id' => '[0-9]+', 'episode' => '.+', 'id' => '[0-9]+'])
        ->name('episodes.show');

    Route::post(sprintf('/%s/{movie}/{episode}/report', config('ophim.routes.movie', 'phim')), [ThemeVlxxController::class, 'reportEpisode'])->name('episodes.report');
    Route::post(sprintf('/%s/{movie}/rate', config('ophim.routes.movie', 'phim')), [ThemeVlxxController::class, 'rateMovie'])->name('movie.rating');

    Route::get(setting('site_routes_movie', '/phim/{movie}'), [ThemeVlxxController::class, 'getMovieOverview'])
        ->where(['movie' => '.+', 'id' => '[0-9]+'])
        ->name('movies.show');
    Route::get('/search/{search}', [ThemeVlxxController::class, 'search'])->name('search');
});
