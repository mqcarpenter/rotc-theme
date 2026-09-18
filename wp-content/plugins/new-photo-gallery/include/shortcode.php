<?php
// New Photo Gallery Shortcode

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- These are local template variables
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedFunctionFound -- NPG is the plugin prefix

add_shortcode('NPG', 'npg_photo_gallery_shortcode');
function npg_photo_gallery_shortcode($atts)
{
	ob_start();
	// JS
	wp_enqueue_script('imagesloaded');
	wp_enqueue_script('awplife-npg-isotope-js');

	// custom grid css
	wp_enqueue_style('npg-frontend-css');

	// unsterilized	
	$atts = shortcode_atts(
		array(
			'id' => '',
		),
		$atts,
		'NPG'
	);
	$gallery_settings = get_post_meta($atts['id'], 'awl_lg_settings_' . $atts['id'], true);

	$light_image_gallery_id = esc_attr($atts['id']);

	// columns settings
	$gal_thumb_size = isset($gallery_settings['gal_thumb_size']) ? $gallery_settings['gal_thumb_size'] : 'thumbnail';
	
	$col_lg = npg_get_column_count(isset($gallery_settings['col_large_desktops']) ? $gallery_settings['col_large_desktops'] : 'col-lg-4', 4);
	$col_md = npg_get_column_count(isset($gallery_settings['col_desktops']) ? $gallery_settings['col_desktops'] : 'col-md-4', 3);
	$col_sm = npg_get_column_count(isset($gallery_settings['col_tablets']) ? $gallery_settings['col_tablets'] : 'col-sm-4', 2);
	$col_xs = npg_get_column_count(isset($gallery_settings['col_phones']) ? $gallery_settings['col_phones'] : 'col-xs-6', 1);

	// lightbox style
	if (isset($gallery_settings['light-box'])) {
		$light_box = $gallery_settings['light-box'];
	} else {
		$light_box = 1;
	}

	// transition effect
	if (isset($gallery_settings['transition_effects'])) {
		$transition_effects = $gallery_settings['transition_effects'];
	} else {
		$transition_effects = 'lg-fade';
	}
	if ($transition_effects != 'none') {
		// transition effects css
		wp_enqueue_style('awplife-npg-lg-transitions-css', NPG_PLUGIN_URL . 'include/lightbox/light-gallery/css/lg-transitions.css', array(), NPG_VER);
	}

	// hover effect
	if (isset($gallery_settings['image_hover_effect_type'])) {
		$image_hover_effect_type = $gallery_settings['image_hover_effect_type'];
	} else {
		$image_hover_effect_type = 'sg';
	}
	if ($image_hover_effect_type == 'no') {
		$image_hover_effect = '';
	} else {
		// hover CSS
		wp_enqueue_style('lg-hover-css', NPG_PLUGIN_URL . 'assets/css/hover.css', array(), NPG_VER);
	}

	if ($image_hover_effect_type == 'sg') {
		if (isset($gallery_settings['image_hover_effect_four'])) {
			$image_hover_effect = $gallery_settings['image_hover_effect_four'];
		} else {
			$image_hover_effect = 'hvr-grow-shadow';
		}
	}

	$thumbnails_spacing = isset($gallery_settings['thumbnails_spacing']) ? (int)$gallery_settings['thumbnails_spacing'] : 1;
	$spacing_val = ($thumbnails_spacing == 1) ? 8 : 0;
	$gutter = $spacing_val . 'px';
	$radius = ($thumbnails_spacing == 1) ? '8px' : '0px';

	$img_title = isset($gallery_settings['img_title']) ? (int)$gallery_settings['img_title'] : 1;
	$thumbnail_order = isset($gallery_settings['thumbnail_order']) ? $gallery_settings['thumbnail_order'] : 'ASC';
	$show_lightbox_loop = isset($gallery_settings['show_lightbox_loop']) ? (int)$gallery_settings['show_lightbox_loop'] : 1;
	$lightbox_thumbnails = isset($gallery_settings['lightbox_thumbnails']) ? (int)$gallery_settings['lightbox_thumbnails'] : 1;

	// New Features
	$image_grayscale = isset($gallery_settings['image_grayscale']) ? $gallery_settings['image_grayscale'] : 0;
	$grayscale_percentage = isset($gallery_settings['grayscale_percentage']) ? (int)$gallery_settings['grayscale_percentage'] : 80;
	?>
	<!-- CSS Part Start From Here-->
	<style>
		#animated-thumbnails-<?php echo esc_attr($light_image_gallery_id); ?> {
			--npg-gutter: <?php echo esc_attr($gutter); ?>;
			--npg-cols-lg: <?php echo esc_attr($col_lg); ?>;
			--npg-cols-md: <?php echo esc_attr($col_md); ?>;
			--npg-cols-sm: <?php echo esc_attr($col_sm); ?>;
			--npg-cols-xs: <?php echo esc_attr($col_xs); ?>;
			--npg-radius: <?php echo esc_attr($radius); ?>;
			--npg-card-radius: <?php echo esc_attr($radius); ?>;
			--npg-card-padding: 8px;
		}

		#animated-thumbnails-<?php echo esc_attr($light_image_gallery_id); ?> a {
			text-decoration: none !important;
			box-shadow: 0 0px 0 0 currentcolor !important;
		}

		/* Grayscale Effect */
		<?php if ($image_grayscale == 1) { ?>
			#animated-thumbnails-<?php echo esc_attr($light_image_gallery_id); ?> .npg-grayscale {
				filter: grayscale(<?php echo esc_attr($grayscale_percentage); ?>%);
				-webkit-filter: grayscale(<?php echo esc_attr($grayscale_percentage); ?>%);
				transition: all 0.5s ease;
			}

			#animated-thumbnails-<?php echo esc_attr($light_image_gallery_id); ?> .npg-grayscale:hover {
				filter: grayscale(0%);
				-webkit-filter: grayscale(0%);
			}
		<?php } ?>

		#animated-thumbnails-<?php echo esc_attr($light_image_gallery_id); ?> img {
			width: 100% !important;
			height: auto !important;
		}
	</style>
	<?php
	require NPG_PLUGIN_DIR . 'include/output.php';
	return ob_get_clean();
}