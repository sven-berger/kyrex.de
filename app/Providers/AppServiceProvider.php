<?php

namespace App\Providers;

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
                        'pages' => $pages->sortByDesc('created_at')->values(),
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
    }
}
