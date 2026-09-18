<?php
/**
 * template-parts/post-grid.php
 * The card-grid + pagination body shared by index.php, archive.php,
 * category.php, and search.php -- the main WP loop is already running
 * when this is included, this just renders it.
 */
if (!defined('ABSPATH')) exit;
?>
<?php if (have_posts()): ?>
  <div class="rotc-news-grid">
    <?php while (have_posts()): the_post(); ?>
      <?php get_template_part('template-parts/content', 'post'); ?>
    <?php endwhile; ?>
  </div>
  <div style="margin-top:24px;">
    <?php the_posts_pagination(['prev_text' => __('&larr; Newer', 'rotc-theme'), 'next_text' => __('Older &rarr;', 'rotc-theme')]); ?>
  </div>
<?php else: ?>
  <p><?php esc_html_e('Nothing found.', 'rotc-theme'); ?></p>
<?php endif; ?>
