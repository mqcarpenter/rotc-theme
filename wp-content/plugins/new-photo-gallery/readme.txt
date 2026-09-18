=== Photo Gallery - Image, Video & Portfolio ===
Contributors: awordpresslife, razipathhan, hanif0991, muhammadshahid, fkfaisalkhan007, sharikkhan007, zishlife, FARAZFRANK
Donate link: https://paypal.me/awplife
Tags: photo gallery, video gallery, youtube gallery, image gallery, portfolio gallery
Requires at least: 4.0
Tested up to: 7.1
Stable tag: 2.0.3
Requires PHP: 7.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Create photo gallery and video gallery in seconds. Display images and videos in a clean grid with lightbox options. Supports block and Elementor

== Description ==

Photo Gallery is a fast, lightweight WordPress plugin designed to help you create stunning photo galleries and video galleries in minutes. Built with a native CSS Grid engine, it delivers high performance and mobile responsiveness across all devices and theme layouts.

Easily add YouTube videos simply by pasting their video links—the plugin automatically fetches high-resolution cover posters for seamless video display. Include photos and videos in the same gallery, customize grid columns, adjust hover effects, and enable interactive lightbox popups. Works effortlessly with WordPress Gutenberg blocks, Elementor page builder, and traditional shortcodes.

*Its Premium Version Name is Video Gallery Premium.*

