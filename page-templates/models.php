<?php
/*
 * Template Name: MODELS      
 */

$context = Timber::context();

$context['types'] = Timber::get_terms( 'filter-type', array(
    'hide_empty' => true,
) );

Timber::render( 'models.twig', $context );
