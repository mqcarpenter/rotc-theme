<?php
/**
 * Template Name: Home Page
 *
 * page-template/home-page.php
 * Exists at this exact path because the site's "Home" page (post ID 2,
 * the assigned static front page) has this literal path stored as its
 * _wp_page_template meta value -- a leftover from seos-football,
 * confirmed live via that page's REST API `template` field. A static
 * front page's own explicit Page Template takes priority over
 * front-page.php entirely, so without this file WordPress was falling
 * back to page.php and rendering that page's actual saved content --
 * itself years-stale (a hardcoded 2022 "championship trophy" block,
 * not any kind of dynamic feed).
 *
 * The `Template Name` header above makes this selectable from Page
 * Attributes if anyone ever wants to; it isn't what makes WordPress
 * use it here (that's simply the file existing at the stored path).
 *
 * Shares the same body as front-page.php via
 * template-parts/homepage-content.php -- one homepage design, not two
 * to keep in sync.
 */
if (!defined('ABSPATH')) exit;
get_header();
get_template_part('template-parts/homepage-content');
get_footer();
