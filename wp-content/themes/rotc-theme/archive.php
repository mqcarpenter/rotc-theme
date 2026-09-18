<?php
/** archive.php — date/tag/author archives. */
if (!defined('ABSPATH')) exit;
get_header();
?>
<div style="padding:28px 0 60px;">
  <h1 class="rotc-section-title"><?php the_archive_title(); ?></h1>
  <?php the_archive_description('<div class="rotc-card">', '</div>'); ?>
  <?php get_template_part('template-parts/post-grid'); ?>
</div>
<?php get_footer(); ?>
