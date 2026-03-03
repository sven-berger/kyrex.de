<?php

namespace App\Http\Controllers;

use App\Models\WissensPortalPages;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WissensportalIndexController extends Controller
{
    public function wissensportalIndexAction(): View
    {
        return $this->renderByUrl('index');
    }

    public function wissensportalPageAction(string $slug): View
    {
        return $this->renderByUrl($slug);
    }

    private function renderByUrl(string $url): View
    {
        $page = WissensPortalPages::query()
            ->with('category')
            ->where('url', $url)
            ->first();

        if ($page === null) {
            abort(404);
        }

        $page = $this->normalizeSnippetContentForDisplay($page);

        return view('wissensportal.wissensportal-page', [
            'page' => $page,
        ]);
    }

    private function normalizeSnippetContentForDisplay(WissensPortalPages $page): WissensPortalPages
    {
        foreach ([1, 2, 3, 4, 5] as $snippetNumber) {
            $field = 'snippet_' . $snippetNumber;
            $titleField = 'snippet_' . $snippetNumber . '_title';
            $content = trim((string) ($page->{$field} ?? ''));
            $snippetTitle = (string) ($page->{$titleField} ?? '');

            if ($content === '') {
                continue;
            }

            if (preg_match('/<pre[\\s>]|<code[\\s>]/i', $content) === 1) {
                continue;
            }

            $plainText = html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $plainText = trim($plainText);

            if ($plainText === '') {
                continue;
            }

            $shouldRenderAsCode = str_contains($plainText, '<?') || $this->titleHasKnownCodeExtension($snippetTitle);
            if (!$shouldRenderAsCode) {
                continue;
            }

            $languageClass = $this->resolveSnippetLanguageClass($snippetTitle, $plainText);
            $escaped = htmlspecialchars($plainText, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

            $page->{$field} = '<pre><code class="' . $languageClass . '">' . $escaped . '</code></pre>';
        }

        return $page;
    }

    private function titleHasKnownCodeExtension(string $snippetTitle): bool
    {
        $normalizedTitle = Str::of($snippetTitle)->lower()->trim()->value();
        $extension = pathinfo($normalizedTitle, PATHINFO_EXTENSION);

        return in_array($extension, ['php', 'js', 'mjs', 'cjs', 'ts', 'css', 'sql', 'json', 'html', 'htm', 'xml', 'sh', 'bash'], true);
    }

    private function resolveSnippetLanguageClass(string $snippetTitle, string $plainText): string
    {
        $normalizedTitle = Str::of($snippetTitle)->lower()->trim()->value();

        $extensionMap = [
            'php' => 'language-php',
            'js' => 'language-javascript',
            'mjs' => 'language-javascript',
            'cjs' => 'language-javascript',
            'ts' => 'language-javascript',
            'css' => 'language-css',
            'sql' => 'language-sql',
            'json' => 'language-json',
            'html' => 'language-html',
            'htm' => 'language-html',
            'xml' => 'language-html',
            'sh' => 'language-bash',
            'bash' => 'language-bash',
        ];

        $extension = pathinfo($normalizedTitle, PATHINFO_EXTENSION);
        if ($extension !== '' && array_key_exists($extension, $extensionMap)) {
            return $extensionMap[$extension];
        }

        if ($normalizedTitle !== '' && str_ends_with($normalizedTitle, '.php')) {
            return 'language-php';
        }

        if (str_contains($plainText, '<?')) {
            return 'language-php';
        }

        return 'language-plaintext';
    }
}
