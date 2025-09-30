<?php
/*
 * VITE JIT development
 * Fully dynamic JS/CSS enqueue based on Vite manifest
 */

define('DIST_DEF', 'dist');
define('DIST_URI', get_template_directory_uri() . '/' . DIST_DEF);
define('DIST_PATH', get_template_directory() . '/' . DIST_DEF);

define('JS_DEPENDENCY', array());
define('JS_LOAD_IN_FOOTER', true);
define('VITE_SERVER', 'http://localhost:3000');

add_action('wp_enqueue_scripts', function() {

    $entry_to_load = '';

    
    if (is_page_template()) {
        $entry_to_load = basename(get_page_template_slug(), '.php') . '.js';
    }  elseif (is_404()) {
        $entry_to_load = 'standard.js';
    }  elseif ((is_woocommerce() && !is_product()) || is_cart() || is_checkout()) {
        $entry_to_load = 'woocommerce.js';
    }elseif (is_singular()) {
        $entry_to_load = 'single' . ucfirst(get_post_type()) . '.js';
    } elseif (is_product()) {
        $entry_to_load = 'singleProduct.js';
    }

    if (!$entry_to_load) return;

    
    if (defined('IS_VITE_DEVELOPMENT') && IS_VITE_DEVELOPMENT === true) {
        add_action('wp_head', function() use ($entry_to_load) {
            echo '<script type="module" crossorigin src="' . VITE_SERVER . '/js/' . $entry_to_load . '"></script>';
        });
        return;
    }

    
    $manifest_file = DIST_PATH . '/.vite/manifest.json';
    if (!file_exists($manifest_file)) return;

    $manifest = json_decode(file_get_contents($manifest_file), true);
    if (!is_array($manifest)) return;

    foreach ($manifest as $entry_name => $entry_info) {
        $js_file  = $entry_info['file'] ?? '';
        $css_files = $entry_info['css'] ?? [];

        if (basename($js_file) === $entry_to_load) {

            $js_path_full = DIST_PATH . '/' . $js_file;
            $version_js   = file_exists($js_path_full) ? filemtime($js_path_full) : time();

            wp_enqueue_script(
                'main',
                DIST_URI . '/' . $js_file . '?v=' . $version_js,
                JS_DEPENDENCY,
                '',
                JS_LOAD_IN_FOOTER
            );

            foreach ($css_files as $css_file) {
                $css_path_full = DIST_PATH . '/' . $css_file;
                $version_css   = file_exists($css_path_full) ? filemtime($css_path_full) : time();
                wp_enqueue_style('main-' . basename($css_file, '.css'), DIST_URI . '/' . $css_file . '?v=' . $version_css);
            }

            break;
        }
    }
});
