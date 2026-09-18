<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
// phpcs:disable WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- These are local template variables, not global

if ($light_box != 0) {
	wp_enqueue_style('awplife-npg-light-gallery-css', NPG_PLUGIN_URL . 'include/lightbox/light-gallery/css/lightgallery.css', array(), NPG_VER);
	// transition effects css
	if ($transition_effects != 'none') {
		wp_enqueue_style('awplife-npg-transitions-css', NPG_PLUGIN_URL . 'include/lightbox/light-gallery/css/lg-transitions.css', array(), NPG_VER);
	}
	wp_enqueue_script('jquery');
	wp_enqueue_script('awplife-npg-light-gallery-js', NPG_PLUGIN_URL . 'include/lightbox/light-gallery/js/lightgallery.js', array('jquery'), NPG_VER, true);
	wp_enqueue_script('awplife-npg-all-plugins-js', NPG_PLUGIN_URL . 'include/lightbox/light-gallery/js/lightgallery-all.js', array('jquery'), NPG_VER, true);
} else {
	wp_enqueue_script('jquery');
}

$npg_allslides = array(
	'p' => $light_image_gallery_id,
	'post_type' => NPG_PLUGIN_SLUG,
	'orderby' => 'ASC',
);
$npg_loop = new WP_Query($npg_allslides);
while ($npg_loop->have_posts()):
	$npg_loop->the_post();

	$post_id = esc_attr(get_the_ID());

	$gallery_settings = get_post_meta($post_id, 'awl_lg_settings_' . $post_id, true);

	$card_class = ($thumbnails_spacing == 1) ? 'npg-has-card-border' : '';

	// start the image gallery contents
	?>
	<div id="animated-thumbnails-<?php echo esc_attr($light_image_gallery_id); ?>"
		class="npg-row all-images-<?php echo esc_attr($light_image_gallery_id); ?>">
		<div class="grid-sizer"></div>
		<?php
		if (isset($gallery_settings['slide-ids']) && count($gallery_settings['slide-ids']) > 0) {
			$slide_ids = $gallery_settings['slide-ids'];
			
			// Handle Sorting/Ordering
			$ordered_slides = array();
			foreach ($slide_ids as $idx => $id) {
				$ordered_slides[] = array('id' => $id, 'index' => $idx);
			}

			if ($thumbnail_order == 'DESC') {
				$ordered_slides = array_reverse($ordered_slides);
			} elseif ($thumbnail_order == 'RANDOM') {
				shuffle($ordered_slides);
			}

			$count = 0;
			foreach ($ordered_slides as $slide_item) {
				$attachment_id = $slide_item['id'];
				$orig_idx = $slide_item['index'];

				$thumb = wp_get_attachment_image_src($attachment_id, 'thumb', true);
				$thumbnail = wp_get_attachment_image_src($attachment_id, 'thumbnail', true);
				$medium = wp_get_attachment_image_src($attachment_id, 'medium', true);
				$large = wp_get_attachment_image_src($attachment_id, 'large', true);
				$full = wp_get_attachment_image_src($attachment_id, 'full', true);
				$postthumbnail = wp_get_attachment_image_src($attachment_id, 'post-thumbnail', true);
				$attachment_details = get_post($attachment_id);
				$href = get_permalink($attachment_details->ID);
				$src = $attachment_details->guid;
				$title = $attachment_details->post_title;
				$description = $attachment_details->post_content;
				$image_type = $gallery_settings['slide-type'][$orig_idx];
				$image_link = $gallery_settings['slide-link'][$orig_idx];
				$image_poster = isset($gallery_settings['slide-poster'][$orig_idx]) ? $gallery_settings['slide-poster'][$orig_idx] : '';
				$image_alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true);

				if ($image_alt == '') {
					$image_alt = $title;
				}

				// set thumbnail size
				if ($gal_thumb_size == 'thumbnail') {
					$thumbnail_url = $thumbnail[0];
				}
				if ($gal_thumb_size == 'medium') {
					$thumbnail_url = $medium[0];
				}
				if ($gal_thumb_size == 'large') {
					$thumbnail_url = $large[0];
				}
				if ($gal_thumb_size == 'full') {
					$thumbnail_url = $full[0];
				}

				if (!empty($image_poster)) {
					$thumbnail_url = $image_poster;
				}

				// New Features Classes & Attributes
				$extra_classes = '';
				if (isset($image_grayscale) && $image_grayscale == 1) {
					$extra_classes .= ' npg-grayscale';
				}
				?>
				<?php if ($image_type == 'image') { ?>
					<div class="npg-col single-image" data-index="<?php echo esc_attr($count); ?>">
						<div class="npg-image-card <?php echo esc_attr($card_class); ?> <?php echo esc_attr($image_hover_effect); ?>">
							<?php if ($light_box != 0) { ?>
							<a href="<?php echo esc_url($full[0]); ?>"
								data-thumb="<?php echo esc_url($medium[0]); ?>"
								class="single-image-<?php echo esc_attr($light_image_gallery_id); ?>"
								data-sub-html="<?php echo esc_attr($title); ?>">
							<?php } ?>
								<div class="npg-image-container">
									<img class="<?php echo esc_attr($extra_classes); ?>"
										src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" />
									<?php if ($img_title == 1) { ?>
										<div class="npg-overlay npg-overlay-bottom">
											<span class="npg-item-title"><?php echo esc_html($title); ?></span>
										</div>
									<?php } ?>
								</div>
							<?php if ($light_box != 0) { ?>
							</a>
							<?php } ?>
						</div>
					</div>
				<?php } ?>

				<?php if ($image_type == 'video') { ?>
					<?php
					// Auto poster fetch logic removed as requested
					?>
					<div class="npg-col single-image" data-index="<?php echo esc_attr($count); ?>">
						<div class="npg-image-card <?php echo esc_attr($card_class); ?> <?php echo esc_attr($image_hover_effect); ?>">
							<?php if ($light_box != 0) { ?>
							<a href="<?php echo esc_url($image_link); ?>" data-poster="<?php echo esc_url($thumbnail_url); ?>"
								data-thumb="<?php echo !empty($image_poster) ? esc_url($image_poster) : esc_url($thumbnail[0]); ?>"
								class="single-image-<?php echo esc_attr($light_image_gallery_id); ?>"
								data-sub-html="<?php echo esc_attr($title); ?>">
							<?php } ?>
								<div class="npg-image-container">
									<img class="<?php echo esc_attr($extra_classes); ?>"
										src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" />
									<?php if ($img_title == 1) { ?>
										<div class="npg-overlay npg-overlay-bottom">
											<span class="npg-item-title"><?php echo esc_html($title); ?></span>
										</div>
									<?php } ?>
								</div>
							<?php if ($light_box != 0) { ?>
							</a>
							<?php } ?>
						</div>
					</div>
				<?php } ?>

				<?php
				$count++;
			}// end of attachment for each
		} else {
			esc_html_e('Sorry! No photo gallery found ', 'new-photo-gallery');
			echo ": [NPG id=" . esc_attr($post_id) . "]";
		} // end of if else of slides available check into slider
		?>
	</div>
	<?php
