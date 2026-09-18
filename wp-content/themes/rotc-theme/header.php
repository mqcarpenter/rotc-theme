<?php
/**
 * header.php
 * Visually mirrors /manage/'s dark top nav (rotc-nav in mfl26.css) --
 * same ink/accent tokens, same condensed-uppercase link style, and
 * deliberately the SAME small 36px icon-only brand mark (no wordmark,
 * no hero-sized logo image) -- see templates/header.php +
 * .rotc-brand/.rotc-logo in mfl26.css over there. Built with
 * wp_nav_menu() instead of that app's hand-rolled MFL-auth-aware
 * markup, since this theme has no equivalent login state to show.
 *
 * Deliberately does NOT use WP's custom-logo feature: this site's
 * custom_logo option is already set to the old seos-football hero
 * image (giant script wordmark over a player photo), and rendering
 * that here is exactly the "too big and consuming" header this was
 * rewritten to get away from. The icon path below is the same file
 * /manage/ itself links to, not a copy.
 */
if (!defined('ABSPATH')) exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="rotc-nav">
  <div class="rotc-nav-inner">
    <a class="rotc-brand" href="<?php echo esc_url(home_url('/')); ?>">
      <img class="rotc-logo" src="<?php echo esc_url(trailingslashit(home_url()) . 'manage/assets/img/rotc-icon.png'); ?>" alt="<?php bloginfo('name'); ?>" width="36" height="36">
    </a>

    <button class="rotc-nav-toggle" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle menu', 'rotc-theme'); ?>">&#9776; Menu</button>

    <?php
    if (has_nav_menu('primary')) {
        wp_nav_menu([
            'theme_location' => 'primary',
            'container'      => false,
            'menu_class'     => 'rotc-nav-menu',
            'fallback_cb'    => false,
        ]);
    } else {
        rotc_theme_fallback_menu();
    }
    ?>
  </div>
</header>

<main class="rotc-wrap">
