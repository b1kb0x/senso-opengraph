<?php

declare(strict_types=1);

namespace Senso\OpenGraph\Core;

if (!defined('ABSPATH')) {
    exit;
}

final class OpenGraphManager
{
    /**
     * Output Open Graph metadata.
     */
    public function output(): void
    {
        $tags = $this->collect();

        $this->render($tags);
    }

    /**
     * Collect Open Graph meta tags.
     *
     * @return array<int, array<string, string>>
     */
    private function collect(): array
    {
        $tags = [];

        $tags = $this->addLocale($tags);

        return apply_filters(
            'senso_opengraph_tags',
            $tags
        );
    }

    /**
     * Render meta tags.
     *
     * @param array<int, array<string, string>> $tags
     */
    private function render(array $tags): void
    {
        foreach ($tags as $tag) {
            $attribute = $tag['attribute'];
            $name      = $tag['name'];
            $content   = $tag['content'];

            printf(
                '<meta %s="%s" content="%s">' . PHP_EOL,
                esc_attr($attribute),
                esc_attr($name),
                esc_attr($content)
            );
        }
    }

    /**
     * Add Open Graph locale.
     *
     * @param array<int, array<string, string>> $tags
     * @return array<int, array<string, string>>
     */
    private function addLocale(array $tags): array
    {
        $locale = get_locale();

        if ($locale === '') {
            return $tags;
        }

        $tags[] = [
            'attribute' => 'property',
            'name'      => 'og:locale',
            'content'   => $locale,
        ];

        return $tags;
    }
}