<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Register Gutenberg Block for Photo & Video Gallery
 */
add_action('init', 'npg_photo_gallery_register_gutenberg_block');
function npg_photo_gallery_register_gutenberg_block() {
    if (!function_exists('register_block_type')) {
        return;
    }

    // Register all frontend styles/scripts on init so they're available for editor_style
    wp_register_style('npg-frontend-css', NPG_PLUGIN_URL . 'assets/css/npg-frontend.css', array(), NPG_VER);
    wp_register_style('lg-hover-css', NPG_PLUGIN_URL . 'assets/css/hover.css', array(), NPG_VER);
    wp_register_style('awplife-npg-light-gallery-css', NPG_PLUGIN_URL . 'include/lightbox/light-gallery/css/lightgallery.css', array(), NPG_VER);

    // Register editor-only override styles (force visibility in editor preview)
    wp_register_style('npg-block-editor-css', false);
    wp_add_inline_style('npg-block-editor-css', '
        .npg-row { opacity: 1 !important; display: flow-root !important; }
        .npg-row .single-image { opacity: 1 !important; animation: none !important; }
    ');

    wp_register_script(
        'npg-gutenberg-block-js',
        NPG_PLUGIN_URL . 'assets/js/gutenberg-block.js',
        array('wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor', 'wp-server-side-render', 'jquery'),
        NPG_VER,
        true
    );

    register_block_type('new-photo-gallery/photo-gallery-block', array(
        'api_version'     => 3,
        'editor_script'   => 'npg-gutenberg-block-js',
        'editor_style'    => array('npg-frontend-css', 'lg-hover-css', 'awplife-npg-light-gallery-css', 'npg-block-editor-css'),
        'render_callback' => 'npg_photo_gallery_block_render',
        'attributes'      => array(
            'galleryId' => array(
                'type'    => 'string',
                'default' => '',
            ),
        ),
    ));
}

/**
 * Localize gallery data for the block editor dropdown
 */
add_action('enqueue_block_editor_assets', 'npg_photo_gallery_gutenberg_localize');
function npg_photo_gallery_gutenberg_localize() {
    $all_galleries = get_posts(array(
        'post_type'      => 'npg_gallery',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'orderby'        => 'title',
        'order'          => 'ASC',
    ));
    
    $galleries_data = array();
    if (!empty($all_galleries)) {
        foreach ($all_galleries as $g) {
            $galleries_data[] = array(
                'id'    => $g->ID,
                'title' => $g->post_title ? $g->post_title : __('(no title)', 'new-photo-gallery'),
            );
        }
    }
    
    wp_localize_script('npg-gutenberg-block-js', 'npg_gutenberg_data', array(
        'galleries' => $galleries_data,
    ));
}

/**
 * Gutenberg Block Render Callback
 * Used for both frontend and ServerSideRender editor preview.
 */
function npg_photo_gallery_block_render($attributes) {
    $gallery_id = isset($attributes['galleryId']) ? (int)$attributes['galleryId'] : 0;
    if (!$gallery_id) {
        return '';
    }
    return do_shortcode('[NPG id=' . $gallery_id . ']');
}
