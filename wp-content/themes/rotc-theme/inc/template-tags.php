<?php
/**
 * inc/template-tags.php
 * Small display helpers shared by template-parts/ and the top-level
 * templates, kept out of functions.php so that file stays pure setup.
 */

if (!defined('ABSPATH')) exit;

function rotc_theme_posted_on(): string {
    return sprintf('%s &middot; %s', get_the_date(), get_the_author());
}

/**
 * Trims an excerpt to $chars, breaking on a word boundary -- used by
 * the card grid instead of the_excerpt() so a post with no manual
 * excerpt and a very long first paragraph doesn't blow out the card.
 */
function rotc_theme_card_excerpt(int $chars = 140): string {
    $text = wp_strip_all_tags(get_the_excerpt());
    if (mb_strlen($text) <= $chars) return $text;
    $truncated = mb_substr($text, 0, $chars);
    $lastSpace = mb_strrpos($truncated, ' ');
    if ($lastSpace !== false) $truncated = mb_substr($truncated, 0, $lastSpace);
    return $truncated . '&hellip;';
}

/**
 * Looks a page up by its ACTUAL slug rather than a hardcoded guess --
 * confirmed live that "Smack Board" and "Knowledge Base" (both linked
 * from the nav/footer) sit at /community/ and /faq/ respectively, not
 * the guessable /smack-board/ or /knowledge-base/ this theme originally
 * assumed. Cached per-request (static array) since the same lookup
 * happens on every page load from the footer.
 *
 * @return string The page's real permalink, or $fallback ('#' by
 *   default) if no published page has that slug -- a dead '#' link is
 *   safer than home_url('/guessed-slug/') silently 404ing again.
 */
function rotc_theme_page_url(string $slug, string $fallback = '#'): string {
    static $cache = [];
    if (array_key_exists($slug, $cache)) return $cache[$slug];
    $page = get_page_by_path($slug);
    return $cache[$slug] = $page ? get_permalink($page) : $fallback;
}
