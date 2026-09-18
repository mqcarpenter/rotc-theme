<?php
/**
 * front-page.php
 * The news-site homepage: a real-league snapshot pulled from
 * /manage/api/wp-feed.php up top, then CMS-controlled content (latest
 * posts) below as the news grid, per Matteo's call to merge the two
 * rather than keep them as separate experiences.
 *
 * The snapshot mirrors the interactive hero+tiles pattern
 * /manage/templates/weekly-recap-hub.php already uses: every game this
 * week is pre-rendered (hidden/shown with a class toggle, no AJAX)
 * with the Game of the Week showing first, and the small tiles beside
 * it swap which one is primary on click.
 */
if (!defined('ABSPATH')) exit;
get_header();

$feed = rotc_theme_get_league_feed();
$games = $feed['games'] ?? [];
?>

<?php if ($feed && ($games || $feed['standings'])): ?>
<section class="rotc-card">
  <span class="rotc-kicker">
    <?php echo $feed['week'] ? esc_html(sprintf(__('Week %d, %d Season', 'rotc-theme'), $feed['week'], $feed['year'])) : esc_html__('This Week in the League', 'rotc-theme'); ?>
  </span>

  <div class="rotc-league-wrap">
    <div id="rotc-league-primary">
      <?php foreach ($games as $i => $game): ?>
        <div class="rotc-league-hero" data-game-index="<?php echo (int) $i; ?>"<?php echo $i === 0 ? '' : ' hidden'; ?>>
          <?php if ($game['helmet']): ?>
            <img class="rotc-league-hero-helmet" src="<?php echo esc_url($game['helmet']); ?>" alt=""
                 style="<?php echo $game['helmetFlip'] ? 'transform:scaleX(-1);' : ''; ?>">
          <?php endif; ?>
          <div class="rotc-league-hero-body">
            <span class="rotc-kicker"><?php echo $game['isGameOfWeek'] ? esc_html__('Game of the Week', 'rotc-theme') : esc_html__('This Week', 'rotc-theme'); ?></span>
            <h1 class="rotc-league-headline"><?php echo esc_html($game['headline']); ?></h1>
            <div class="rotc-league-score"><?php echo esc_html__('Final:', 'rotc-theme') . ' ' . esc_html($game['score']); ?></div>
            <p class="rotc-league-excerpt"><?php echo esc_html($game['excerpt']); ?></p>
            <?php if ($game['topPerformer']): $tp = $game['topPerformer']; ?>
              <div class="rotc-top-performer" style="margin:10px 0;">
                <?php if ($tp['photo']): ?><img src="<?php echo esc_url($tp['photo']); ?>" alt=""><?php endif; ?>
                <div>
                  <strong><?php echo esc_html($tp['name']); ?></strong> &middot; <?php echo esc_html($tp['team']); ?><br>
                  <span style="color:var(--muted);font-size:13px;"><?php echo esc_html($tp['franchise']); ?> &mdash; <?php echo esc_html(number_format((float) $tp['score'], 1)); ?> pts</span>
                </div>
              </div>
            <?php endif; ?>
            <a class="rotc-league-cta" href="<?php echo esc_url($game['url']); ?>"><?php esc_html_e('Full Recap', 'rotc-theme'); ?> &rarr;</a>
          </div>
        </div>
      <?php endforeach; ?>
      <?php if (!$games): ?><p><?php esc_html_e('No recap available yet this week.', 'rotc-theme'); ?></p><?php endif; ?>
    </div>

    <?php if (count($games) > 1): ?>
      <div class="rotc-league-tiles">
        <?php foreach ($games as $i => $game): ?>
          <button type="button" class="rotc-league-tile<?php echo $i === 0 ? ' is-active' : ''; ?>" data-game-index="<?php echo (int) $i; ?>">
            <?php if ($game['helmet']): ?><img src="<?php echo esc_url($game['helmet']); ?>" alt="" style="<?php echo $game['helmetFlip'] ? 'transform:scaleX(-1);' : ''; ?>"><?php endif; ?>
            <span>
              <span class="rotc-league-tile-kicker"><?php echo $game['isGameOfWeek'] ? esc_html__('Game of the Week', 'rotc-theme') : esc_html__('Final', 'rotc-theme'); ?></span>
              <span class="rotc-league-tile-headline"><?php echo esc_html($game['headline']); ?></span>
            </span>
          </button>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <?php if ($feed['standings']): ?>
    <div style="padding-top:18px;margin-top:18px;border-top:1px solid var(--line);">
      <h3 class="rotc-section-title" style="font-size:15px;"><?php esc_html_e('Standings', 'rotc-theme'); ?></h3>
      <ul class="rotc-standings-mini">
        <?php foreach ($feed['standings'] as $row): ?>
          <li>
            <span class="rotc-standings-rank"><?php echo (int) $row['rank']; ?></span>
            <?php if ($row['helmet']): ?><img src="<?php echo esc_url($row['helmet']); ?>" alt="" style="<?php echo $row['helmetFlip'] ? 'transform:scaleX(-1);' : ''; ?>"><?php endif; ?>
            <span class="rotc-standings-name"><?php echo esc_html($row['name']); ?></span>
            <span class="rotc-standings-record"><?php echo esc_html($row['record']); ?></span>
          </li>
        <?php endforeach; ?>
      </ul>
      <p style="margin-top:10px;"><a class="rotc-league-cta" href="<?php echo esc_url(trailingslashit(home_url()) . 'manage/scores/standings'); ?>"><?php esc_html_e('Full Standings', 'rotc-theme'); ?> &rarr;</a></p>
    </div>
  <?php endif; ?>
