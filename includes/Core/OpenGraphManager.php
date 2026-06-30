<?php

declare(strict_types=1);

namespace Senso\OpenGraph\Core;

use WP_Term;

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
        $tags = $this->addType($tags);
        $tags = $this->addTitle($tags);
        $tags = $this->addUrl($tags);
        $tags = $this->addSiteName($tags);
        $tags = $this->addImage($tags);

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

    /**
     * Add Open Graph type.
     *
     * @param array<int, array<string, string>> $tags
     * @return array<int, array<string, string>>
     */
    private function addType(array $tags): array
    {
        if (is_product()) {
            $tags[] = [
                'attribute' => 'property',
                'name'      => 'og:type',
                'content'   => 'product',
            ];

            return $tags;
        }

        $tags[] = [
            'attribute' => 'property',
            'name'      => 'og:type',
            'content'   => 'website',
        ];

        return $tags;
    }

    /**
     * Add Open Graph title.
     *
     * @param array<int, array<string, string>> $tags
     * @return array<int, array<string, string>>
     */
    private function addTitle(array $tags): array
    {
        $title = wp_get_document_title();

        if ($title === '') {
            return $tags;
        }

        $tags[] = [
            'attribute' => 'property',
            'name'      => 'og:title',
            'content'   => $title,
        ];

        return $tags;
    }

    /**
     * Add Open Graph URL.
     *
     * @param array<int, array<string, string>> $tags
     * @return array<int, array<string, string>>
     */
    private function addUrl(array $tags): array
    {
        $url = $this->getUrl();

        if ($url === '') {
            return $tags;
        }

        $tags[] = [
            'attribute' => 'property',
            'name'      => 'og:url',
            'content'   => $url,
        ];

        return $tags;
    }

    private function getUrl(): string
    {
        if (is_front_page()) {
            return home_url('/');
        }

        if (is_shop()) {
            $shopId = wc_get_page_id('shop');

            return $shopId > 0
                ? get_permalink($shopId)
                : '';
        }

        if (is_singular()) {
            $url = get_permalink();

            return is_string($url) ? $url : '';
        }

        if (is_tax()) {
            $term = get_queried_object();

            if (!$term instanceof WP_Term) {
                return '';
            }

            $url = get_term_link($term);

            return is_wp_error($url) ? '' : $url;
        }

        return '';
    }

    /**
     * Add Open Graph site name.
     *
     * @param array<int, array<string, string>> $tags
     * @return array<int, array<string, string>>
     */
    private function addSiteName(array $tags): array
    {
        $siteName = get_bloginfo('name');

        if ($siteName === '') {
            return $tags;
        }

        $tags[] = [
            'attribute' => 'property',
            'name'      => 'og:site_name',
            'content'   => $siteName,
        ];

        return $tags;
    }

    /**
     * Get current page image.
     *
     * @return array{
     *     url: string,
     *     width: int,
     *     height: int,
     *     alt: string
     * }|null
     */
    private function getImage(): ?array
    {
        if (!is_singular()) {
            return null;
        }

        $attachmentId = get_post_thumbnail_id();

        if ($attachmentId === 0) {
            return null;
        }

        $image = wp_get_attachment_image_src(
            $attachmentId,
            'full'
        );

        if ($image === false) {
            return null;
        }

        $alt = get_post_meta(
            $attachmentId,
            '_wp_attachment_image_alt',
            true
        );

        if ($alt === '') {
            $alt = wp_get_document_title();
        }

        $url = $image[0];

        $width = (int) $image[1];

        $height = (int) $image[2];

        return [
            'url'    => $url,
            'width'  => $width,
            'height' => $height,
            'alt'    => $alt,
        ];
    }

    /**
     * Add Open Graph image.
     *
     * @param array<int, array<string, string>> $tags
     * @return array<int, array<string, string>>
     */
    private function addImage(array $tags): array
    {
        $image = $this->getImage();

        if ($image === null) {
            return $tags;
        }

        $tags[] = [
            'attribute' => 'property',
            'name'      => 'og:image',
            'content'   => $image['url'],
        ];

        $tags[] = [
            'attribute' => 'property',
            'name'      => 'og:image:width',
            'content'   => (string) $image['width'],
        ];

        $tags[] = [
            'attribute' => 'property',
            'name'      => 'og:image:height',
            'content'   => (string) $image['height'],
        ];

        $tags[] = [
            'attribute' => 'property',
            'name'      => 'og:image:alt',
            'content'   => $image['alt'],
        ];

        return $tags;
    }
}