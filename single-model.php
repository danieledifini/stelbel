<?php
/**
 * The Template for displaying all single MODEL
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context         = Timber::context();
$timber_post     = Timber::get_post();
$context['post'] = $timber_post;

$context['is_single_model'] = true;

Timber::render( array( 'single-model.twig' ), $context );
