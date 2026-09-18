<?php
/** search.php */
if (!defined('ABSPATH')) exit;
get_header();
?>
<div style="padding:28px 0 60px;">
  <h1 class="rotc-section-title">
    <?php printf(esc_html__('Search Results for: %s', 'rotc-theme'), '<em>' . get_search_query() . '</em>'); ?>
  </h1>
  <?php get_template_part('template-parts/post-grid'); ?>
</div>
<?php get_footer(); ?>
