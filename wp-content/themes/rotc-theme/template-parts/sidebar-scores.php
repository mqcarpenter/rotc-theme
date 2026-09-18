<?php
/**
 * template-parts/sidebar-scores.php
 * "Latest Scores" sidebar card -- every game from this week's real
 * league data (manage/api/wp-feed.php), compact final-score rows.
 * Included from any sidebar that wants it (single.php, page.php,
 * front-page.php) rather than baked into header/footer, since not
 * every template has a sidebar.
 */
if (!defined('ABSPATH')) exit;

$rotc_scores_feed = rotc_theme_get_league_feed();
$rotc_scores_games = $rotc_scores_feed['games'] ?? [];
if (!$rotc_scores_games) return;
?>
<div class="rotc-card">
  <h3 class="rotc-section-title" style="font-size:15px;">
    <?php echo $rotc_scores_feed['week']
        ? esc_html(sprintf(__('Week %d Scores', 'rotc-theme'), $rotc_scores_feed['week']))
        : esc_html__('Latest Scores', 'rotc-theme'); ?>
  </h3>
  <ul class="rotc-scores-mini">
    <?php foreach ($rotc_scores_games as $game): ?>
      <li>
        <a href="<?php echo esc_url($game['url']); ?>">
          <span class="rotc-scores-teams"><?php echo esc_html($game['winner']); ?> <span class="rotc-scores-vs">d.</span> <?php echo esc_html($game['loser']); ?></span>
          <span class="rotc-scores-final"><?php echo esc_html($game['score']); ?></span>
        </a>
      </li>
    <?php endforeach; ?>
  </ul>
</div>
