<?php

declare(strict_types=1);

namespace Senso\OpenGraph\Core;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin class.
 */
final class Plugin
{
    /**
     * Plugin initialization.
     */
    public function run(): void
    {
        add_action(
            'init',
            [$this, 'init']
        );
    }

    /**
     * Initialize plugin.
     */
    public function init(): void
    {
        add_action(
            'wp_head',
            [$this, 'renderOpenGraph'],
            1
        );
    }

    /**
     * Render Open Graph metadata.
     */
    public function renderOpenGraph(): void
    {
        (new OpenGraphManager())->output();
    }
}