<?php
/** 404.php */
if (!defined('ABSPATH')) exit;
get_header();
?>
<div class="rotc-card" style="margin:28px 0 60px;text-align:center;padding:60px 20px;">
  <span class="rotc-kicker"><?php esc_html_e('404', 'rotc-theme'); ?></span>
  <h1 class="rotc-article-title"><?php esc_html_e('Nothing here.', 'rotc-theme'); ?></h1>
  <p><?php esc_html_e("That page doesn't exist, or moved.", 'rotc-theme'); ?></p>
  <p><a class="rotc-btn" href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Back to Home', 'rotc-theme'); ?></a></p>
  <?php get_search_form(); ?>
</div>
<?php get_footer(); ?>
