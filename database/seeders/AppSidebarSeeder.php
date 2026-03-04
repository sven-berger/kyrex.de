<?php

namespace Database\Seeders;

use App\Models\AppCategories;
use App\Models\AppPages;
use Illuminate\Database\Seeder;

class AppSidebarSeeder extends Seeder
{
    public function run(): void
    {
        $indexCategory = AppCategories::query()->updateOrCreate(
            ['value' => 'index'],
            ['name' => 'Index', 'area' => 'index', 'sort_order' => 10]
        );

        $acpCategory = AppCategories::query()->updateOrCreate(
            ['value' => 'acp'],
            ['name' => 'ACP', 'area' => 'acp', 'sort_order' => 10]
        );

        $this->syncCategoryPages($indexCategory->id, [
            ['name' => 'Startseite', 'url' => '/'],
            ['name' => 'Wissensportal', 'url' => '/wissensportal'],
            ['name' => 'Dummy Page', 'url' => '/dummy-page'],
        ]);

        $this->syncCategoryPages($acpCategory->id, [
            ['name' => 'ACP Startseite', 'url' => '/acp'],
            ['name' => 'Wissensportal Kategorien', 'url' => '/acp/wissensportal/wp-categories'],
            ['name' => 'Wissensportal Seiten', 'url' => '/acp/wissensportal/wp-pages'],
        ]);
    }

    private function syncCategoryPages(int $categoryId, array $pages): void
    {
        $urls = array_column($pages, 'url');

        AppPages::query()
            ->where('category_id', $categoryId)
            ->whereNotIn('url', $urls)
            ->delete();

        foreach ($pages as $page) {
            AppPages::query()->updateOrCreate(
                [
                    'category_id' => $categoryId,
                    'url' => $page['url'],
                ],
                ['name' => $page['name']]
            );
        }
    }
}
