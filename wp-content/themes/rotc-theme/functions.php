<?php
/**
 * functions.php
 * Theme setup for ROTC Theme. Kept deliberately small: this is a
 * from-scratch news-site theme (not a child of seos-football), so
 * there's no parent functions.php to lean on -- everything the theme
 * needs is registered here or in inc/.
 */

if (!defined('ABSPATH')) exit;

define('ROTC_THEME_VERSION', '0.2.0');

function rotc_theme_setup(): void {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('automatic-feed-links');
    add_theme_support('align-wide');

    // No 'primary' menu location anymore -- header.php includes
    // /manage/'s own real nav directly (see that file's doc comment),
    // not a WordPress-managed menu. 'footer' stays; footer.php still
    // offers it as an optional extra column.
    register_nav_menus([
        'footer' => __('Footer Menu', 'rotc-theme'),
    ]);
}
add_action('after_setup_theme', 'rotc_theme_setup');

function rotc_theme_assets(): void {
    wp_enqueue_style('rotc-theme-style', get_stylesheet_uri(), [], ROTC_THEME_VERSION);
    wp_enqueue_style('rotc-theme-fonts', 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Roboto+Condensed:wght@600;700&display=swap', [], null);
    // mfl26.css is what actually styles the shared nav header.php now
    // includes from /manage/ -- this theme's own style.css deliberately
    // no longer defines .rotc-nav/.rotc-brand/etc. at all, to avoid two
    // rule sets fighting over the same class names. Cache-busted the
    // same way manage's own header.php does (filemtime, not a static
    // version string), since this file changes independently of theme
    // releases.
    $manageRoot = dirname(ABSPATH) . '/manage';
    $mfl26Path = $manageRoot . '/assets/mfl26.css';
    if (file_exists($mfl26Path)) {
        $mfl26Ver = @filemtime($mfl26Path) ?: ROTC_THEME_VERSION;
        wp_enqueue_style('rotc-manage-nav', trailingslashit(home_url()) . 'manage/assets/mfl26.css', [], $mfl26Ver);
    }
}

/**
 * This theme doesn't offer commenting (per Matteo's call -- the site
 * already has wpforo for discussion, a second comment surface on
 * every post/page was redundant). Force it closed at the theme level
 * rather than relying on every individual post's setting being right,
 * and hide any pre-existing counts so nothing invites a form that
 * isn't there.
 */
add_filter('comments_open', '__return_false');
add_filter('pings_open', '__return_false');
add_filter('comments_array', '__return_empty_array');

/**
 * Same reasoning, different surface: a "Latest Comments" block widget
 * was left over in a footer widget area from before this theme was
 * active (confirmed live -- it was rendering real comment data via
 * dynamic_sidebar('rotc-footer') on the front page). Removing it in
 * Appearance > Widgets would work too, but suppressing the block
 * itself here means it can never resurface site-wide (post content,
 * any widget area, a future page) no matter how it gets re-added --
 * consistent with "no comments anywhere" rather than "no comments in
 * the one spot someone happened to notice."
 */
add_filter('render_block_core/latest-comments', '__return_empty_string');
// The classic (non-block) Recent Comments widget, same reasoning.
function rotc_theme_unregister_widgets(): void {
    unregister_widget('WP_Widget_Recent_Comments');
}
add_action('widgets_init', 'rotc_theme_unregister_widgets', 11);

add_action('wp_enqueue_scripts', 'rotc_theme_assets');

/**
 * NOTE: this theme used to register a 'rotc-footer' widget area here.
 * Removed -- confirmed live it was a dead end: Appearance > Widgets
 * showed that area as completely empty, yet dynamic_sidebar('rotc-footer')
 * was still rendering a stale "Latest Comments" block on every page,
 * wrapped in this sidebar's own before_widget markup. That's a real
 * WordPress quirk (the block-based Widgets screen not reflecting a
 * leftover classic widget assignment in the sidebars_widgets option),
 * not something fixable from the admin UI. Simplest, most reliable fix:
 * the theme never calls dynamic_sidebar() on it at all now (see
 * front-page.php/page.php/footer.php), so there is nothing left for
 * that stale assignment to render into, regardless of what's still
 * sitting in the database.
 */

require get_template_directory() . '/inc/league-data.php';
require get_template_directory() . '/inc/epkb-compat.php';
require get_template_directory() . '/inc/wpforo-compat.php';
require get_template_directory() . '/inc/template-tags.php';
