<?php

namespace App\Providers;

use App\Models\AppPages;
use App\Models\WissensPortalPages;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        View::composer([
            'layouts.sidebarLeft.sidebarLeftWissensportal',
            'layouts.sidebarRight.sidebarRightWissensportal',
        ], function ($view): void {
            $rightCategoryKeys = ['vorlagen', 'ubungen', 'templates', 'exercises'];

            $groupedPages = WissensPortalPages::query()
                ->with('category')
                ->get()
                ->groupBy(function (WissensPortalPages $page) {
                    return $page->category?->id ?? 0;
                })
                ->map(function ($pages) {
                    $firstPage = $pages->first();
                    $category = $firstPage?->category;
                    $title = $category?->name ?? 'Ohne Kategorie';
                    $nameKey = Str::of($title)->lower()->ascii()->replaceMatches('/[^a-z0-9]/', '')->value();
                    $valueKey = Str::of((string) ($category?->value ?? ''))->lower()->ascii()->replaceMatches('/[^a-z0-9]/', '')->value();

                    return [
                        'title' => (string) $title,
                        'name_key' => $nameKey,
                        'value_key' => $valueKey,
                        'pages' => $pages->sortBy('id')->values(),
                    ];
                })
                ->values();

            $wissensportalRightGroups = $groupedPages
                ->filter(function (array $group) use ($rightCategoryKeys) {
                    return in_array($group['name_key'], $rightCategoryKeys, true)
                        || in_array($group['value_key'], $rightCategoryKeys, true);
                })
                ->values();

            $wissensportalLeftGroups = $groupedPages
                ->reject(function (array $group) use ($rightCategoryKeys) {
                    return in_array($group['name_key'], $rightCategoryKeys, true)
                        || in_array($group['value_key'], $rightCategoryKeys, true);
                })
                ->values();

            $view->with([
                'wissensportalLeftGroups' => $wissensportalLeftGroups,
                'wissensportalRightGroups' => $wissensportalRightGroups,
            ]);
        });

        View::composer([
            'layouts.sidebarLeft.sidebarLeftIndex',
            'layouts.sidebarLeft.sidebarLeftACP',
        ], function ($view): void {
            $groupedPages = AppPages::query()
                ->with('category')
                ->get()
                ->groupBy(function (AppPages $page) {
                    return $page->category?->id ?? 0;
                })
                ->map(function ($pages) {
                    $firstPage = $pages->first();
                    $category = $firstPage?->category;
                    $title = $category?->name ?? 'Ohne Kategorie';
                    $nameKey = Str::of($title)->lower()->ascii()->replaceMatches('/[^a-z0-9]/', '')->value();
                    $valueKey = Str::of((string) ($category?->value ?? ''))->lower()->ascii()->replaceMatches('/[^a-z0-9]/', '')->value();
                    $areaKey = Str::of((string) ($category?->area ?? 'index'))->lower()->ascii()->replaceMatches('/[^a-z0-9]/', '')->value();

                    return [
                        'title' => (string) $title,
                        'name_key' => $nameKey,
                        'value_key' => $valueKey,
                        'area_key' => $areaKey,
                        'sort_order' => (int) ($category?->sort_order ?? 0),
                        'category_id' => (int) ($category?->id ?? 0),
                        'pages' => $pages->sortBy('id')->values(),
                    ];
                })
                ->sortBy([
                    ['sort_order', 'asc'],
                    ['category_id', 'asc'],
                ])
                ->values();

            $appIndexGroups = $groupedPages
                ->filter(function (array $group) {
                    return $group['area_key'] === 'index';
                })
                ->values();

            $appAcpGroups = $groupedPages
                ->filter(function (array $group) {
                    return $group['area_key'] === 'acp';
                })
                ->values();

            $view->with([
                'appIndexGroups' => $appIndexGroups,
                'appAcpGroups' => $appAcpGroups,
            ]);
        });
    }
}
