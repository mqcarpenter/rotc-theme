<?php
/**
@package New Photo Gallery
 * Plugin Name: Photo & Video Gallery
 * Plugin URI: https://awplife.com/wordpress-plugins/video-gallery-wordpress-plugin/
 * Description: new photo gallery plugin with lightbox preview for WordPress
 * Version: 2.0.3
 * Author: A WP Life
 * Author URI: https://awplife.com/
 * License: GPLv2 or later
 * Text Domain: new-photo-gallery
 * Domain Path: /languages
 */
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

/**
 * Normalizes column settings to a clean numeric column count.
 */
if (!function_exists('npg_get_column_count')) {
	function npg_get_column_count($value, $default = 4) {
		if (empty($value)) return $default;
		if (is_numeric($value)) return (int) $value;
		
		// Map Bootstrap-style classes (col-lg-4, col-md-6, col-6, etc.)
		if (preg_match('/(?:col-\w+-|col-)(\d+)/', $value, $matches)) {
			$span = (int) $matches[1];
			if ($span > 0) {
				return max(1, floor(12 / $span));
			}
		}
		return $default;
	}
}

if (!class_exists('New_Photo_Gallery')) {
	// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- NPG is the plugin prefix
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- NPG is the plugin prefix

	class New_Photo_Gallery
	{

		protected $protected_plugin_api;
		protected $ajax_plugin_nonce;

		public function __construct()
		{
			$this->_constants();
			$this->_hooks();
		}

		protected function _constants()
		{
			// Plugin Version
			define('NPG_VER', '2.0.3');

			// Plugin Text Domain
			define('NPG_TXTDM', 'new-photo-gallery');

			// Plugin Name
			define('NPG_PLUGIN_NAME', 'Photo & Video Gallery');

			// Plugin Slug
			define('NPG_PLUGIN_SLUG', 'npg_gallery');

			// Plugin Directory Path
			define('NPG_PLUGIN_DIR', plugin_dir_path(__FILE__));

			// Plugin Directory URL
			define('NPG_PLUGIN_URL', plugin_dir_url(__FILE__));

		} // end of constructor function

		/**
		 * Setup the default filters and actions
		 */
		protected function _hooks()
		{

			// Load text domain
			add_action('init', array($this, '_load_textdomain'));

			// add gallery menu item
			add_action('admin_menu', array($this, '_npg_menus'), 101);

			// create Image Gallery Custom Post
			add_action('init', array($this, 'light_image_gallery'));

			// Add meta box to custom post
			add_action('add_meta_boxes', array($this, '_admin_add_meta_box'));

			// loaded during admin init
			add_action('admin_init', array($this, '_admin_add_meta_box'));
			add_action('admin_init', array($this, '_npg_database_migration'));

			add_action('wp_ajax_photo_gallery_js', array(&$this, '_ajax_light_image_gallery'));
			add_action('wp_ajax_npg_fetch_video_poster', array($this, 'npg_fetch_video_poster'));
			add_action('save_post', array(&$this, '_lg_save_settings'));

			// shortcode compatibility in Text Widgets
			add_filter('widget_text', 'do_shortcode');

			// add npg cpt shortcode column - manage_{$post_type}_posts_columns
			add_filter('manage_npg_gallery_posts_columns', array(&$this, 'set_light_image_gallery_shortcode_column_name'));

			// add npg cpt shortcode column data - manage_{$post_type}_posts_custom_column
			add_action('manage_npg_gallery_posts_custom_column', array(&$this, 'custom_light_image_gallery_shortcode_data'), 10, 2);

			add_action('wp_enqueue_scripts', array(&$this, 'npg_enqueue_scripts_in_header'));

			// Admin scripts
			add_action('admin_enqueue_scripts', array($this, 'npg_admin_scripts'));

		} // end of hook function

		public function npg_enqueue_scripts_in_header()
		{
			wp_enqueue_script('jquery');
		}

		// npg cpt shortcode column before date columns
		public function set_light_image_gallery_shortcode_column_name($columns)
		{
			$new = array();
			$shortcode = isset($columns['npg_gallery_shortcode']) ? $columns['npg_gallery_shortcode'] : '';
			unset($columns['tags']); // remove it from the columns list

			foreach ($columns as $key => $value) {
				if ($key == 'date') {  // when we find the date column
					$new['npg_gallery_shortcode'] = __('Shortcode', 'new-photo-gallery');  // put the tags column before it
				}
				$new[$key] = $value;
			}
			return $new;
		}

		// npg cpt shortcode column data
		public function custom_light_image_gallery_shortcode_data($column, $post_id)
		{
			switch ($column) {
				case 'npg_gallery_shortcode':
					echo "<input type='text' id='light-image-gallery-shortcode-" . esc_attr($post_id) . "' value='[NPG id=" . esc_attr($post_id) . "]' readonly style='font-weight: 500; font-family: monospace; background-color: #f8fafc; color: #334155; border: 1px solid #cbd5e1; border-radius: 6px; text-align: center; padding: 6px 12px; height: 32px; line-height: 18px; box-shadow: none; outline: none; min-width: 140px;' />";
					echo "<input type='button' onclick='return PHOTOCopyShortcode" . esc_attr($post_id) . "();' readonly value='Copy' style='font-weight: 600; background-color: #4f46e5; color: #ffffff; border: none; border-radius: 6px; padding: 0 16px; height: 32px; line-height: 32px; cursor: pointer; transition: background 0.15s ease; box-shadow: 0 1px 2px rgba(79, 70, 229, 0.1); margin-left: 6px; display: inline-block; vertical-align: middle;' onmouseover='this.style.background=\"#4338ca\"' onmouseout='this.style.background=\"#4f46e5\"' />";
					echo "<span id='copy-msg-" . esc_attr($post_id) . "' style='display:none; background-color: #10b981; color: #ffffff; font-weight: 600; font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, Oxygen-Sans, Ubuntu, Cantarell, \"Helvetica Neue\", sans-serif; border-radius: 6px; padding: 0 12px; height: 32px; line-height: 32px; margin-left: 6px; vertical-align: middle; font-size: 13px;'>" . esc_html__('copied', 'new-photo-gallery') . "</span>";
					echo '<script>
						function PHOTOCopyShortcode' . esc_attr($post_id) . "() {
							var copyText = document.getElementById('light-image-gallery-shortcode-" . esc_attr($post_id) . "');
							var value = copyText.value;
							if (navigator.clipboard && navigator.clipboard.writeText) {
								navigator.clipboard.writeText(value).then(function() {
									copyText.select();
									jQuery('#copy-msg-" . esc_attr($post_id) . "').css('display', 'inline-block').hide().fadeIn('500', 'linear').fadeOut(2000, 'swing');
								});
							} else {
								copyText.select();
								document.execCommand('copy');
								jQuery('#copy-msg-" . esc_attr($post_id) . "').css('display', 'inline-block').hide().fadeIn('500', 'linear').fadeOut(2000, 'swing');
							}
						}
						</script>
					";
					break;
			}
		}

		// Loads the language file
		public function _load_textdomain()
		{
			load_plugin_textdomain('new-photo-gallery', false, dirname(plugin_basename(__FILE__)) . '/languages');
		}

		// Adds the photo gallery menus
		public function _npg_menus()
		{
			add_submenu_page('edit.php?post_type=' . NPG_PLUGIN_SLUG, __('Docs / How to Use', 'new-photo-gallery'), __('Docs / How to Use', 'new-photo-gallery'), 'manage_options', 'npg-docs', array($this, '_npg_docs_page'));
			add_submenu_page('edit.php?post_type=' . NPG_PLUGIN_SLUG, __('Our Themes', 'new-photo-gallery'), __('Our Themes', 'new-photo-gallery'), 'manage_options', 'npg-themes', array($this, '_npg_theme_page'));
			add_submenu_page('edit.php?post_type=' . NPG_PLUGIN_SLUG, __('Our Plugins', 'new-photo-gallery'), __('Our Plugins', 'new-photo-gallery'), 'manage_options', 'npg-plugins', array($this, '_npg_featured_plugins'));
		}

		public function _npg_docs_page()
		{
			require_once NPG_PLUGIN_DIR . 'include/docs.php';
		}

		// a wp life plugins page
		public function _npg_featured_plugins()
		{
			require_once NPG_PLUGIN_DIR . 'include/our-plugins.php';
		}

		// a wp life themes page
		public function _npg_theme_page()
		{
			require_once NPG_PLUGIN_DIR . 'include/our-themes.php';
		}


		// Photo Gallery Custom Post
		public function light_image_gallery()
		{
			$labels = array(
				'name' => __('Photo & Video Gallery', 'new-photo-gallery'),
				'singular_name' => __('Photo & Video Gallery', 'new-photo-gallery'),
				'menu_name' => __('Photo & Video Gallery', 'new-photo-gallery'),
				'name_admin_bar' => __('Photo & Video Gallery', 'new-photo-gallery'),
				'parent_item_colon' => __('Parent Item:', 'new-photo-gallery'),
				'all_items' => __('All Galleries', 'new-photo-gallery'),
				'add_new_item' => __('Add Gallery', 'new-photo-gallery'),
				'add_new' => __('Add Gallery', 'new-photo-gallery'),
				'new_item' => __('New Gallery', 'new-photo-gallery'),
				'edit_item' => __('Edit Gallery', 'new-photo-gallery'),
				'update_item' => __('Update Gallery', 'new-photo-gallery'),
				'search_items' => __('Search Gallery', 'new-photo-gallery'),
				'not_found' => __('Gallery Not found', 'new-photo-gallery'),
				'not_found_in_trash' => __('Gallery Not found in Trash', 'new-photo-gallery'),
			);
			$args = array(
				'label' => __('New Photo Gallery', 'new-photo-gallery'),
				'description' => __('Custom Post Type For New Photo Gallery', 'new-photo-gallery'),
				'labels' => $labels,
				'supports' => array('title'),
				'taxonomies' => array(),
				'hierarchical' => false,
				'public' => false,
				'show_ui' => true,
				'show_in_menu' => true,
				'menu_position' => 65,
				'menu_icon' => 'dashicons-images-alt2',
				'show_in_admin_bar' => false,
				'show_in_nav_menus' => false,
				'can_export' => true,
				'has_archive' => false,
				'exclude_from_search' => true,
				'publicly_queryable' => false,
				'query_var' => false,
				'rewrite' => false,
				'capability_type' => 'page',
			);
			register_post_type(NPG_PLUGIN_SLUG, $args);

		} // end of post type function

		// gallery setting meta box
		public function _admin_add_meta_box()
		{
			// Syntax: add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );
			add_meta_box('1', __('Copy Photo Gallery Shortcode', 'new-photo-gallery'), array(&$this, '_lg_shortcode_left_metabox'), NPG_PLUGIN_SLUG, 'side', 'high');
			add_meta_box('', __('Add Photos To Photo Gallery', 'new-photo-gallery'), array(&$this, 'lg_upload_multiple_images'), NPG_PLUGIN_SLUG, 'normal', 'high');
		}

		// image gallery copy shortcode meta box under publish button
		public function _lg_shortcode_left_metabox($post)
		{ ?>
			<p class="input-text-wrap">
				<input type="text" name="photoCopyShortcode" id="photoCopyShortcode"
					value="<?php echo '[NPG id=' . esc_attr($post->ID) . ']'; ?>" readonly
					style="height: 50px; text-align: center; width:100%;  font-size: 24px; border: 2px dashed;">
			<p id="npg-copy-code"><?php esc_html_e('Shortcode copied to clipboard!', 'new-photo-gallery'); ?></p>
			<p style="margin-top: 10px">
				<?php esc_html_e('Copy & Embed shotcode into any Page/ Post / Text Widget to display gallery.', 'new-photo-gallery'); ?>
			</p>
			</p>
			<span onclick="copyToClipboard('#photoCopyShortcode')" class="npg-copy dashicons dashicons-clipboard"></span>
			<style>
				.npg-copy {
					position: absolute;
					top: 9px;
					right: 24px;
					font-size: 26px;
					cursor: pointer;
				}

				.ui-sortable-handle>span {
					font-size: 16px !important;
				}
			</style>
			<script>
				jQuery("#npg-copy-code").hide();
				function copyToClipboard(element) {
					var value = jQuery(element).val();
					if (navigator.clipboard && navigator.clipboard.writeText) {
						navigator.clipboard.writeText(value).then(function() {
							jQuery("#photoCopyShortcode").select();
							jQuery("#npg-copy-code").fadeIn().delay(2000).fadeOut();
						});
					} else {
						var $temp = jQuery("<input>");
						jQuery("body").append($temp);
						$temp.val(value).select();
						document.execCommand("copy");
						$temp.remove();
						jQuery("#photoCopyShortcode").select();
						jQuery("#npg-copy-code").fadeIn().delay(2000).fadeOut();
					}
				}
			</script>
			<?php
		}



		public function lg_upload_multiple_images($post)
		{
			require_once NPG_PLUGIN_DIR . 'include/settings.php';
		} // end of upload multiple image

		public function _ajax_light_image_gallery()
		{
			if (current_user_can('manage_options')) {
				$nonce = isset($_POST['lg_add_images_nonce']) ? sanitize_text_field(wp_unslash($_POST['lg_add_images_nonce'])) : '';
				if (wp_verify_nonce($nonce, 'lg_add_images')) {
					$slide_id = isset($_POST['slideId']) ? absint(wp_unslash($_POST['slideId'])) : 0;
					$this->_lg_ajax_callback_function($slide_id);
					wp_die();
				} else {
					print 'Sorry, your nonce did not verify.';
					exit;
				}
			}
		}

		public function npg_fetch_video_poster() {
			if (!current_user_can('manage_options')) {
				wp_send_json_error(array('message' => 'Unauthorized'));
			}
			$video_url = isset($_POST['video_url']) ? esc_url_raw(wp_unslash($_POST['video_url'])) : '';
			if (empty($video_url)) {
				wp_send_json_error(array('message' => 'Empty Video URL'));
			}

			$poster_url = '';
			if (strpos($video_url, 'youtube') !== false || strpos($video_url, 'youtu.be') !== false) {
				if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/', $video_url, $matches)) {
					$video_id = $matches[1];
					$poster_url = "https://img.youtube.com/vi/{$video_id}/hqdefault.jpg";
				}
			} elseif (strpos($video_url, 'vimeo') !== false) {
				if (preg_match('/vimeo\.com\/(?:video\/)?([0-9]+)/', $video_url, $matches)) {
					$video_id = $matches[1];
					$response = wp_safe_remote_get("https://vimeo.com/api/v2/video/{$video_id}.json", array('timeout' => 5));
					if (!is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200) {
						$data = json_decode(wp_remote_retrieve_body($response), true);
						if (!empty($data[0]['thumbnail_large'])) {
							$poster_url = esc_url_raw($data[0]['thumbnail_large']);
						} elseif (!empty($data[0]['thumbnail_medium'])) {
							$poster_url = esc_url_raw($data[0]['thumbnail_medium']);
						}
					}
				}
			}

			if ($poster_url) {
				wp_send_json_success(array('poster_url' => $poster_url));
			} else {
				wp_send_json_error(array('message' => 'Could not fetch poster from the URL'));
			}
		}

		public function _lg_ajax_callback_function($id)
		{
			$thumbnail = wp_get_attachment_image_src($id, 'medium', true);
			$attachment = get_post($id);
			?>
			<li class="npg-image-slide" id="<?php echo esc_attr($id); ?>" data-position="<?php echo esc_attr($id); ?>">
				<div class="npg-image-preview">
					<div class="npg-image-controls">
						<div class="npg-move-handle" title="<?php esc_attr_e('Drag to reorder', 'new-photo-gallery'); ?>"><span class="dashicons dashicons-move"></span></div>
						<a class="pw-trash-icon remove-slide" name="remove-slide" href="#" title="<?php esc_attr_e('Delete photo', 'new-photo-gallery'); ?>"><span class="dashicons dashicons-trash"></span></a>
					</div>
					<img class="photo" src="<?php echo esc_url($thumbnail[0]); ?>" alt="<?php echo esc_html(get_the_title($id)); ?>" data-default-src="<?php echo esc_url($thumbnail[0]); ?>">
				</div>

				<div class="npg-image-info">
					<input type="hidden" name="slide-ids[]" value="<?php echo esc_attr($id); ?>" />

					<!-- Type -->
					<select name="slide-type[]" class="npg-input-sm photo-type" style="width: 100%;">
						<option value="image" selected="selected">
							<?php esc_html_e('Image', 'new-photo-gallery'); ?>
						</option>
						<option value="video">
							<?php esc_html_e('Video', 'new-photo-gallery'); ?>
						</option>
					</select>

					<!-- Title -->
					<input type="text" name="slide-title[]" class="npg-input-sm photo-title"
						placeholder="<?php esc_html_e('Title', 'new-photo-gallery'); ?>"
						value="<?php echo esc_attr(get_the_title($id)); ?>" style="width: 100%;">

					<!-- Link -->
					<div class="photo-link-wrapper" style="width: 100%; display:none;">
						<input type="text" name="slide-link[]" class="npg-input-sm photo-link"
							placeholder="<?php esc_html_e('Video URL', 'new-photo-gallery'); ?>" style="width: 100%;">
						<input type="hidden" name="slide-poster[]" class="photo-poster" value="" />
						<div class="npg-poster-actions" style="margin-top: 8px; display: flex; gap: 8px;">
							<button type="button" class="button button-secondary npg-fetch-poster-btn" style="flex: 1; font-size: 11px; padding: 0 8px; min-height: 26px; line-height: 24px;">
								<span class="dashicons dashicons-download" style="font-size: 14px; width: 14px; height: 14px; line-height: 14px; margin-top: 5px;"></span> Fetch Poster
							</button>
							<button type="button" class="button button-link npg-revert-poster-btn" style="color: #a00; text-decoration: none; font-size: 11px; padding: 0 4px; display: none;">
								Revert
							</button>
						</div>
					</div>
				</div>
			</li>
			<?php
		}

		public function _lg_save_settings($post_id)
		{
			if (current_user_can('edit_post', $post_id)) {
				$nonce = isset($_POST['lg_save_nonce']) ? sanitize_text_field(wp_unslash($_POST['lg_save_nonce'])) : '';
				if (wp_verify_nonce($nonce, 'lg_save_settings')) {

					$gal_thumb_size = isset($_POST['gal_thumb_size']) ? sanitize_text_field(wp_unslash($_POST['gal_thumb_size'])) : '';
					$col_large_desktops = isset($_POST['col_large_desktops']) ? sanitize_text_field(wp_unslash($_POST['col_large_desktops'])) : '';
					$col_desktops = isset($_POST['col_desktops']) ? sanitize_text_field(wp_unslash($_POST['col_desktops'])) : '';
					$col_tablets = isset($_POST['col_tablets']) ? sanitize_text_field(wp_unslash($_POST['col_tablets'])) : '';
					$col_phones = isset($_POST['col_phones']) ? sanitize_text_field(wp_unslash($_POST['col_phones'])) : '';
					$image_hover_effect_type = isset($_POST['image_hover_effect_type']) ? sanitize_text_field(wp_unslash($_POST['image_hover_effect_type'])) : '';
					$image_hover_effect_four = isset($_POST['image_hover_effect_four']) ? sanitize_text_field(wp_unslash($_POST['image_hover_effect_four'])) : '';
					$transition_effects = isset($_POST['transition_effects']) ? sanitize_text_field(wp_unslash($_POST['transition_effects'])) : '';
					$thumbnails_spacing = isset($_POST['thumbnails_spacing']) ? sanitize_text_field(wp_unslash($_POST['thumbnails_spacing'])) : '';
					$img_title = isset($_POST['img_title']) ? sanitize_text_field(wp_unslash($_POST['img_title'])) : '';
					$thumbnail_order = isset($_POST['thumbnail_order']) ? sanitize_text_field(wp_unslash($_POST['thumbnail_order'])) : '';
					$show_lightbox_loop = isset($_POST['show_lightbox_loop']) ? sanitize_text_field(wp_unslash($_POST['show_lightbox_loop'])) : '';
					$lightbox_thumbnails = isset($_POST['lightbox_thumbnails']) ? sanitize_text_field(wp_unslash($_POST['lightbox_thumbnails'])) : '';
					$light_box = isset($_POST['light-box']) ? sanitize_text_field(wp_unslash($_POST['light-box'])) : 1;

					$i = 0;
					$image_ids = array();
					$image_titles = array();
					$image_type = array();
					$slide_link = array();
					$slide_poster = array();
					// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized below with array_map
					$image_ids_val = isset($_POST['slide-ids']) ? array_map('absint', wp_unslash((array) $_POST['slide-ids'])) : array();

					foreach ($image_ids_val as $image_id) {
						$image_ids[] = $image_id;
						// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Values are accessed by index
						$image_titles[] = isset($_POST['slide-title'][$i]) ? sanitize_text_field(wp_unslash($_POST['slide-title'][$i])) : '';
						// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Values are accessed by index
						$image_type[] = isset($_POST['slide-type'][$i]) ? sanitize_text_field(wp_unslash($_POST['slide-type'][$i])) : '';
						// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Values are accessed by index
						$slide_link[] = isset($_POST['slide-link'][$i]) ? esc_url_raw(wp_unslash($_POST['slide-link'][$i])) : '';
						// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash -- Values are accessed by index
						$slide_poster[] = isset($_POST['slide-poster'][$i]) ? esc_url_raw(wp_unslash($_POST['slide-poster'][$i])) : '';
						$single_image_update = array(
							'ID' => $image_id,
							'post_title' => $image_titles[$i],
						);
						wp_update_post($single_image_update);
						$i++;
					}

					$image_grayscale = isset($_POST['image_grayscale']) ? sanitize_text_field(wp_unslash($_POST['image_grayscale'])) : '';
					$grayscale_percentage = isset($_POST['grayscale_percentage']) ? sanitize_text_field(wp_unslash($_POST['grayscale_percentage'])) : '80';

					$gallery_settings = array(
						'slide-ids' => $image_ids,
						'slide-title' => $image_titles,
						'slide-type' => $image_type,
						'slide-link' => $slide_link,
						'slide-poster' => $slide_poster,
						'gal_thumb_size' => $gal_thumb_size,
						'col_large_desktops' => $col_large_desktops,
						'col_desktops' => $col_desktops,
						'col_tablets' => $col_tablets,
						'col_phones' => $col_phones,
						'image_hover_effect_type' => $image_hover_effect_type,
						'image_hover_effect_four' => $image_hover_effect_four,
						'transition_effects' => $transition_effects,
						'thumbnails_spacing' => $thumbnails_spacing,
						'img_title' => $img_title,
						'thumbnail_order' => $thumbnail_order,
						'show_lightbox_loop' => $show_lightbox_loop,
						'lightbox_thumbnails' => $lightbox_thumbnails,
						'image_grayscale' => $image_grayscale,
						'grayscale_percentage' => $grayscale_percentage,
						'light-box' => $light_box,
					);
					$awl_light_image_gallery_shortcode_setting = 'awl_lg_settings_' . $post_id;
					update_post_meta($post_id, $awl_light_image_gallery_shortcode_setting, $gallery_settings);
				}
			}
		}//end _lg_save_settings()


		// database CPT slug migration
		public function _npg_database_migration()
		{
			if (get_option('npg_db_version_1_5_4') !== 'done') {
				global $wpdb;
				$wpdb->query("UPDATE {$wpdb->posts} SET post_type = 'npg_gallery' WHERE post_type = '_light_image_gallery'");
				update_option('npg_db_version_1_5_4', 'done');
			}
		}
		// Admin scripts
		public function npg_admin_scripts($hook)
		{
			global $post, $typenow;
			$current_post_type = !empty($typenow) ? $typenow : ($post ? $post->post_type : '');
			if (empty($current_post_type) && isset($_GET['post_type'])) {
				$current_post_type = sanitize_key($_GET['post_type']);
			}

			if ($current_post_type === NPG_PLUGIN_SLUG || $hook == 'post-new.php' || $hook == 'post.php' || $hook == 'edit.php') {
				if ($current_post_type === NPG_PLUGIN_SLUG) {
					if ($hook == 'post-new.php' || $hook == 'post.php') {
						wp_enqueue_script('media-upload');
						wp_enqueue_script('awplife-npg-uploader-js', NPG_PLUGIN_URL . 'assets/js/awplife-npg-uploader.js', array('jquery'), NPG_VER, true);
						wp_enqueue_media();
					}

					// Admin Layout CSS & Fonts for post edit and post list pages
					wp_enqueue_style('npg-admin-css', NPG_PLUGIN_URL . 'assets/css/npg-admin.css', array(), NPG_VER);
					wp_enqueue_style('npg-google-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap', array(), null);
				}
			}

			if (strpos($hook, 'npg-themes') !== false || strpos($hook, 'npg-plugins') !== false) {
				wp_enqueue_style('ig-our-plugins-style', NPG_PLUGIN_URL . 'assets/css/our-plugins-style.css', array(), NPG_VER);
				wp_enqueue_style('thickbox');
				wp_enqueue_script('thickbox');
			}

			if (strpos($hook, 'npg-docs') !== false) {
				wp_enqueue_style('npg-docs-css', NPG_PLUGIN_URL . 'assets/css/npg-docs.css', array(), NPG_VER);
			}
		}

	}//end class

	// register sf scripts
	function npg_register_scripts()
	{
		// css & JS
		wp_register_script('awplife-npg-isotope-js', NPG_PLUGIN_URL . 'assets/js/isotope.pkgd.js', array(), NPG_VER, true);
		wp_register_style('npg-frontend-css', NPG_PLUGIN_URL . 'assets/css/npg-frontend.css', array(), NPG_VER);
	}
	add_action('wp_enqueue_scripts', 'npg_register_scripts');


	/**
	 * Instantiates the Class
	 */
	$npg_gallery_object = new New_Photo_Gallery();
	require_once NPG_PLUGIN_DIR . 'include/shortcode.php';
	require_once NPG_PLUGIN_DIR . 'include/elementor-widget.php';
	require_once NPG_PLUGIN_DIR . 'include/gutenberg-block.php';
} // end of class exists
?>