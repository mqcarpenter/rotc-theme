<?php
/** single.php — single news post. */
if (!defined('ABSPATH')) exit;
get_header();
?>
<div class="rotc-layout">
  <main>
    <?php while (have_posts()): the_post(); ?>
      <article <?php post_class('rotc-card'); ?>>
        <header class="rotc-article-header">
          <?php
          $cats = get_the_category();
          if ($cats) echo '<span class="rotc-kicker">' . esc_html($cats[0]->name) . '</span>';
          ?>
          <h1 class="rotc-article-title"><?php the_title(); ?></h1>
          <div class="rotc-article-meta"><?php echo rotc_theme_posted_on(); ?></div>
        </header>
        <?php if (has_post_thumbnail()): ?>
          <div class="rotc-post-card-media" style="margin-bottom:16px;border-radius:var(--radius);"><?php the_post_thumbnail('large'); ?></div>
        <?php endif; ?>
        <div class="rotc-article-body">
          <?php the_content(); ?>
        </div>
        <?php
        $tags = get_the_tags();
        if ($tags):
        ?>
          <div class="rotc-tags">
            <?php foreach ($tags as $tag): ?>
              <a href="<?php echo esc_url(get_tag_link($tag)); ?>">#<?php echo esc_html($tag->name); ?></a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </article>
    <?php endwhile; ?>
  </main>

  <aside class="rotc-sidebar">
    <?php get_template_part('template-parts/sidebar-scores'); ?>
    <div class="rotc-card">
      <h3 class="rotc-section-title" style="font-size:15px;"><?php esc_html_e('More News', 'rotc-theme'); ?></h3>
      <?php
      $recent = new WP_Query(['post_type' => 'post', 'posts_per_page' => 5, 'post__not_in' => [get_the_ID()]]);
      if ($recent->have_posts()):
      ?>
        <ul style="list-style:none;margin:0;padding:0;">
          <?php while ($recent->have_posts()): $recent->the_post(); ?>
            <li style="padding:8px 0;border-bottom:1px solid var(--line);">
              <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
            </li>
          <?php endwhile; wp_reset_postdata(); ?>
        </ul>
      <?php endif; ?>
    </div>
  </aside>
</div>
<?php get_footer(); ?>
