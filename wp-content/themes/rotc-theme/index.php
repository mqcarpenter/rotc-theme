<?php
/** index.php — fallback blog listing (front-page.php handles the real homepage). */
if (!defined('ABSPATH')) exit;
get_header();
?>
<div style="padding:28px 0 60px;">
  <h1 class="rotc-section-title"><?php esc_html_e('News', 'rotc-theme'); ?></h1>
  <?php get_template_part('template-parts/post-grid'); ?>
</div>
<?php get_footer(); ?>