endwhile;
wp_reset_postdata();
?>
<?php if ($light_box != 0) { ?>
<script>
	//thumbnail or fixed Size thumbnail
	jQuery(window).on('load', function () {
		if (jQuery.fn.lightGallery) {
			jQuery('#animated-thumbnails-<?php echo esc_js($light_image_gallery_id); ?>').lightGallery({
				selector: '.single-image-<?php echo esc_js($light_image_gallery_id); ?>',
				thumbnail: <?php echo ($lightbox_thumbnails == 0) ? 'false' : 'true'; ?>,
				exThumbImage: 'data-thumb',
				animateThumb: true,
				showThumbByDefault: true,
				subHtmlSelectorRelative: true,
				share: false,
				download: false,
				loop: <?php echo ($show_lightbox_loop == 0) ? 'false' : 'true'; ?>,
				<?php if ($transition_effects != 'none') { ?>
										mode: '<?php echo esc_js($transition_effects); ?>',
				<?php } ?>
			});
		}
	});	
</script>
<?php } ?>
<style>
	.lg-backdrop.in {
		opacity: 0.85;
	}

	.fixed-size.lg-outer .lg-inner {
		background-color: #FFF;
	}

	.fixed-size.lg-outer .lg-sub-html {
		position: absolute;
		text-align: left;
	}

	.fixed-size.lg-outer .lg-toolbar {
		background-color: transparent;
		height: 0;
	}

	.fixed-size.lg-outer .lg-toolbar .lg-icon {
		color: #FFF;
	}

	.fixed-size.lg-outer .lg-img-wrap {
		padding: 12px;
	}
</style>
<script>
	jQuery(document).ready(function () {
		if (jQuery.fn.isotope) {
			// isotope effect function
			// Method 1 - Initialize Isotope, then trigger layout after each image loads.
			var $grid = jQuery('.all-images-<?php echo esc_js($light_image_gallery_id); ?>').isotope({
				// options...
				itemSelector: '.single-image',
				layoutMode: 'masonry',
				masonry: {
					columnWidth: '.grid-sizer',
					percentPosition: true
				},
				transitionDuration: '0.6s'
			});
			// layout Isotope after each image loads
			$grid.imagesLoaded().progress(function () {
				$grid.isotope('layout');
			});

			setTimeout(function() {
				jQuery('.all-images-<?php echo esc_js($light_image_gallery_id); ?>').addClass('npg-loaded');
			}, 100);
		} else {
			jQuery('.all-images-<?php echo esc_js($light_image_gallery_id); ?>').addClass('npg-loaded');
		}
	});
</script>