[Premium Live Demo](https://awplife.com/demo/video-gallery-premium/)
[Get Video Gallery Premium](https://awplife.com/wordpress-plugins/video-gallery-wordpress-plugin/)

=== Key Features ===
* Native CSS Grid layout for maximum page loading speed and mobile responsiveness.
* Unified photo gallery and video gallery capabilities to display mixed media.
* Video Poster Manager to automatically fetch YouTube and Vimeo video thumbnails.
* Modern lightbox popup with thumbnail navigation strip and loop toggle.
* Grayscale filter with customizable hover percentage transitions.
* Custom grid column spacing and padding adjustments.
* Integrated shortcode generator and Elementor widget support.
* Gutenberg block integration for live backend preview.

=== Upgrade to Video Gallery Premium ===
The official premium counterpart is Video Gallery Premium. Upgrading unlocks features such as:
* Pinterest-style masonry grid layouts.
* Native API syncing for YouTube, Vimeo, Twitch Helix, TikTok, Dailymotion, Wistia, and Meta Reels.
* Server-side AJAX pagination and Load More button for large collections.
* Duplicate gallery action tool for CPT lists.
* Custom redirect targets to send visitors to targeted URLs on slide click.
* Chart.js analytics tracking for video plays and views.
* Advanced lightbox settings, custom play icons, and priority support.

== Installation ==

= Automatic Installation =
1. Go to Plugins > Add New in your WordPress dashboard.
2. Search for "Photo Gallery Starter".
3. Click Install Now and then Activate.

= Manual Installation =
1. Download the plugin zip file.
2. Go to Plugins > Add New > Upload Plugin.
3. Choose the zip file and click Install Now.
4. Activate the plugin.

= Usage =
1. Navigate to Photo & Video Gallery > Add Gallery.
2. Enter a title and upload images/videos.
3. Adjust the grid layout, columns, and lightbox options.
4. Copy the generated shortcode [NPG id=XX] and paste it into any post, page, or widget.

== Frequently Asked Questions ==

= How do I create a photo gallery? =
Navigate to Photo & Video Gallery > Add Gallery. Enter a title, upload your images using the media manager, adjust your preferences, and copy the shortcode. Paste this shortcode into your page editor.

= How do I add videos to my video gallery? =
Under the gallery settings tab, change the slide type to "Video". Paste the YouTube or Vimeo URL in the field, and use the Fetch Poster button to fetch the video's cover thumbnail automatically.

= Is the gallery responsive? =
Yes, the layout uses modern CSS Grid with responsive columns (from 1 to 6) configured for desktops, tablets, and mobile devices.

= Can I use it in Elementor or Gutenberg? =
Yes, a custom Elementor widget is provided, along with a native Gutenberg block featuring backend previews.

= What is the premium version? =
The premium version is Video Gallery Premium. It supports Masonry layout, automatic channel API syncing, AJAX pagination, and slide redirections.

== Screenshots ==

1. Upload image and video poster.
2. Layout tab options.
3. Lightbox tab options. 
4. Gallery frontend.

== Changelog ==

= 2.0.3 =
* Date: 20 July 2026
* Modernized the All Gallery CPT admin page (edit.php) list table styling and shortcode column layout to match Video Gallery.
* Enqueued plugin admin CSS and Inter fonts on the All Gallery admin page.
* Updated Live Demo and Buy Pro Version button links in the Docs page.

= 2.0.2 =
* Date: 17 July 2026
* Updated the "Upgrade to Pro" dashboard settings tab to officially showcase Video Gallery Premium features.
* Added a detailed side-by-side Free vs. Premium layout and sources comparison table.
* Corrected invalid dashicon identifier on the API features grid card to restore correct icon visibility.

= 2.0.1 =
* Date: 29 June 2026
* Fixed Gutenberg block to use ServerSideRender live preview with modern gradient placeholder.
* Fixed Gutenberg block selectability, movement, and deletion by adding pointer-events fallbacks and flow-root display styling to prevent container collapse in the editor.
* Fixed script errors in Gutenberg editor by wrapping output template's lightGallery and isotope calls in safe jQuery existence checks.
* Fixed Elementor widget styling and script dependencies enqueuing.

= 2.0.0 =
* Date 29 June 2026
* Modernization: Replaced legacy Bootstrap styling with a high-performance, native CSS Grid layout engine.
* UI/UX Upgrade: Redesigned the entire admin dashboard using a premium Indigo & Violet tabbed user interface.
* Feature: Added manual "Fetch Poster" and "Revert" action buttons for video slide thumbnails (YouTube and Vimeo).
* Feature: Added a Grayscale Amount (%) range control setting to customize the Black & White hover filter strength.
* Feature: Added a "Show Lightbox Thumbnails" toggle switch to enable/disable the lightbox thumbnail strip.
* Optimization: Enforced lightweight (medium resolution) image loading for lightbox thumbnails to resolve performance lag.
* Housekeeping: Permanently removed duplicate CSS/JS code, obsolete bootstrap dependencies, and unused icon libraries.
* Housekeeping: Refactored plugin architecture by organizing core files into dedicated assets/ and include/ directories.

= 1.5.5 =
* Security: Implemented secure Vimeo thumbnail API parsing and meta caching.
* Compliance: Updated Custom Post Type slug to 'npg_gallery' with seamless auto-migration.
* Compliance: Upgraded menu and post save capabilities from 'administrator' to 'manage_options'/'edit_post'.
* Modernization: Replaced deprecated copy commands with navigator.clipboard API.
* Housekeeping: Purged dead/unused assets.
* Housekeeping: Excised TGMPA library to eliminate intrusive admin nags and reduce plugin weight.
* Feature: Added fully dynamic, cached, and translation-ready "Our Plugins" and "Our Themes" submenus matching WP.org standards.

= 1.5.4 =
* Tested with WordPress 6.9
* New: Redesigned Settings Page with modern Card-based UI.
* Fixed: Typos in function names and shortcode logic.
* Fixed: Deprecated jQuery load event.
* Improvement: Removed unused code and variables.
* Improvement: Standardized code structure.

= 1.5.3 =
* Fixed translation loading warning for WordPress 6.7+
* Updated default gallery settings (3 column layout, full size thumbnails)
* Default thumbnails spacing set to No

= 1.5.2 =
* Tested with WordPress 6.8.3

= 1.5.1 =
* Tested with WordPress 6.8.1

= 1.5.0 =
* Bug fixes and improvements

= 1.4.9 =
* Fixed Twenty Twenty-Five theme compatibility issue
* Bug fixes

= 1.4.8 =
* Removed deprecated docs menu
* Bug fixes

= 1.4.7 =
* Added Vimeo video poster fetch functionality
* Tested with WordPress 6.7.1
* Bug fixes

= 1.4.6 =
* Tested with WordPress 6.6.2
* Bug fixes

= 1.4.5 =
* Tested with WordPress 6.6.1

= 1.4.4 =
* Tested with WordPress 6.5.4

= 1.4.3 =
* Security improvements
* Tested with WordPress 6.5.3
* Bug fixes

= 1.4.2 =
* Security improvements
* Tested with WordPress 6.5.2
* Bug fixes

= 1.4.1 =
* Tested with WordPress 6.4.3
* Bug fixes

= 1.4.0 =
* Tested with WordPress 6.4.2
* Bug fixes

== Upgrade Notice ==

= 2.0.3 =
Redesigned All Gallery page styling, improved shortcode copy UI, and updated Pro links. Recommended for all users.

= 2.0.2 =
Updated the settings panels to display correct comparison metrics and links to the premium counterpart (Video Gallery Premium). Recommended for all users.

= 2.0.1 =
Fixed Elementor widget asset dependencies, Gutenberg block selection/deletion, and resolved inline template script errors inside the editor. Recommended for all users.

= 1.5.2 =
Tested with WordPress 6.8.3. Update recommended for compatibility.

