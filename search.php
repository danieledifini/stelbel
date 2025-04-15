<?php
/**
 * Search results page
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

$templates = array( 'search.twig', 'archive.twig', 'index.twig' );

$context          = Timber::context();
$context['title'] = get_search_query();

$context['is_search'] = true;

global $wp_query;
$context['posts'] = Timber::get_posts( $wp_query );
$context['total_results'] = $wp_query->found_posts;

Timber::render( $templates, $context );


