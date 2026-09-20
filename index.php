<?php
/**
 * index.php -- the site's true document-root front controller.
 *
 * Replaces WordPress's stock stub (which just required
 * ./wp-blog-header.php). This is step 3 of the manage-owns-the-root
 * migration: WordPress core now lives in /wp/ (wp-admin, wp-includes,
 * every other root wp-*.php file -- see wp-config.php's WP_SITEURL/
 * WP_HOME/WP_CONTENT_DIR/WP_CONTENT_URL overrides for how it still
 * finds wp-content back here at the true root), reachable via the
 * existing .htaccess rewrite rule that was already sending every
 * non-file request to THIS file regardless of what's in it.
 *
 * The only decision this file makes: is this request for the bare
 * homepage, or everything else?
 *   - Bare "/" -> manage/'s own homepage (the carousel + news grid +
 *     recap hub built there), per Matteo's call that /manage/ is the
 *     mature, fully-owned app and should be the site's front door.
 *   - Everything else -> WordPress, unchanged. This covers every real
 *     WP permalink (posts, /faq/, /community/, /history/, etc.) --
 *     none of those URLs change, they're still rendered by WordPress
 *     exactly as before, just from its new /wp/ location.
 *
 * /manage/'s OWN pages (/manage/scores/standings, etc.) never reach
 * this file at all -- that's a real directory with real files, so
 * Apache serves it directly before any rewrite rule (including the
 * one that lands here) ever applies. This file only ever sees
 * requests for paths that don't correspond to a file on disk.
 */

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if ($uri === '/' || $uri === '' || $uri === '/index.php') {
    require __DIR__ . '/manage/index.php';
    exit;
}

define('WP_USE_THEMES', true);
require __DIR__ . '/wp/wp-blog-header.php';
