<?php
/**
 * page-teams.php
 * WordPress automatically picks this up for the page whose slug is
 * "teams" (WP's template hierarchy: page-{slug}.php) -- confirmed live
 * against the site's actual database that a published "Teams" page
 * already exists at that slug.
 *
 * Two sources merged here, since neither alone tells the whole story:
 *  - The current 16 franchises + helmet art + live record, from
 *    /manage/api/wp-feed.php (real MFL data, this season only).
 *  - Echo Knowledge Base articles (epkb_post_type_1) that happen to be
 *    franchise write-ups rather than FAQ entries -- confirmed live
 *    that plugin is reused for exactly this (both current-franchise
 *    profiles like "Krypton Knights" and defunct ones like "Alamo
 *    Assault" live under the same post type). Matched to a current
 *    franchise by an exact, case-insensitive title match; anything
 *    left over after that match is a defunct/historical franchise and
 *    gets its own "Franchise History" list below the active grid.
 */
if (!defined('ABSPATH')) exit;
get_header();

$feed = rotc_theme_get_league_feed();
$franchises = $feed['franchises'] ?? [];

// One query for every published KB article, so matching each franchise
// name costs a lookup in an array already in memory, not a query per
// franchise.
$kbByTitle = [];
$kbPosts = get_posts([
    'post_type'      => 'epkb_post_type_1',
    'posts_per_page' => -1,
    'post_status'    => 'publish',
]);
foreach ($kbPosts as $kb) {
    $kbByTitle[mb_strtolower(trim($kb->post_title))] = get_permalink($kb);
}

$matchedTitles = [];
foreach ($franchises as $fr) {
    $key = mb_strtolower(trim($fr['name']));
    if (isset($kbByTitle[$key])) $matchedTitles[$key] = true;
}
$historyLinks = [];
foreach ($kbByTitle as $title => $url) {
    if (!isset($matchedTitles[$title])) $historyLinks[] = ['title' => $title, 'url' => $url];
}
?>

<div style="padding:28px 0 60px;">
  <?php while (have_posts()): the_post(); ?>
    <h1 class="rotc-section-title"><?php the_title(); ?></h1>
    <?php if (get_the_content()): ?>
      <div class="rotc-card rotc-article-body"><?php the_content(); ?></div>
    <?php endif; ?>
  <?php endwhile; ?>

  <?php if ($franchises): ?>
    <div class="rotc-news-grid">
      <?php foreach ($franchises as $fr):
        $key = mb_strtolower(trim($fr['name']));
        $kbUrl = $kbByTitle[$key] ?? null;
      ?>
        <div class="rotc-post-card" style="align-items:center;text-align:center;padding:20px 16px;">
          <?php if ($fr['helmet']): ?>
            <img src="<?php echo esc_url($fr['helmet']); ?>" alt="" style="width:80px;height:80px;object-fit:contain;margin:0 auto 10px;<?php echo $fr['helmetFlip'] ? 'transform:scaleX(-1);' : ''; ?>">
          <?php endif; ?>
          <h3 class="rotc-post-card-title" style="margin-bottom:4px;">
            <?php if ($kbUrl): ?><a href="<?php echo esc_url($kbUrl); ?>"><?php echo esc_html($fr['name']); ?></a><?php else: echo esc_html($fr['name']); endif; ?>
          </h3>
          <div style="color:var(--muted);font-size:13px;"><?php echo esc_html($fr['record']); ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <p><?php esc_html_e('Franchise data is temporarily unavailable.', 'rotc-theme'); ?></p>
  <?php endif; ?>

  <?php if ($historyLinks): ?>
    <h2 class="rotc-section-title" style="margin-top:36px;"><?php esc_html_e('Franchise History', 'rotc-theme'); ?></h2>
    <p style="color:var(--muted);font-size:14px;margin-top:-10px;"><?php esc_html_e('Defunct or renamed franchises from past seasons.', 'rotc-theme'); ?></p>
    <ul style="columns:2;list-style:none;margin:0;padding:0;">
      <?php foreach ($historyLinks as $h): ?>
        <li style="padding:6px 0;"><a href="<?php echo esc_url($h['url']); ?>"><?php echo esc_html(ucwords($h['title'])); ?></a></li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<?php get_footer(); ?>
