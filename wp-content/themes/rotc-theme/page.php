<?php
/** page.php — generic page (also backs Echo KB and wpforo pages, see inc/*-compat.php). */
if (!defined('ABSPATH')) exit;
get_header();
?>
<div class="rotc-layout">
  <main>
    <?php while (have_posts()): the_post(); ?>
      <?php get_template_part('template-parts/content', 'page'); ?>
    <?php endwhile; ?>
  </main>
  <aside class="rotc-sidebar">
    <?php get_template_part('template-parts/sidebar-scores'); ?>
  </aside>
</div>
<?php get_footer(); ?>
