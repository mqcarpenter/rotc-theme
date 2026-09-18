<?php
/**
 * front-page.php
 * Used whenever the site's front-page setting resolves here normally
 * (e.g. "Your latest posts", or a static front page with no custom
 * Page Template of its own). The actual "Home" page currently has a
 * leftover custom template assigned (see page-template/home-page.php's
 * doc comment), so THAT file is what really renders today -- this one
 * stays as the correct fallback if that assignment is ever cleared.
 * Both share the same body via template-parts/homepage-content.php so
 * there is exactly one homepage design, not two to keep in sync.
 */
if (!defined('ABSPATH')) exit;
get_header();
get_template_part('template-parts/homepage-content');
get_footer();
