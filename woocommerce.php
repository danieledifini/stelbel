<?php
if (!class_exists('Timber')) {
    echo 'Timber not activated. Make sure you activate the plugin in <a href="/wp-admin/plugins.php#timber">/wp-admin/plugins.php</a>';

    return;
}

$context = Timber::context();
$context['sidebar'] = Timber::get_widgets('shop-sidebar');

if (is_singular('product')) {
    $context['post'] = Timber::get_post();
    $product = wc_get_product($context['post']->ID);
    $context['product'] = $product;

    

    
    $related_limit = wc_get_loop_prop('columns');
    $related_ids = wc_get_related_products($context['post']->id, $related_limit);
    $context['related_products'] = Timber::get_posts($related_ids);
    $context['archive_title'] = get_field("collection","options");
    $context['archive_link'] = wc_get_page_permalink( 'shop' );

    
    wp_reset_postdata();

    $context['is_woo_single'] = true;

    Timber::render('single-product.twig', $context);
} else {

    

    /*
    
    global $wp_query;
    
    $args = [
        'post_type' => 'product',
        'post_status' => 'publish',
        'paged' => 1,
        'posts_per_page' => 4,
    ];

    $query = new WP_Query($args);

    $context['posts'] = Timber::get_posts($query);
    
    
    $paged = isset($_GET['paged']) ? max(1, (int) $_GET['paged']) : 1;
    
    $context['paged'] = $paged;
    $context['max_pages'] = $query->max_num_pages;

     if ( isset($_SERVER['HTTP_HX_REQUEST']) && $_SERVER['HTTP_HX_REQUEST'] === 'true' ) {
        Timber::render('partial/product-loop.twig', $context);
        exit;
    }

    if (is_product_category()) {
        $queried_object = get_queried_object();
        $term_id = $queried_object->term_id;
        $context['category'] = get_term($term_id, 'product_cat');
        $context['archive_title'] = single_term_title('', false);
    }
    else{
        $shop_page_id = wc_get_page_id('shop');
        $context['shop_acf'] = get_fields($shop_page_id); 
        
           
        $title = get_field('alternative_title', $shop_page_id);

        if(!$title)
        {
            $title = get_the_title($shop_page_id);
        }

        $context['title'] = $title;    
    }

   */

    
    
    $context['is_woo_archive'] = true;
    $context['is_archive'] = true;

    Timber::render('woo-archive.twig', $context);
}