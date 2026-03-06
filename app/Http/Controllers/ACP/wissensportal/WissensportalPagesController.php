<?php

namespace App\Http\Controllers\ACP\Wissensportal;

use App\Models\WissensPortalCategories;
use App\Models\WissensPortalPages;
use App\Http\Controllers\Controller;
use DOMDocument;
use DOMElement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WissensportalPagesController extends Controller
{
    public function wissensportalPagesAction(): View
    {
        $wpPagesEntries = WissensPortalPages::query()
            ->with('category')
            ->latest()
            ->get();

        $groupedEntries = $wpPagesEntries->groupBy(function (WissensPortalPages $page) {
            return $page->category?->id ?? 0;
        });

        $categories = WissensPortalCategories::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        $categories = ['' => 'Bitte Kategorie wählen'] + $categories;

        return view('acp.wissensportal.wissensportal-pages', [
            'entries' => $wpPagesEntries,
            'groupedEntries' => $groupedEntries,
            'categories' => $categories,
        ]);
    }

    public function wpEditAction(WissensPortalPages $page): View
    {
        $categories = WissensPortalCategories::query()
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();

        $categories = ['' => 'Bitte Kategorie wählen'] + $categories;

        return view('acp.wissensportal.wp-pages-edit', [
            'page' => $page,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'snippet_1_title' => ['required', 'string', 'max:255'],
            'snippet_1' => ['required', 'string'],
            'snippet_2_title' => ['nullable', 'string', 'max:255'],
            'snippet_2' => ['nullable', 'string'],
            'snippet_3_title' => ['nullable', 'string', 'max:255'],
            'snippet_3' => ['nullable', 'string'],
            'snippet_4_title' => ['nullable', 'string', 'max:255'],
            'snippet_4' => ['nullable', 'string'],
            'snippet_5_title' => ['nullable', 'string', 'max:255'],
            'snippet_5' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:wissensportal_categories,id'],
        ]);

        foreach (['snippet_2_title', 'snippet_2', 'snippet_3_title', 'snippet_3', 'snippet_4_title', 'snippet_4', 'snippet_5_title', 'snippet_5', 'content'] as $field) {
            $validated[$field] = $validated[$field] ?? '';
        }

        $validated = $this->sanitizeValidatedContent($validated);

        WissensPortalPages::create($validated);

        return redirect()
            ->route('acp.wissensportal.pages')
            ->with('status', 'Eintrag gespeichert.');
    }

    public function update(Request $request, WissensPortalPages $entry): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'snippet_1_title' => ['required', 'string', 'max:255'],
            'snippet_1' => ['required', 'string'],
            'snippet_2_title' => ['nullable', 'string', 'max:255'],
            'snippet_2' => ['nullable', 'string'],
            'snippet_3_title' => ['nullable', 'string', 'max:255'],
            'snippet_3' => ['nullable', 'string'],
            'snippet_4_title' => ['nullable', 'string', 'max:255'],
            'snippet_4' => ['nullable', 'string'],
            'snippet_5_title' => ['nullable', 'string', 'max:255'],
            'snippet_5' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:wissensportal_categories,id'],
        ]);

        foreach (['snippet_2_title', 'snippet_2', 'snippet_3_title', 'snippet_3', 'snippet_4_title', 'snippet_4', 'snippet_5_title', 'snippet_5', 'content'] as $field) {
            $validated[$field] = $validated[$field] ?? '';
        }

        $validated = $this->sanitizeValidatedContent($validated);

        $entry->update($validated);

        return redirect()
            ->route('acp.wissensportal.pages')
            ->with('status', 'Eintrag aktualisiert.');
    }

    public function destroy(WissensPortalPages $entry): RedirectResponse
    {
        $entry->delete();

        return redirect()
            ->route('acp.wissensportal.pages')
            ->with('status', 'Eintrag geloescht.');
    }

    private function sanitizeValidatedContent(array $validated): array
    {
        $validated['content'] = $this->sanitizeRichHtml($validated['content'] ?? '');

        foreach ([1, 2, 3, 4, 5] as $snippetNumber) {
            $contentField = 'snippet_' . $snippetNumber;
            $titleField = 'snippet_' . $snippetNumber . '_title';

            $validated[$contentField] = $this->sanitizeSnippetHtml(
                $validated[$contentField] ?? '',
                $validated[$titleField] ?? ''
            );
        }

        return $validated;
    }

    private function sanitizeSnippetHtml(string $html, string $snippetTitle = ''): string
    {
        $trimmed = trim($html);

        if ($trimmed === '') {
            return '';
        }

        $hasCodeBlockMarkup = preg_match('/<pre[\\s>]|<code[\\s>]/i', $trimmed) === 1;

        if (!$hasCodeBlockMarkup) {
            $plainText = $this->extractPlainTextFromHtml($trimmed);

            if ($plainText === '') {
                return '';
            }

            $shouldRenderAsCode = str_contains($plainText, '<?') || $this->titleHasKnownCodeExtension($snippetTitle);
            if (!$shouldRenderAsCode) {
                return $this->sanitizeRichHtml($html);
            }

            $languageClass = $this->resolveSnippetLanguageClass($snippetTitle, $plainText);
            $escaped = htmlspecialchars($plainText, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

            return '<pre><code class="' . $languageClass . '">' . $escaped . '</code></pre>';
        }

        return $this->sanitizeRichHtml($html);
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

    private function extractPlainTextFromHtml(string $html): string
    {
        $withLineBreaks = preg_replace('/<\\s*br\\s*\\/?\\s*>/i', "\n", $html);
        $withLineBreaks = preg_replace('/<\\/\\s*(p|div|li|h[1-6])\\s*>/i', "\n", $withLineBreaks ?? $html);
        $stripped = strip_tags($withLineBreaks ?? $html);
        $decoded = html_entity_decode($stripped, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $normalized = preg_replace('/\\r\\n?|\\x{2028}|\\x{2029}/u', "\n", $decoded);
        $normalized = preg_replace('/\n{3,}/', "\n\n", $normalized ?? $decoded);

        return trim($normalized ?? $decoded);
    }

    private function titleHasKnownCodeExtension(string $snippetTitle): bool
    {
        $normalizedTitle = Str::of($snippetTitle)->lower()->trim()->value();
        $extension = pathinfo($normalizedTitle, PATHINFO_EXTENSION);

        return in_array($extension, ['php', 'js', 'mjs', 'cjs', 'ts', 'css', 'sql', 'json', 'html', 'htm', 'xml', 'sh', 'bash'], true);
    }

    private function sanitizeRichHtml(string $html): string
    {
        if (trim($html) === '') {
            return '';
        }

        $allowedTags = [
            'figure',
            'p',
            'br',
            'strong',
            'em',
            'a',
            'ul',
            'ol',
            'li',
            'h2',
            'h3',
            'h4',
            'blockquote',
            'table',
            'thead',
            'tbody',
            'tr',
            'th',
            'td',
            'caption',
            'pre',
            'code',
        ];

        $allowedAttributes = [
            'a' => ['href', 'target', 'rel'],
            'figure' => ['class'],
            'th' => ['colspan', 'rowspan'],
            'td' => ['colspan', 'rowspan'],
            'pre' => ['class'],
            'code' => ['class'],
        ];

        $dom = new DOMDocument('1.0', 'UTF-8');
        libxml_use_internal_errors(true);
        $loaded = $dom->loadHTML('<?xml encoding="utf-8" ?><div>' . $html . '</div>', LIBXML_HTML_NOIMPLIED |
            LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        if (!$loaded) {
            return strip_tags($html, '<figure><p><br><strong><em><a><ul><ol><li><h2><h3><h4><blockquote><table><thead><tbody><tr><th><td><caption><pre><code>');
        }

        $nodes = [];
        foreach ($dom->getElementsByTagName('*') as $node) {
            $nodes[] = $node;
        }

        foreach (array_reverse($nodes) as $node) {
            if (!$node instanceof DOMElement) {
                continue;
            }

            $tagName = strtolower($node->tagName);

            if (!in_array($tagName, $allowedTags, true)) {
                while ($node->firstChild) {
                    $node->parentNode?->insertBefore($node->firstChild, $node);
                }

                $node->parentNode?->removeChild($node);
                continue;
            }

            $attributes = [];
            if ($node->hasAttributes()) {
                foreach ($node->attributes as $attribute) {
                    $attributes[] = strtolower($attribute->nodeName);
                }
            }

            foreach ($attributes as $attributeName) {
                if (Str::startsWith($attributeName, 'on') || $attributeName === 'style') {
                    $node->removeAttribute($attributeName);
                    continue;
                }

                $tagAllowedAttributes = $allowedAttributes[$tagName] ?? [];
                if (!in_array($attributeName, $tagAllowedAttributes, true)) {
                    $node->removeAttribute($attributeName);
                }
            }

            if ($tagName === 'a' && $node->hasAttribute('href')) {
                $href = trim($node->getAttribute('href'));

                if ($href === '' || preg_match('/^\s*(javascript:|data:)/i', $href) === 1) {
                    $node->removeAttribute('href');
                }

                if ($node->getAttribute('target') === '_blank') {
                    $node->setAttribute('rel', 'noopener noreferrer');
                } else {
                    $node->removeAttribute('target');
                    $node->removeAttribute('rel');
                }
            }

            if (in_array($tagName, ['pre', 'code'], true) && $node->hasAttribute('class')) {
                $classes = preg_split('/\s+/', trim($node->getAttribute('class'))) ?: [];
                $safeClasses = array_values(array_filter($classes, function (string $class): bool {
                    return preg_match('/^language-[a-z0-9+-]+$/i', $class) === 1;
                }));

                if ($safeClasses === []) {
                    $node->removeAttribute('class');
                } else {
                    $node->setAttribute('class', implode(' ', $safeClasses));
                }
            }

            if ($tagName === 'figure' && $node->hasAttribute('class')) {
                $classes = preg_split('/\s+/', trim($node->getAttribute('class'))) ?: [];
                $safeClasses = array_values(array_filter($classes, function (string $class): bool {
                    return in_array($class, ['table'], true);
                }));

                if ($safeClasses === []) {
                    $node->removeAttribute('class');
                } else {
                    $node->setAttribute('class', implode(' ', $safeClasses));
                }
            }
        }

        $wrapper = $dom->getElementsByTagName('div')->item(0);
        if (!$wrapper) {
            return strip_tags($html, '<figure><p><br><strong><em><a><ul><ol><li><h2><h3><h4><blockquote><table><thead><tbody><tr><th><td><caption><pre><code>');
        }

        $cleanHtml = '';
        foreach ($wrapper->childNodes as $childNode) {
            $cleanHtml .= $dom->saveHTML($childNode);
        }

        return trim($cleanHtml);
    }
}
