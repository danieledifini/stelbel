<?php
/*
 * Template Name: BUILDS      
 */

$context = Timber::context();

$models = Timber::get_posts([
    'post_type' => 'model',
    'posts_per_page' => -1,
]);

$context['models'] = $models;

$last_build = Timber::get_posts([
    'post_type' => 'build',
    'posts_per_page' => 1,
    'orderby' => 'date',
    'order' => 'DESC',
]);

$context['last_build'] = $last_build;

Timber::render( 'builds.twig', $context );
