<?php
/** category.php — News category archive (this is what the primary "News" nav item points at). */
if (!defined('ABSPATH')) exit;
get_header();
?>
<div style="padding:28px 0 60px;">
  <h1 class="rotc-section-title"><?php single_cat_title(); ?></h1>
  <?php get_template_part('template-parts/post-grid'); ?>
</div>
<?php get_footer(); ?>
