<?php
/* WOO COMMERCE */

function timber_set_product($post)
{
    global $product;

    if (is_woocommerce()) {
        $product = wc_get_product($post->ID);
    }
}

remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );


add_action( 'woocommerce_before_quantity_input_field', 'add_title_for_quantity', 10 );

function add_title_for_quantity() {
    echo '<label class="fdc fs-20" for="quantity">'.get_field("quantity","options").'</label>';
}

function print_price(){
    global $product;
    $html = '<div class="foc fs-18 '.  esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ). '">'. $product->get_price_html() .'</div>';
    echo $html;

}