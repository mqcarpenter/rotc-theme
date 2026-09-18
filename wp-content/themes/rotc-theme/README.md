# rotc-theme

News-site WordPress theme for returnofthechampions.com, built to match the
visual language of the league's custom `/manage/` app (same palette, type,
card system, helmet art) rather than the stock `seos-football` theme.
Installs alongside `seos-football` as a selectable option — activating it
doesn't touch or remove the existing theme.

Pulls real league data (this week's results, standings, top performers) from
`/manage/api/wp-feed.php` server-side (`inc/league-data.php`), so the
WordPress-hosted pages and the fantasy-management app read as one site.

## Deploying

Same workflow as `/manage/` (the [rotc26](https://github.com/mqcarpenter/rotc26)
repo) — no zip uploads, no WordPress admin involved:

**First time on a given host**, replace the existing (non-git)
`wp-content/themes/rotc-theme` directory with a real clone:

```
cd wp-content/themes
mv rotc-theme rotc-theme-old   # keep as a backup until you've confirmed it works
git clone git@github.com:mqcarpenter/rotc-theme.git rotc-theme
```

**Every time after that**, from the same terminal you already use to pull
`/manage/`:

```
cd wp-content/themes/rotc-theme
git pull
```

WordPress picks up the change immediately — there's no build step, no cache
to bust beyond whatever the host's own page cache does on a file change.

The host needs its own SSH key added as a **deploy key** on this repo
(Settings → Deploy keys) — deploy keys are per-repo, so the key already
trusted for `manage` doesn't automatically work here too.
