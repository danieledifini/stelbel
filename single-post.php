<?php
/**
 * The Template for displaying all single posts
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

$content = get_post_field('post_content', $post->ID);

preg_match_all('/<img[^>]+>/i', $content, $img_tags, PREG_OFFSET_CAPTURE);

$images = [];
$counter = 1;

foreach ($img_tags[0] as $img_data) {
    $img_tag = $img_data[0];
    $offset = $img_data[1];

    preg_match('/src="([^"]+)"/i', $img_tag, $src_match);
    $src = $src_match[1] ?? '';

    preg_match('/wp-image-(\d+)/', $img_tag, $id_match);
    $image_id = isset($id_match[1]) ? intval($id_match[1]) : null;


    $caption = '';
    preg_match_all('/\[caption.*?\](.*?)\[\/caption\]/s', $content, $caption_blocks, PREG_OFFSET_CAPTURE);

    foreach ($caption_blocks[1] as $block) {
        $block_html = $block[0];
        $block_offset = $block[1];

        if ($offset >= $block_offset && $offset <= ($block_offset + strlen($block_html))) {
            if (preg_match('/<img[^>]+> *(.*)$/s', $block_html, $caption_match)) {
                $caption = trim(strip_tags($caption_match[1]));

                $images[] = [
                    'index' => $counter,
                    'caption' => $caption,
                ];
            }
            break;
        }
    }

    $counter++;
}

$gallery = get_field('gallery', $post->ID);


if ($gallery && is_array($gallery)) {
    foreach ($gallery as $image) {

        if (!empty($image['caption'])) {

            $images[] = [
                'index' => $counter,
                'caption' => $image['caption'],
                'from_gallery' => true,
            ];

            
        }

        $counter++;
    }
}

$context['images_in_content'] = $images ;

Timber::render( array( 'single-post.twig' ), $context );
