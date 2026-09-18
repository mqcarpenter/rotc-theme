<?php
/**
 * template-parts/content-list-row.php
 * Horizontal "hub" row -- thumbnail + title + excerpt -- used below
 * the hero/featured strip on front-page.php, matching a classic news
 * site's "more stories" river instead of another card grid.
 */
if (!defined('ABSPATH')) exit;
?>
<article <?php post_class('rotc-news-row'); ?>>
  <?php if (has_post_thumbnail()): ?>
    <a class="rotc-news-row-media" href="<?php the_permalink(); ?>"><?php the_post_thumbnail('thumbnail'); ?></a>
  <?php endif; ?>
  <div class="rotc-news-row-body">
    <div class="rotc-post-card-date"><?php echo get_the_date(); ?></div>
    <h3 class="rotc-post-card-title" style="font-size:16px;"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <p class="rotc-post-card-excerpt" style="margin:0;"><?php echo wp_kses_post(rotc_theme_card_excerpt(160)); ?></p>
  </div>
</article>
