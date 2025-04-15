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

    $forsale = true;
    $values = get_field("product_status");

    if($values){
        
        foreach ($values as $value) {
            if($value == 'not for sale'){
                $forsale = false;
            }
        }
    }

    $context['forsale'] = $forsale;

    // Get related products
    $related_limit = wc_get_loop_prop('columns');
    $related_ids = wc_get_related_products($context['post']->id, $related_limit);
    $context['related_products'] = Timber::get_posts($related_ids);
    $context['archive_title'] = get_field("collection","options");
    $context['archive_link'] = wc_get_page_permalink( 'shop' );

    // Restore the context and loop back to the main query loop.
    wp_reset_postdata();

    $context['is_woo_single'] = true;

    Timber::render('single-product.twig', $context);
} else {
    $posts = Timber::get_posts();
    $context['products'] = $posts;

    

    if (is_product_category()) {
        $queried_object = get_queried_object();
        $term_id = $queried_object->term_id;
        $context['category'] = get_term($term_id, 'product_cat');
        $context['archive_title'] = single_term_title('', false);
    }
    else{
        $context['archive_title'] = get_field("collection","options");
    }
    
    $context['is_woo_archive'] = true;
    $context['is_archive'] = true;

    Timber::render('woo-archive.twig', $context);
}