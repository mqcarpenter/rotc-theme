<?php
/**
 * page.php — generic page (also backs Echo KB and wpforo pages, see
 * inc/*-compat.php).
 *
 * The forum runs full-width, no sidebar -- wpforo's own layout (topic
 * lists, category grids, the reply editor) is built to use the whole
 * content column, and squeezing it into this theme's two-column
 * layout just cramped it for no benefit (confirmed live: the forum
 * page was showing the "Latest Scores"/"League Community" sidebar
 * alongside it, which has nothing to do with the forum). Any other
 * page keeps the normal layout.
 */
if (!defined('ABSPATH')) exit;
get_header();
$isForumPage = function_exists('wpforo_is_forum_page') && wpforo_is_forum_page();
?>
<?php if ($isForumPage): ?>
  <div style="padding:28px 0 60px;">
    <?php while (have_posts()): the_post(); ?>
      <?php get_template_part('template-parts/content', 'page'); ?>
    <?php endwhile; ?>
  </div>
<?php else: ?>
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
<?php endif; ?>
<?php get_footer(); ?>
