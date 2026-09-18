<?php
/**
 * template-parts/content-page.php
 * Generic page body wrapper, used by page.php. Kept intentionally
 * plain -- Echo Knowledge Base and wpforo both render their own markup
 * into the_content() on the pages they're assigned to (see
 * inc/epkb-compat.php and inc/wpforo-compat.php), so this must not
 * impose extra structure that would fight either plugin's own layout.
 */
if (!defined('ABSPATH')) exit;
?>
<article <?php post_class('rotc-card'); ?>>
  <header class="rotc-article-header">
    <h1 class="rotc-article-title"><?php the_title(); ?></h1>
  </header>
  <?php if (has_post_thumbnail()): ?>
    <div class="rotc-post-card-media" style="margin-bottom:16px;border-radius:var(--radius);"><?php the_post_thumbnail('large'); ?></div>
  <?php endif; ?>
  <div class="rotc-article-body">
    <?php the_content(); ?>
  </div>
</article>
