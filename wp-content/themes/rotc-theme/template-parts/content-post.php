<?php
/**
 * template-parts/content-post.php
 * News-card partial, reused by front-page.php, index.php, archive.php,
 * category.php, and search.php so the grid always looks the same.
 */
if (!defined('ABSPATH')) exit;
?>
<article <?php post_class('rotc-post-card'); ?>>
  <?php if (has_post_thumbnail()): ?>
    <a class="rotc-post-card-media" href="<?php the_permalink(); ?>">
      <?php the_post_thumbnail('medium_large'); ?>
    </a>
  <?php endif; ?>
  <div class="rotc-post-card-body">
    <div class="rotc-post-card-date"><?php echo get_the_date(); ?></div>
    <h3 class="rotc-post-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    <p class="rotc-post-card-excerpt"><?php echo wp_kses_post(rotc_theme_card_excerpt()); ?></p>
    <a class="rotc-post-card-more" href="<?php the_permalink(); ?>"><?php esc_html_e('Continue Reading', 'rotc-theme'); ?> &rarr;</a>
  </div>
</article>