</section>

<?php if (count($games) > 1): ?>
<script>
(function () {
  var primary = document.getElementById('rotc-league-primary');
  if (!primary) return;
  var cards = primary.querySelectorAll('[data-game-index]');
  var tiles = document.querySelectorAll('.rotc-league-tile');
  tiles.forEach(function (btn) {
    btn.addEventListener('click', function () {
      var idx = btn.dataset.gameIndex;
      cards.forEach(function (c) { c.hidden = (c.dataset.gameIndex !== idx); });
      tiles.forEach(function (t) { t.classList.remove('is-active'); });
      btn.classList.add('is-active');
    });
  });
})();
</script>
<?php endif; ?>
<?php endif; ?>

<?php
/**
 * News section: hero (latest post) + a strip of the next four, then a
 * hub-style river (thumbnail + excerpt rows) for everything after
 * that -- a real news-site front page, not a flat grid of equal cards.
 */
// ignore_sticky_posts: a news homepage's hero should be whatever is
// genuinely most recent -- confirmed live a 2022 post ("John T.
// Buckley Championship Trophy") was pinned to the hero slot ahead of
// everything published since, purely because it's marked Sticky in
// WordPress. Sticky is a blog-index concept; it has no place deciding
// what counts as this week's lead story.
$newsQuery = new WP_Query(['post_type' => 'post', 'posts_per_page' => 9, 'post_status' => 'publish', 'ignore_sticky_posts' => true]);
$newsPosts = $newsQuery->posts;
$hero = $newsPosts[0] ?? null;
$featured = array_slice($newsPosts, 1, 4);
$river = array_slice($newsPosts, 5);
?>

<div class="rotc-layout">
  <div>
    <h2 class="rotc-section-title"><?php esc_html_e('Latest News', 'rotc-theme'); ?></h2>

    <?php if ($hero): setup_postdata($hero); ?>
      <article class="rotc-news-hero">
        <?php if (has_post_thumbnail($hero)): ?>
          <a class="rotc-news-hero-media" href="<?php echo esc_url(get_permalink($hero)); ?>"><?php echo get_the_post_thumbnail($hero, 'large'); ?></a>
        <?php endif; ?>
        <div class="rotc-news-hero-body">
          <div class="rotc-post-card-date"><?php echo esc_html(get_the_date('', $hero)); ?></div>
          <h3 class="rotc-post-card-title" style="font-size:26px;"><a href="<?php echo esc_url(get_permalink($hero)); ?>"><?php echo esc_html(get_the_title($hero)); ?></a></h3>
          <p class="rotc-post-card-excerpt"><?php echo wp_kses_post(rotc_theme_card_excerpt(220)); ?></p>
          <a class="rotc-post-card-more" href="<?php echo esc_url(get_permalink($hero)); ?>"><?php esc_html_e('Continue Reading', 'rotc-theme'); ?> &rarr;</a>
        </div>
      </article>
    <?php endif; ?>

    <?php if ($featured): ?>
      <div class="rotc-news-grid" style="margin-top:20px;">
        <?php foreach ($featured as $post): setup_postdata($post); ?>
          <?php get_template_part('template-parts/content', 'post'); ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if ($river): ?>
      <div class="rotc-news-river">
        <?php foreach ($river as $post): setup_postdata($post); ?>
          <?php get_template_part('template-parts/content', 'list-row'); ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php if (!$newsPosts): ?>
      <p><?php esc_html_e('No news posted yet.', 'rotc-theme'); ?></p>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
  </div>

  <aside class="rotc-sidebar">
    <?php get_template_part('template-parts/sidebar-scores'); ?>
    <div class="rotc-card">
      <h3 class="rotc-section-title" style="font-size:15px;"><?php esc_html_e('League Community', 'rotc-theme'); ?></h3>
      <p><a class="rotc-league-cta" href="<?php echo esc_url(rotc_theme_page_url('community')); ?>"><?php esc_html_e('Smack Board', 'rotc-theme'); ?> &rarr;</a></p>
      <p><a class="rotc-league-cta" href="<?php echo esc_url(rotc_theme_page_url('faq')); ?>"><?php esc_html_e('FAQ / Knowledge Base', 'rotc-theme'); ?> &rarr;</a></p>
    </div>
    <?php if (is_active_sidebar('rotc-footer')): ?>
      <div class="rotc-card"><?php dynamic_sidebar('rotc-footer'); ?></div>
    <?php endif; ?>
  </aside>
</div>

<?php get_footer(); ?>
