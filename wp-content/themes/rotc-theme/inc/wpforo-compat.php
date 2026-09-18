<?php
/**
 * inc/wpforo-compat.php
 * Compatibility notes for wpforo, which backs the "Smack" board.
 *
 * Like Echo Knowledge Base, wpforo renders inside whichever page it's
 * assigned to (via its `[wpforo]` shortcode or its own page template
 * setting in wpForo > Settings > Forum Page) rather than needing a
 * theme-specific PHP template. page.php's plain content wrapper is
 * enough for it to function; the .epkb-container/#wpforo rules in
 * style.css just bring its link/font colors in line with the rest of
 * the theme rather than wpforo's own default palette.
 *
 * If the forum page renders narrower/wider than the rest of the site
 * once this is live, check wpForo > Settings > Layout > "Forum Width"
 * (Full/Boxed) against .rotc-container's max-width in style.css.
 */

if (!defined('ABSPATH')) exit;

function rotc_theme_wpforo_body_class(array $classes): array {
    if (function_exists('wpforo_is_forum_page') && wpforo_is_forum_page()) {
        $classes[] = 'rotc-has-wpforo';
    }
    return $classes;
}
add_filter('body_class', 'rotc_theme_wpforo_body_class');
