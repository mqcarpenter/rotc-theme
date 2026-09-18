<?php
/**
 * inc/league-data.php
 * Server-side bridge to the fantasy-management app's JSON feed
 * (manage/api/wp-feed.php) -- see that file's doc comment for exactly
 * what it returns and why this goes through one small JSON contract
 * instead of WordPress reaching into that app's HTML/PHP directly.
 *
 * Cached in a transient so a homepage view doesn't cost a fresh HTTP
 * round trip on every single request -- 5 minutes, matching the
 * Cache-Control header wp-feed.php itself sends.
 */

if (!defined('ABSPATH')) exit;

const ROTC_LEAGUE_FEED_TRANSIENT = 'rotc_league_feed';
const ROTC_LEAGUE_FEED_TTL = 300;

/**
 * @return array|null Decoded feed (see manage/api/wp-feed.php's doc
 *   comment for shape), or null if the app is unreachable or the
 *   response wasn't valid JSON -- callers must treat null the same as
 *   "nothing to show" and degrade gracefully, never fatal.
 */
function rotc_theme_get_league_feed(): ?array {
    $cached = get_transient(ROTC_LEAGUE_FEED_TRANSIENT);
    if (is_array($cached)) return $cached;

    // /manage/ is a same-domain subdirectory app, not a separate host --
    // built off home_url() rather than a hardcoded domain so this also
    // works on a staging copy of the site without editing PHP.
    $url = trailingslashit(home_url()) . 'manage/api/wp-feed.php';
    $response = wp_remote_get($url, ['timeout' => 5]);

    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        // Serve a stale copy rather than nothing, same fallback
        // philosophy the source app itself uses for its own MFL calls.
        $stale = get_transient(ROTC_LEAGUE_FEED_TRANSIENT . '_stale');
        return is_array($stale) ? $stale : null;
    }

    $data = json_decode(wp_remote_retrieve_body($response), true);
    if (!is_array($data)) return null;

    set_transient(ROTC_LEAGUE_FEED_TRANSIENT, $data, ROTC_LEAGUE_FEED_TTL);
    // Kept for a day past its normal TTL purely as an outage fallback --
    // this second transient is intentionally longer-lived than the
    // primary one above.
    set_transient(ROTC_LEAGUE_FEED_TRANSIENT . '_stale', $data, DAY_IN_SECONDS);
    return $data;
}
