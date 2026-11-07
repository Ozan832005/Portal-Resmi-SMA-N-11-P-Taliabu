<?php

namespace App\Providers;

use App\Models\Footer;
use App\Models\FooterGallery;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Footer tampil di semua halaman publik, jadi datanya dibagikan lewat
        // view composer agar setiap controller tidak perlu mengirim manual.
        View::composer('frontend.content.footer', function ($view) {
            $view->with('footerGalleries', FooterGallery::aktif());
        });

        View::composer('auth.login', function ($view) {
            $view->with('schoolLogo', Footer::value('logo'));
        });

        View::composer('layouts.backend.menu', function ($view) {
            $view->with('schoolLogo', Footer::value('logo'));
        });
    }
}
