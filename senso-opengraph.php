<?php
/**
 * Plugin Name: Senso OpenGraph
 * Description: Generates Open Graph and Twitter Cards metadata for SENSO COFFEE.
 * Version: 0.1.0
 * Requires at least: 7.0
 * Requires PHP: 8.3
 * Author: SENSO COFFEE
 * Text Domain: senso-opengraph
 */

declare(strict_types=1);

if (!defined('ABSPATH')) {
    exit;
}

/*
|--------------------------------------------------------------------------
| Plugin constants
|--------------------------------------------------------------------------
*/

define('SENSO_OPENGRAPH_VERSION', '0.1.0');

define(
    'SENSO_OPENGRAPH_PATH',
    plugin_dir_path(__FILE__)
);

define(
    'SENSO_OPENGRAPH_URL',
    plugin_dir_url(__FILE__)
);


/*
|--------------------------------------------------------------------------
| Autoloader
|--------------------------------------------------------------------------
*/

require_once SENSO_OPENGRAPH_PATH . 'includes/Core/Loader.php';

/*
|--------------------------------------------------------------------------
| Boot plugin
|--------------------------------------------------------------------------
*/

(new Senso\OpenGraph\Core\Plugin())->run();
