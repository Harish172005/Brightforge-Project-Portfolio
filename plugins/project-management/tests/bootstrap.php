<?php
/**
 * Test bootstrap.
 *
 * The classes under test call a few WordPress functions. These small stand-ins
 * copy what the real functions do, so the tests run with plain PHPUnit and no
 * WordPress install.
 */

if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

// PHPUnit itself.
require_once dirname(__DIR__) . '/vendor/autoload.php';

// The plugin and its tests, loaded straight from src/ and tests/.
spl_autoload_register(function (string $class): void {
    $map = [
        'Brightforge\\ProjectManager\\Tests\\' => __DIR__ . '/',
        'Brightforge\\ProjectManager\\'        => dirname(__DIR__) . '/src/',
    ];

    foreach ($map as $prefix => $directory) {
        if (strpos($class, $prefix) === 0) {
            $file = $directory . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';

            if (is_file($file)) {
                require_once $file;
            }

            return;
        }
    }
});

if (!function_exists('wp_unslash')) {
    function wp_unslash($value)
    {
        if (is_array($value)) {
            return array_map('wp_unslash', $value);
        }

        return is_string($value) ? stripslashes($value) : $value;
    }
}

if (!function_exists('sanitize_title')) {
    function sanitize_title($title): string
    {
        return trim((string) preg_replace('/[^a-z0-9]+/', '-', strtolower((string) $title)), '-');
    }
}

if (!function_exists('sanitize_text_field')) {
    function sanitize_text_field($text): string
    {
        return trim((string) preg_replace('/\s+/', ' ', strip_tags((string) $text)));
    }
}

if (!function_exists('absint')) {
    function absint($value): int
    {
        return abs((int) $value);
    }
}
