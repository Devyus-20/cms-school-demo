<?php

namespace App\Providers;

use App\Models\Page;
use App\Models\Setting;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('*', function ($view) {
            try {
                $setting = Setting::latest()->first();
                $allPages = Page::where('aktif', true)->orderBy('urutan')->get();
            } catch (\Throwable $e) {
                $setting = null;
                $allPages = collect();
            }

            $view->with('websiteSetting', $setting);

            $grouped = [];
            foreach ($allPages as $page) {
                $baseSlug = preg_replace('/-\d+$/', '', $page->slug);
                if (!isset($grouped[$baseSlug])) {
                    $title = Page::$profilSlugs[$baseSlug] ?? $page->judul;
                    $grouped[$baseSlug] = [
                        'judul' => $title,
                        'slug'  => $baseSlug,
                    ];
                }
            }

            $view->with('profilNavItems', array_values($grouped));
        });
    }
}
