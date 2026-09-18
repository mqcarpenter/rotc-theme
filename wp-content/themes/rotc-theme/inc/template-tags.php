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
