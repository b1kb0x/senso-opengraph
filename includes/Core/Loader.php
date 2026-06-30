<?php

declare(strict_types=1);

namespace Senso\OpenGraph\Core;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Simple PSR-4 autoloader.
 */
spl_autoload_register(function (string $class): void {
    $prefix = 'Senso\\OpenGraph\\';

    if (strpos($class, $prefix) !== 0) {
        return;
    }

    $relative = substr($class, strlen($prefix));

    $file = SENSO_OPENGRAPH_PATH .
        'includes/' .
        str_replace('\\', DIRECTORY_SEPARATOR, $relative) .
        '.php';

    if (file_exists($file)) {
        require_once $file;
    }

});