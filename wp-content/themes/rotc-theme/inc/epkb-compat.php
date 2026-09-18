<?php
/**
 * inc/epkb-compat.php
 * Compatibility notes for Echo Knowledge Base (plugin slug
 * echo-knowledge-base), which backs both the real FAQ content and,
 * repurposed, the historical/defunct-franchise profile pages (both
 * live under its 'epkb_post_type_1' post type -- confirmed live
 * against the site's actual database: 41 published entries split
 * between draft-rules/trade-rules FAQs and ~11 franchise history
 * write-ups).
 *
 * Echo KB manages its OWN single/archive templates internally (it
 * hooks into the page lifecycle rather than relying on this theme
 * having epkb_post_type_1-specific template files) and is generally
 * theme-agnostic by design -- it renders inside whatever page wrapper
 * the active theme provides for a normal page. That means page.php
 * already carries it correctly; nothing here NEEDS to exist for the
 * plugin to function.
 *
 * What this file is for: nudging Echo KB's own output to sit inside
 * this theme's visual language rather than its own default chrome.
 * The .epkb-container rules in style.css handle color/link/font
 * baseline; if a real KB page still looks visually foreign once this
 * theme is live, check Echo KB's own Admin > Settings > General >
 * "Kb Main Page Template" — pick the "Custom" or minimal template
 * option there so it deforms into the surrounding page rather than
 * rendering its own full-width layout.
 */

if (!defined('ABSPATH')) exit;

/**
 * Adds a body class when viewing a KB article/archive so page.php (or
 * a future page-knowledge-base.php) can add KB-specific wrapper markup
 * if the default compatibility styling in style.css isn't enough once
 * this is live against real content.
 */
function rotc_theme_epkb_body_class(array $classes): array {
    if (function_exists('epkb_is_kb_post_type') && epkb_is_kb_post_type()) {
        $classes[] = 'rotc-has-epkb';
    } elseif (get_post_type() === 'epkb_post_type_1') {
        $classes[] = 'rotc-has-epkb';
    }
    return $classes;
}
add_filter('body_class', 'rotc_theme_epkb_body_class');
