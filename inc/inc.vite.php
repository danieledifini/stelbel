<?php
/*
 * VITE JIT development
 * Inspired by https://github.com/andrefelipe/vite-php-setup
 *
 */

// dist subfolder - defined in vite.config.json
define('DIST_DEF', 'dist');

// defining some base urls and paths
define('DIST_URI', get_template_directory_uri() . '/' . DIST_DEF);
define('DIST_PATH', get_template_directory() . '/' . DIST_DEF);

// js enqueue settings
define('JS_DEPENDENCY', array()); // array('jquery') as example
define('JS_LOAD_IN_FOOTER', true); // load scripts in footer?

// deafult server address, port and entry point can be customized in vite.config.json
define('VITE_SERVER', 'http://localhost:3000');
define('VITE_ENTRY_POINT', array('/js/homepage.js','/js/models.js','/js/singleModel.js','/js/request.js','/js/blog.js','/js/singleBlog.js','/js/woocommerce.js', '/js/singleProduct.js','/js/builds.js','/js/singleBuild.js','/js/contact.js','/js/trademark.js','/js/register.js','/js/standard.js'));

// enqueue hook
add_action( 'wp_enqueue_scripts', function() {
    
    if (defined('IS_VITE_DEVELOPMENT') && IS_VITE_DEVELOPMENT === true) {

        // insert hmr into head for live reload
        function vite_head_module_hook() {
            if (is_array(VITE_ENTRY_POINT)) {
                foreach(VITE_ENTRY_POINT as $entry){
                    $found = false;

                    if ((is_page_template( 'page-templates/homepage.php' )&&($entry == '/js/homepage.js'))){
                        $found = true;
                    }

                    elseif (((is_page_template( 'page-templates/models.php' ) )&&($entry == '/js/models.js'))){
                        $found = true;
                    }

                    elseif (((is_singular( 'model' ))  &&($entry == '/js/singleModel.js'))){
                        $found = true;
                    }

                    elseif (((is_page_template( 'page-templates/request.php' ) )&&($entry == '/js/request.js'))){
                        $found = true;
                    }

                    elseif (((is_page_template( 'page-templates/blog.php' ) )&&($entry == '/js/blog.js'))){
                        $found = true;
                    }

                    elseif (((is_singular( 'post' ))  &&($entry == '/js/singleBlog.js'))){
                        $found = true;
                    }


                    elseif ((is_woocommerce() && !is_product() )&&($entry == '/js/woocommerce.js')){
                        $found = true;
                    }

                    elseif ((is_product() )&&($entry == '/js/singleProduct.js')){
                        $found = true;
                    }

                    elseif (((is_page_template( 'page-templates/builds.php' ) )&&($entry == '/js/builds.js'))){
                        $found = true;
                    }

                    elseif (((is_singular( 'build' ))  &&($entry == '/js/singleBuild.js'))){
                        $found = true;
                    }

                    elseif (((is_page_template( 'page-templates/contact.php' ) )&&($entry == '/js/contact.js'))){
                        $found = true;
                    }

                    elseif (((is_page_template( 'page-templates/trademark.php' ) )&&($entry == '/js/trademark.js'))){
                        $found = true;
                    }

                    elseif (((is_page_template( 'page-templates/register.php' ) )&&($entry == '/js/register.js'))){
                        $found = true;
                    }

                    elseif (((is_page_template( 'page-templates/standard.php' )|| is_404())  &&($entry == '/js/standard.js'))){
                        $found = true;
                    }

                    if($found){
                        echo '<script type="module" crossorigin src="' . VITE_SERVER . $entry . '"></script>';
                    }
                }
            }
        } 
        add_action('wp_head', 'vite_head_module_hook');      

    } else {

        // production version, 'npm run build' must be executed in order to generate assets
        // ----------

        // read manifest.json to figure out what to enqueue
        $manifest = json_decode( file_get_contents( DIST_PATH . '/.vite/manifest.json'), true );
        
        // is ok
        if (is_array($manifest)) {
            
            foreach($manifest as $entry){
                $manifest_key = array_keys($entry);

                if(count($manifest_key) > 1){

                    $css_file = '';
                    $js_file  = '';

                    foreach($manifest_key as $key){
                        $value = $entry[$key];

                        if(is_array($value)){
                            $value = $entry[$key][0];
                        }
                        
                        if($key == 'file'){
                            $js_file = $value;
                        }
                        elseif($key == 'css'){
                            $css_file = $value;
                        }
                    }

                    $found = false;

                    if ((is_page_template( 'page-templates/homepage.php' )&&($js_file == 'homepage.js'))){
                        $found = true;
                    }

                    elseif (((is_page_template( 'page-templates/models.php' ) )&&($js_file == 'models.js'))){
                        $found = true;
                    }

                    elseif (((is_singular( 'model' ))  &&($js_file == 'singleModel.js'))){
                        $found = true;
                    }

                    elseif (((is_page_template( 'page-templates/request.php' ) )&&($js_file == 'request.js'))){
                        $found = true;
                    }

                    elseif (((is_page_template( 'page-templates/blog.php' ) )&&($js_file == 'blog.js'))){
                        $found = true;
                    }

                    elseif (((is_singular( 'post' ))  &&($js_file == 'singleBlog.js'))){
                        $found = true;
                    }

                    elseif ((is_woocommerce() && !is_product() )&&($js_file == 'woocommerce.js')){
                        $found = true;
                    }

                    elseif ((is_product() )&&($js_file == 'singleProduct.js')){
                        $found = true;
                    }

                    elseif (((is_page_template( 'page-templates/builds.php' ) )&&($js_file == 'builds.js'))){
                        $found = true;
                    }

                    elseif (((is_singular( 'build' ))  &&($js_file == 'singleBuild.js'))){
                        $found = true;
                    }

                    elseif (((is_page_template( 'page-templates/contact.php' ) )&&($js_file == 'contact.js'))){
                        $found = true;
                    }

                    elseif (((is_page_template( 'page-templates/trademark.php' ) )&&($js_file == 'trademark.js'))){
                        $found = true;
                    }

                    elseif (((is_page_template( 'page-templates/register.php' ) )&&($js_file == 'register.js'))){
                        $found = true;
                    }

                    elseif (((is_page_template( 'page-templates/standard.php' )|| is_404())  &&($js_file == 'standard.js'))){
                        $found = true;
                    }

                    $version = '1.14';

                    if($found){
                        wp_enqueue_script( 'main', DIST_URI . '/' . $js_file.'?v='.$version, JS_DEPENDENCY, '', JS_LOAD_IN_FOOTER );
                        wp_enqueue_style( 'main', DIST_URI . '/' . $css_file.'?v='.$version );
                    }
                }
            }
        }
    }
});