<?php
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

// Load Settings
$post_id = esc_attr($post->ID);
$gallery_settings = get_post_meta($post->ID, 'awl_lg_settings_' . $post->ID, true);

// Defaults
$defaults = array(
	'gal_thumb_size' => 'full',
	'col_large_desktops' => 'col-lg-4',
	'col_desktops' => 'col-md-4',
	'col_tablets' => 'col-sm-4',
	'col_phones' => 'col-xs-6',
	'image_hover_effect_type' => 'sg',
	'image_hover_effect_four' => 'hvr-grow-shadow',
	'transition_effects' => 'lg-fade',
	'image_grayscale' => 0,
	'grayscale_percentage' => 80,
	'thumbnails_spacing' => 1,
	'img_title' => 1,
	'thumbnail_order' => 'ASC',
	'show_lightbox_loop' => 1,
	'lightbox_thumbnails' => 1,
	'light-box' => 1,
);

// Merge saved settings with defaults
$settings = wp_parse_args($gallery_settings, $defaults);

// Normalize column settings for Admin UI consistency
$col_lg_val = npg_get_column_count($settings['col_large_desktops'], 4);
$col_md_val = npg_get_column_count($settings['col_desktops'], 3);
$col_sm_val = npg_get_column_count($settings['col_tablets'], 2);
$col_xs_val = npg_get_column_count($settings['col_phones'], 1);

?>

<div class="npg-settings-wrapper">
	<?php wp_nonce_field('lg_save_settings', 'lg_save_nonce'); ?>

	<!-- Navigation Tabs -->
	<div class="npg-tabs-nav">
		<a href="#" class="nav-item active" data-target="tab-add-photos">
			<span class="dashicons dashicons-format-image"></span> <?php esc_html_e('Add Photos', 'new-photo-gallery'); ?>
		</a>
		<a href="#" class="nav-item" data-target="tab-layout-design">
			<span class="dashicons dashicons-layout"></span> <?php esc_html_e('Layout & Design', 'new-photo-gallery'); ?>
		</a>
		<a href="#" class="nav-item" data-target="tab-lightbox">
			<span class="dashicons dashicons-welcome-view-site"></span> <?php esc_html_e('Lightbox', 'new-photo-gallery'); ?>
		</a>
		<a href="#" class="nav-item npg-pro-tab" data-target="tab-upgrade-pro" style="color: #f59e0b; font-weight: 600;">
			<span class="dashicons dashicons-star-filled" style="color: #f59e0b;"></span> <?php esc_html_e('Upgrade to Pro', 'new-photo-gallery'); ?>
		</a>
	</div>

	<!-- Content Area -->
	<div class="npg-tabs-content-wrapper">

		<!-- Tab 1: Add Photos -->
		<div class="npg-tab-content active" id="tab-add-photos">
			<div class="file-upload">
				<div class="image-upload-wrap" id="add-new-photos">
					<div class="drag-text">
						<span class="dashicons dashicons-cloud-upload" style="font-size: 40px; width: 40px; height: 40px; color: var(--npg-primary); margin-bottom: 15px; display: block; margin-left: auto; margin-right: auto;"></span>
						<h3>
							<?php esc_html_e('ADD PHOTOS', 'new-photo-gallery'); ?>
						</h3>
						<?php wp_nonce_field('lg_add_images', 'lg_add_images_nonce'); ?>
					</div>
				</div>
			</div>

			<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
				<div class="npg-button-group">
					<button type="button" class="npg-btn npg-btn-secondary" onclick="return NPGISortSlides('ASC');">
						<span class="dashicons dashicons-sort"></span> <?php esc_html_e('Ascending', 'new-photo-gallery'); ?>
					</button>
					<button type="button" class="npg-btn npg-btn-secondary" onclick="return NPGISortSlides('DESC');">
						<span class="dashicons dashicons-sort"></span> <?php esc_html_e('Descending', 'new-photo-gallery'); ?>
					</button>
				</div>
				<button type="button" id="remove-all-photos" class="npg-btn npg-btn-danger">
					<span class="dashicons dashicons-trash"></span> <?php esc_html_e('Delete All Photos', 'new-photo-gallery'); ?>
				</button>
			</div>

			<ul id="remove-photos" class="sbox npg-listitems photo-box">
				<?php
				if (isset($gallery_settings['slide-ids'])) {
					$count = 0;
					foreach ($gallery_settings['slide-ids'] as $id) {
						$default_thumbnail = wp_get_attachment_image_src($id, 'medium', true);
						$image_link = isset($gallery_settings['slide-link'][$count]) ? $gallery_settings['slide-link'][$count] : '';
						$image_type = isset($gallery_settings['slide-type'][$count]) ? $gallery_settings['slide-type'][$count] : 'image';
						$image_poster = isset($gallery_settings['slide-poster'][$count]) ? $gallery_settings['slide-poster'][$count] : '';
						$preview_url = !empty($image_poster) ? $image_poster : $default_thumbnail[0];
						?>
						<li class="npg-image-slide" id="<?php echo esc_attr($id); ?>" data-position="<?php echo esc_attr($id); ?>">
							<div class="npg-image-preview">
								<div class="npg-image-controls">
									<div class="npg-move-handle" title="<?php esc_attr_e('Drag to reorder', 'new-photo-gallery'); ?>"><span class="dashicons dashicons-move"></span></div>
									<a class="pw-trash-icon remove-slide" name="remove-slide" href="#" title="<?php esc_attr_e('Delete photo', 'new-photo-gallery'); ?>"><span class="dashicons dashicons-trash"></span></a>
								</div>
								<img class="photo" src="<?php echo esc_url($preview_url); ?>" alt="<?php echo esc_html(get_the_title($id)); ?>" data-default-src="<?php echo esc_url($default_thumbnail[0]); ?>">
							</div>

							<div class="npg-image-info">
								<input type="hidden" name="slide-ids[]" value="<?php echo esc_attr($id); ?>" />

								<!-- Type -->
								<select name="slide-type[]" class="npg-input-sm photo-type" style="width: 100%;">
									<option value="image" <?php selected($image_type, 'image'); ?>>
										<?php esc_html_e('Image', 'new-photo-gallery'); ?>
									</option>
									<option value="video" <?php selected($image_type, 'video'); ?>>
										<?php esc_html_e('Video', 'new-photo-gallery'); ?>
									</option>
								</select>

								<!-- Title -->
								<input type="text" name="slide-title[]" class="npg-input-sm photo-title"
									placeholder="<?php esc_html_e('Title', 'new-photo-gallery'); ?>"
									value="<?php echo esc_attr(get_the_title($id)); ?>" style="width: 100%;">

								<!-- Link -->
								<div class="photo-link-wrapper" style="width: 100%; <?php echo ($image_type == 'video') ? '' : 'display:none;'; ?>">
									<input type="text" name="slide-link[]" class="npg-input-sm photo-link"
										placeholder="<?php esc_html_e('Video URL', 'new-photo-gallery'); ?>"
										value="<?php echo esc_attr($image_link); ?>" style="width: 100%;">
									<input type="hidden" name="slide-poster[]" class="photo-poster" value="<?php echo esc_attr($image_poster); ?>" />
									<div class="npg-poster-actions" style="margin-top: 8px; display: flex; gap: 8px;">
										<button type="button" class="button button-secondary npg-fetch-poster-btn" style="flex: 1; font-size: 11px; padding: 0 8px; min-height: 26px; line-height: 24px;">
											<span class="dashicons dashicons-download" style="font-size: 14px; width: 14px; height: 14px; line-height: 14px; margin-top: 5px;"></span> Fetch Poster
										</button>
										<button type="button" class="button button-link npg-revert-poster-btn" style="color: #a00; text-decoration: none; font-size: 11px; padding: 0 4px; display: <?php echo empty($image_poster) ? 'none' : 'inline-block'; ?>;">
											Revert
										</button>
									</div>
								</div>
							</div>
						</li>
						<?php
						$count++;
					} // end of for each
				} //end of if
				?>
			</ul>
		</div>

		<!-- Tab 2: Layout & Design -->
		<div class="npg-tab-content" id="tab-layout-design">
			
			<!-- Card 1: Grid options -->
			<div class="npg-card npg-card-compact">
				<!-- Thumbnail Size -->
				<div class="npg-setting-row">
					<div class="npg-setting-label">
						<h4><span class="dashicons dashicons-image-filter"></span> <?php esc_html_e('Thumbnail Resolution', 'new-photo-gallery'); ?></h4>
						<p><?php esc_html_e('Select the resolution size for gallery images.', 'new-photo-gallery'); ?></p>
					</div>
					<div class="npg-setting-field">
						<select id="gal_thumb_size" name="gal_thumb_size" class="npg-select">
							<option value="thumbnail" <?php selected($settings['gal_thumb_size'], 'thumbnail'); ?>><?php esc_html_e('Thumbnail – 150 × 150', 'new-photo-gallery'); ?></option>
							<option value="medium" <?php selected($settings['gal_thumb_size'], 'medium'); ?>><?php esc_html_e('Medium – 300 × 169', 'new-photo-gallery'); ?></option>
							<option value="large" <?php selected($settings['gal_thumb_size'], 'large'); ?>><?php esc_html_e('Large – 840 × 473', 'new-photo-gallery'); ?></option>
							<option value="full" <?php selected($settings['gal_thumb_size'], 'full'); ?>><?php esc_html_e('Full Size – Original', 'new-photo-gallery'); ?></option>
						</select>
					</div>
				</div>

				<!-- Columns -->
				<div class="npg-setting-row">
					<div class="npg-setting-label">
						<h4><span class="dashicons dashicons-desktop"></span> <?php esc_html_e('Responsive Columns Layout', 'new-photo-gallery'); ?></h4>
						<p><?php esc_html_e('Configure columns for each device breakpoint size.', 'new-photo-gallery'); ?></p>
					</div>
					<div class="npg-setting-field">
						<div class="npg-responsive-cols">
							<!-- X-Large Desktop -->
							<div class="npg-col-item">
								<label><?php esc_html_e('X-Large Screens', 'new-photo-gallery'); ?></label>
								<select name="col_large_desktops" class="npg-select">
									<option value="col-lg-12" <?php selected($settings['col_large_desktops'], 'col-lg-12'); ?>><?php esc_html_e('1 Column', 'new-photo-gallery'); ?></option>
									<option value="col-lg-6" <?php selected($settings['col_large_desktops'], 'col-lg-6'); ?>><?php esc_html_e('2 Columns', 'new-photo-gallery'); ?></option>
									<option value="col-lg-4" <?php selected($settings['col_large_desktops'], 'col-lg-4'); ?>><?php esc_html_e('3 Columns', 'new-photo-gallery'); ?></option>
									<option value="col-lg-3" <?php selected($settings['col_large_desktops'], 'col-lg-3'); ?>><?php esc_html_e('4 Columns', 'new-photo-gallery'); ?></option>
									<option value="col-lg-2" <?php selected($settings['col_large_desktops'], 'col-lg-2'); ?>><?php esc_html_e('6 Columns', 'new-photo-gallery'); ?></option>
								</select>
							</div>

							<!-- Desktop -->
							<div class="npg-col-item">
								<label><?php esc_html_e('Desktop', 'new-photo-gallery'); ?></label>
								<select name="col_desktops" class="npg-select">
									<option value="col-md-12" <?php selected($settings['col_desktops'], 'col-md-12'); ?>><?php esc_html_e('1 Column', 'new-photo-gallery'); ?></option>
									<option value="col-md-6" <?php selected($settings['col_desktops'], 'col-md-6'); ?>><?php esc_html_e('2 Columns', 'new-photo-gallery'); ?></option>
									<option value="col-md-4" <?php selected($settings['col_desktops'], 'col-md-4'); ?>><?php esc_html_e('3 Columns', 'new-photo-gallery'); ?></option>
									<option value="col-md-3" <?php selected($settings['col_desktops'], 'col-md-3'); ?>><?php esc_html_e('4 Columns', 'new-photo-gallery'); ?></option>
									<option value="col-md-2" <?php selected($settings['col_desktops'], 'col-md-2'); ?>><?php esc_html_e('6 Columns', 'new-photo-gallery'); ?></option>
								</select>
							</div>

							<!-- Tablet -->
							<div class="npg-col-item">
								<label><?php esc_html_e('Tablet', 'new-photo-gallery'); ?></label>
								<select name="col_tablets" class="npg-select">
									<option value="col-sm-12" <?php selected($settings['col_tablets'], 'col-sm-12'); ?>><?php esc_html_e('1 Column', 'new-photo-gallery'); ?></option>
									<option value="col-sm-6" <?php selected($settings['col_tablets'], 'col-sm-6'); ?>><?php esc_html_e('2 Columns', 'new-photo-gallery'); ?></option>
									<option value="col-sm-4" <?php selected($settings['col_tablets'], 'col-sm-4'); ?>><?php esc_html_e('3 Columns', 'new-photo-gallery'); ?></option>
									<option value="col-sm-3" <?php selected($settings['col_tablets'], 'col-sm-3'); ?>><?php esc_html_e('4 Columns', 'new-photo-gallery'); ?></option>
								</select>
							</div>

							<!-- Phone -->
							<div class="npg-col-item">
								<label><?php esc_html_e('Phone', 'new-photo-gallery'); ?></label>
								<select name="col_phones" class="npg-select">
									<option value="col-xs-12" <?php selected($settings['col_phones'], 'col-xs-12'); ?>><?php esc_html_e('1 Column', 'new-photo-gallery'); ?></option>
									<option value="col-xs-6" <?php selected($settings['col_phones'], 'col-xs-6'); ?>><?php esc_html_e('2 Columns', 'new-photo-gallery'); ?></option>
									<option value="col-xs-4" <?php selected($settings['col_phones'], 'col-xs-4'); ?>><?php esc_html_e('3 Columns', 'new-photo-gallery'); ?></option>
									<option value="col-xs-3" <?php selected($settings['col_phones'], 'col-xs-3'); ?>><?php esc_html_e('4 Columns', 'new-photo-gallery'); ?></option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- Card 3: Hover Animations & Grayscale -->
			<div class="npg-card npg-card-compact">
				
				<!-- Thumbnail Spacing -->
				<div class="npg-setting-row">
					<div class="npg-setting-label">
						<h4><span class="dashicons dashicons-editor-expand"></span> <?php esc_html_e('Thumbnail Spacing (Gap)', 'new-photo-gallery'); ?></h4>
						<p><?php esc_html_e('Enable or disable gap spacing between thumbnails.', 'new-photo-gallery'); ?></p>
					</div>
					<div class="npg-setting-field">
						<div class="npg-segmented-control">
							<input type="radio" id="spacing_yes" name="thumbnails_spacing" value="1" <?php checked($settings['thumbnails_spacing'], 1); ?> />
							<label for="spacing_yes"><?php esc_html_e('Yes', 'new-photo-gallery'); ?></label>
							<input type="radio" id="spacing_no" name="thumbnails_spacing" value="0" <?php checked($settings['thumbnails_spacing'], 0); ?> />
							<label for="spacing_no"><?php esc_html_e('No', 'new-photo-gallery'); ?></label>
						</div>
					</div>
				</div>

				<!-- Thumbnail Title -->
				<div class="npg-setting-row">
					<div class="npg-setting-label">
						<h4><span class="dashicons dashicons-editor-quote"></span> <?php esc_html_e('Thumbnail Title', 'new-photo-gallery'); ?></h4>
						<p><?php esc_html_e('Display titles overlaid on thumbnails on the frontend.', 'new-photo-gallery'); ?></p>
					</div>
					<div class="npg-setting-field">
						<div class="npg-segmented-control">
							<input type="radio" id="img_title_yes" name="img_title" value="1" <?php checked($settings['img_title'], 1); ?>>
							<label for="img_title_yes"><?php esc_html_e('Yes', 'new-photo-gallery'); ?></label>
							<input type="radio" id="img_title_no" name="img_title" value="0" <?php checked($settings['img_title'], 0); ?>>
							<label for="img_title_no"><?php esc_html_e('No', 'new-photo-gallery'); ?></label>
						</div>
					</div>
				</div>
				
				<!-- Hover Effect Type -->
				<div class="npg-setting-row">
					<div class="npg-setting-label">
						<h4><span class="dashicons dashicons-admin-appearance"></span> <?php esc_html_e('Image Hover Effect', 'new-photo-gallery'); ?></h4>
						<p><?php esc_html_e('Select effect for gallery thumbnails mouseover.', 'new-photo-gallery'); ?></p>
					</div>
					<div class="npg-setting-field npg-flex-wrap">
						<div class="npg-segmented-control">
							<input type="radio" id="hover_sg" name="image_hover_effect_type" value="sg" <?php checked($settings['image_hover_effect_type'], 'sg'); ?> />
							<label for="hover_sg"><?php esc_html_e('Shadow & Glow', 'new-photo-gallery'); ?></label>
							<input type="radio" id="hover_none" name="image_hover_effect_type" value="no" <?php checked($settings['image_hover_effect_type'], 'no'); ?> />
							<label for="hover_none"><?php esc_html_e('None', 'new-photo-gallery'); ?></label>
						</div>

						<div class="npg-inline-options he_two" style="<?php echo ($settings['image_hover_effect_type'] == 'no') ? 'display:none;' : ''; ?>">
							<span class="npg-option-label"><?php esc_html_e('OPTION:', 'new-photo-gallery'); ?></span>
							<select name="image_hover_effect_four" id="image_hover_effect_four" class="npg-select">
								<option value="hvr-grow-shadow" <?php selected($settings['image_hover_effect_four'], 'hvr-grow-shadow'); ?>>Grow Shadow</option>
								<option value="hvr-float-shadow" <?php selected($settings['image_hover_effect_four'], 'hvr-float-shadow'); ?>>Float Shadow</option>
								<option value="hvr-glow" <?php selected($settings['image_hover_effect_four'], 'hvr-glow'); ?>>Glow</option>
								<option value="hvr-box-shadow-outset" <?php selected($settings['image_hover_effect_four'], 'hvr-box-shadow-outset'); ?>>Box Shadow Outset</option>
								<option value="hvr-box-shadow-inset" <?php selected($settings['image_hover_effect_four'], 'hvr-box-shadow-inset'); ?>>Box Shadow Inset</option>
							</select>
						</div>
					</div>
				</div>

				<!-- Grayscale Effect -->
				<div class="npg-setting-row">
					<div class="npg-setting-label">
						<h4><span class="dashicons dashicons-art"></span> <?php esc_html_e('Grayscale Effect', 'new-photo-gallery'); ?></h4>
						<p><?php esc_html_e('Show thumbnails in Black & White, restoring color on hover.', 'new-photo-gallery'); ?></p>
					</div>
					<div class="npg-setting-field" style="display: flex; flex-direction: column; align-items: flex-start; gap: 15px;">
						<div class="npg-segmented-control">
							<input type="radio" id="gray_yes" name="image_grayscale" value="1" <?php checked($settings['image_grayscale'], 1); ?> />
							<label for="gray_yes"><?php esc_html_e('Yes', 'new-photo-gallery'); ?></label>
							<input type="radio" id="gray_no" name="image_grayscale" value="0" <?php checked($settings['image_grayscale'], 0); ?> />
							<label for="gray_no"><?php esc_html_e('No', 'new-photo-gallery'); ?></label>
						</div>

						<div class="npg-inline-options grayscale_pct_wrapper" style="<?php echo ($settings['image_grayscale'] == 0) ? 'display:none;' : ''; ?>">
							<span class="npg-option-label" style="font-weight: 600; font-size: 13px; color: var(--npg-text-muted); margin-right: 8px;"><?php esc_html_e('Grayscale Amount (%):', 'new-photo-gallery'); ?></span>
							<input type="number" id="grayscale_percentage" name="grayscale_percentage" min="0" max="100" value="<?php echo esc_attr($settings['grayscale_percentage']); ?>" class="npg-input" style="width: 80px; text-align: center;" />
						</div>
					</div>
				</div>
			</div>

			<!-- Card 4: Automatic Sort Order -->
			<div class="npg-card npg-card-compact">
				<div class="npg-setting-row">
					<div class="npg-setting-label">
						<h4><span class="dashicons dashicons-sort"></span> <?php esc_html_e('Automatic Sort Order', 'new-photo-gallery'); ?></h4>
						<p><?php esc_html_e('Configure display order sorting for frontend gallery display.', 'new-photo-gallery'); ?></p>
					</div>
					<div class="npg-setting-field">
						<div class="npg-segmented-control">
							<input type="radio" id="order_asc" name="thumbnail_order" value="ASC" <?php checked($settings['thumbnail_order'], 'ASC'); ?>>
							<label for="order_asc"><?php esc_html_e('Oldest First', 'new-photo-gallery'); ?></label>
							
							<input type="radio" id="order_desc" name="thumbnail_order" value="DESC" <?php checked($settings['thumbnail_order'], 'DESC'); ?>>
							<label for="order_desc"><?php esc_html_e('Newest First', 'new-photo-gallery'); ?></label>
							
							<input type="radio" id="order_rnd" name="thumbnail_order" value="RANDOM" <?php checked($settings['thumbnail_order'], 'RANDOM'); ?>>
							<label for="order_rnd"><?php esc_html_e('Random', 'new-photo-gallery'); ?></label>
						</div>
					</div>
				</div>
			</div>

			<!-- Card 5: Right Click Protection -->
			<div class="npg-card npg-card-compact">
				<div class="npg-setting-row">
					<div class="npg-setting-label">
						<h4><span class="dashicons dashicons-lock"></span> <?php esc_html_e('Right Click Protection', 'new-photo-gallery'); ?></h4>
						<p><?php esc_html_e('If you want to disable right click and image dragging on your site, we have a dedicated plugin for it. Just install and use it.', 'new-photo-gallery'); ?></p>
					</div>
					<div class="npg-setting-field">
                        <?php
                        $rcb_plugin = 'right-click-disable-or-ban/right-click-disable-or-ban.php';
                        if ( ! function_exists( 'is_plugin_active' ) ) {
                            require_once ABSPATH . 'wp-admin/includes/plugin.php';
                        }
                        $is_rcb_installed = file_exists(WP_PLUGIN_DIR . '/' . $rcb_plugin);
                        $is_rcb_active = $is_rcb_installed && is_plugin_active($rcb_plugin);

                        if ($is_rcb_active) : ?>
                            <div class="rcb-status-container" style="display: flex; flex-direction: column; gap: 10px; align-items: flex-start;">
                                <div style="display: inline-flex; align-items: center; gap: 8px; background: #e0f2fe; color: #0369a1; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                    <span class="dashicons dashicons-shield" style="font-size: 16px; width: 16px; height: 16px; margin: 0;"></span>
                                    <?php esc_html_e('Active & Protecting', 'new-photo-gallery'); ?>
                                </div>
                                <a href="<?php echo esc_url(admin_url('admin.php?page=right-click-disable-or-ban-free')); ?>" class="npg-btn npg-btn-secondary">
                                    <span class="dashicons dashicons-admin-generic"></span> <?php esc_html_e('Configure Protection Settings', 'new-photo-gallery'); ?>
                                </a>
                            </div>
                        <?php elseif ($is_rcb_installed) : ?>
                            <div class="rcb-status-container" style="display: flex; flex-direction: column; gap: 10px; align-items: flex-start;">
                                <div style="display: inline-flex; align-items: center; gap: 8px; background: #ffedd5; color: #c2410c; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                    <span class="dashicons dashicons-warning" style="font-size: 16px; width: 16px; height: 16px; margin: 0;"></span>
                                    <?php esc_html_e('Installed (Inactive)', 'new-photo-gallery'); ?>
                                </div>
                                <a href="<?php echo esc_url(wp_nonce_url(self_admin_url('plugins.php?action=activate&plugin=' . $rcb_plugin), 'activate-plugin_' . $rcb_plugin)); ?>" class="npg-btn npg-btn-primary">
                                    <span class="dashicons dashicons-yes-alt"></span> <?php esc_html_e('Activate Right Click Ban', 'new-photo-gallery'); ?>
                                </a>
                            </div>
                        <?php else : ?>
                            <div class="rcb-status-container" style="display: flex; flex-direction: column; gap: 10px; align-items: flex-start;">
                                <div style="display: inline-flex; align-items: center; gap: 8px; background: #f1f5f9; color: #475569; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                                    <span class="dashicons dashicons-info" style="font-size: 16px; width: 16px; height: 16px; margin: 0;"></span>
                                    <?php esc_html_e('Recommended Plugin', 'new-photo-gallery'); ?>
                                </div>
                                <a href="<?php echo esc_url(wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=right-click-disable-or-ban'), 'install-plugin_right-click-disable-or-ban')); ?>" class="npg-btn npg-btn-primary">
                                    <span class="dashicons dashicons-download"></span> <?php esc_html_e('Install Free Right Click Ban Plugin', 'new-photo-gallery'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
					</div>
				</div>
			</div>
		</div>

		<!-- Tab 3: Lightbox -->
		<div class="npg-tab-content" id="tab-lightbox">
			<div class="npg-card npg-card-compact">
				<!-- Lightbox Script -->
				<div class="npg-setting-row">
					<div class="npg-setting-label">
						<h4><span class="dashicons dashicons-visibility"></span> <?php esc_html_e('Active Lightbox Script', 'new-photo-gallery'); ?></h4>
						<p><?php esc_html_e('Select the script core for the image popup.', 'new-photo-gallery'); ?></p>
					</div>
					<div class="npg-setting-field">
						<select name="light-box" id="light-box" class="npg-select">
							<option value="0" <?php selected($settings['light-box'], 0); ?>><?php esc_html_e('None (Disable Popup)', 'new-photo-gallery'); ?></option>
							<option value="1" <?php selected($settings['light-box'], 1); ?>><?php esc_html_e('lightGallery', 'new-photo-gallery'); ?></option>
						</select>
					</div>
				</div>

				<!-- Lightbox Transition -->
				<div class="npg-setting-row">
					<div class="npg-setting-label">
						<h4><span class="dashicons dashicons-visibility"></span> <?php esc_html_e('Lightbox Transition Style', 'new-photo-gallery'); ?></h4>
						<p><?php esc_html_e('Select transition animation effect when shifting lightbox media.', 'new-photo-gallery'); ?></p>
					</div>
					<div class="npg-setting-field">
						<select name="transition_effects" id="transition_effects" class="npg-select">
							<option value="none" <?php selected($settings['transition_effects'], 'none'); ?>><?php esc_html_e('None', 'new-photo-gallery'); ?></option>
							<option value="lg-slide" <?php selected($settings['transition_effects'], 'lg-slide'); ?>><?php esc_html_e('Slide', 'new-photo-gallery'); ?></option>
							<option value="lg-fade" <?php selected($settings['transition_effects'], 'lg-fade'); ?>><?php esc_html_e('Fade', 'new-photo-gallery'); ?></option>
							<option value="lg-zoom-in" <?php selected($settings['transition_effects'], 'lg-zoom-in'); ?>><?php esc_html_e('Zoom In', 'new-photo-gallery'); ?></option>
							<option value="lg-zoom-in-big" <?php selected($settings['transition_effects'], 'lg-zoom-in-big'); ?>><?php esc_html_e('Zoom In (Big)', 'new-photo-gallery'); ?></option>
						</select>
					</div>
				</div>

				<!-- Loop Images in Lightbox -->
				<div class="npg-setting-row">
					<div class="npg-setting-label">
						<h4><span class="dashicons dashicons-update"></span> <?php esc_html_e('Loop Images in Lightbox', 'new-photo-gallery'); ?></h4>
						<p><?php esc_html_e('Enable loop navigation to return to the first image after clicking next on the last.', 'new-photo-gallery'); ?></p>
					</div>
					<div class="npg-setting-field">
						<div class="npg-segmented-control">
							<input type="radio" id="lb_loop_yes" name="show_lightbox_loop" value="1" <?php checked($settings['show_lightbox_loop'], 1); ?>>
							<label for="lb_loop_yes"><?php esc_html_e('Yes', 'new-photo-gallery'); ?></label>
							
							<input type="radio" id="lb_loop_no" name="show_lightbox_loop" value="0" <?php checked($settings['show_lightbox_loop'], 0); ?>>
							<label for="lb_loop_no"><?php esc_html_e('No', 'new-photo-gallery'); ?></label>
						</div>
					</div>
				</div>

				<!-- Show Lightbox Thumbnails -->
				<div class="npg-setting-row">
					<div class="npg-setting-label">
						<h4><span class="dashicons dashicons-format-image"></span> <?php esc_html_e('Show Lightbox Thumbnails', 'new-photo-gallery'); ?></h4>
						<p><?php esc_html_e('Enable thumbnail strip at the bottom of the lightbox popup.', 'new-photo-gallery'); ?></p>
					</div>
					<div class="npg-setting-field">
						<div class="npg-segmented-control">
							<input type="radio" id="lb_thumbnails_yes" name="lightbox_thumbnails" value="1" <?php checked($settings['lightbox_thumbnails'], 1); ?>>
							<label for="lb_thumbnails_yes"><?php esc_html_e('Yes', 'new-photo-gallery'); ?></label>
							
							<input type="radio" id="lb_thumbnails_no" name="lightbox_thumbnails" value="0" <?php checked($settings['lightbox_thumbnails'], 0); ?>>
							<label for="lb_thumbnails_no"><?php esc_html_e('No', 'new-photo-gallery'); ?></label>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Tab 4: Upgrade to Pro -->
		<div class="npg-tab-content" id="tab-upgrade-pro">
			<div class="npg-pro-upgrade-container">
				<!-- Header section -->
				<div class="npg-pro-header">
					<div class="npg-pro-badge"><?php esc_html_e('PREMIUM FEATURES', 'new-photo-gallery'); ?></div>
					<h2><?php esc_html_e('Experience the Best with Video Gallery Premium', 'new-photo-gallery'); ?></h2>
					<p style="font-weight: 600; margin-bottom: 10px; color: var(--npg-primary);"><?php esc_html_e('Its premium version is Video Gallery Premium.', 'new-photo-gallery'); ?></p>
					<p><?php esc_html_e('Take your video and image galleries to the next level with native API integrations, advanced pagination, insights tracking, and priority support.', 'new-photo-gallery'); ?></p>
					
					<!-- Top Buy & Demo Buttons -->
					<div class="npg-pro-top-cta" style="margin-top: 25px; display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
						<a href="https://awplife.com/wordpress-plugins/video-gallery-wordpress-plugin/" target="_blank" class="npg-btn npg-btn-premium lg">
							<span class="dashicons dashicons-cart"></span> <?php esc_html_e('Get Video Gallery Premium Now', 'new-photo-gallery'); ?>
						</a>
						<a href="https://awplife.com/demo/video-gallery-premium/" target="_blank" class="npg-btn npg-btn-secondary lg">
							<span class="dashicons dashicons-welcome-view-site"></span> <?php esc_html_e('Check Live Demo', 'new-photo-gallery'); ?>
						</a>
					</div>
				</div>

				<!-- Feature Grid -->
				<div class="npg-pro-grid">
					<div class="npg-pro-feature-card">
						<div class="npg-pro-icon"><span class="dashicons dashicons-cloud"></span></div>
						<h3><?php esc_html_e('YouTube, Vimeo, Twitch, TikTok, Dailymotion & Wistia APIs', 'new-photo-gallery'); ?></h3>
						<p><?php esc_html_e('Fetch playlists, channels, shows, and portfolios dynamically using secure developer keys with high-performance transient caching.', 'new-photo-gallery'); ?></p>
					</div>
					<div class="npg-pro-feature-card">
						<div class="npg-pro-icon"><span class="dashicons dashicons-clock"></span></div>
						<h3><?php esc_html_e('Server-Side AJAX Load More', 'new-photo-gallery'); ?></h3>
						<p><?php esc_html_e('Paginate extensive media collections dynamically using server-side AJAX requests with spinning loader indicators to optimize loading speeds.', 'new-photo-gallery'); ?></p>
					</div>
					<div class="npg-pro-feature-card">
						<div class="npg-pro-icon"><span class="dashicons dashicons-images-alt2"></span></div>
						<h3><?php esc_html_e('Masonry & Pinterest-Style Layouts', 'new-photo-gallery'); ?></h3>
						<p><?php esc_html_e('Unlock advanced Pinterest-style Masonry configurations, Circle grids, up to 12 column widths, and pixel-precise spacing.', 'new-photo-gallery'); ?></p>
					</div>
					<div class="npg-pro-feature-card">
						<div class="npg-pro-icon"><span class="dashicons dashicons-desktop"></span></div>
						<h3><?php esc_html_e('Custom Redirection Link Targets', 'new-photo-gallery'); ?></h3>
						<p><?php esc_html_e('Bind custom web links or specific page targets directly to individual gallery cards, redirecting visitors to conversion pages.', 'new-photo-gallery'); ?></p>
					</div>
					<div class="npg-pro-feature-card">
						<div class="npg-pro-icon"><span class="dashicons dashicons-update"></span></div>
						<h3><?php esc_html_e('1-Click Photo Gallery Migration', 'new-photo-gallery'); ?></h3>
						<p><?php esc_html_e('Upgrade with peace of mind. A built-in automatic migration tool imports all configurations and slide data into the Premium engine in one click.', 'new-photo-gallery'); ?></p>
					</div>
					<div class="npg-pro-feature-card">
						<div class="npg-pro-icon"><span class="dashicons dashicons-chart-bar"></span></div>
						<h3><?php esc_html_e('Advanced Analytics & Insights', 'new-photo-gallery'); ?></h3>
						<p><?php esc_html_e('Track plays, views, click counts, and engagement metrics directly inside your dashboard with beautiful Chart.js visualizations.', 'new-photo-gallery'); ?></p>
					</div>
				</div>

				<!-- Comparison Table -->
				<div class="npg-pro-comparison">
					<h3><?php esc_html_e('Photo & Video Gallery vs Video Gallery Premium', 'new-photo-gallery'); ?></h3>
					<table class="npg-comparison-table">
						<thead>
							<tr>
								<th><?php esc_html_e('Feature / Option', 'new-photo-gallery'); ?></th>
								<th><?php esc_html_e('Free Version', 'new-photo-gallery'); ?></th>
								<th><?php esc_html_e('Video Gallery Premium', 'new-photo-gallery'); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php esc_html_e('Supported Layouts', 'new-photo-gallery'); ?></td>
								<td><?php esc_html_e('CSS Grid (Multi-column)', 'new-photo-gallery'); ?></td>
								<td><strong><?php esc_html_e('Pinterest Masonry, Circle Grid, and up to 12 Columns', 'new-photo-gallery'); ?></strong></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Automatic 1-Click Migration', 'new-photo-gallery'); ?></td>
								<td><?php esc_html_e('Not Available', 'new-photo-gallery'); ?></td>
								<td><strong><span style="color:#10b981; font-weight: bold;">&#10004;</span> <?php esc_html_e('Yes, imports all CPT configs & legacy [NPG] shortcodes', 'new-photo-gallery'); ?></strong></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Media Sources Supported', 'new-photo-gallery'); ?></td>
								<td><?php esc_html_e('Local Images & Video Uploads', 'new-photo-gallery'); ?></td>
								<td><strong><?php esc_html_e('YouTube, Vimeo, Twitch Helix, TikTok, Dailymotion, Wistia, Meta Reels & Self-hosted', 'new-photo-gallery'); ?></strong></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Automatic API Synchronisation', 'new-photo-gallery'); ?></td>
								<td><?php esc_html_e('No (Manual URL input only)', 'new-photo-gallery'); ?></td>
								<td><strong><span style="color:#10b981; font-weight: bold;">&#10004;</span> <?php esc_html_e('Yes (Playlists, Channels & albums dynamically cached)', 'new-photo-gallery'); ?></strong></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Pagination & Load More', 'new-photo-gallery'); ?></td>
								<td><?php esc_html_e('No (All-in-one page load)', 'new-photo-gallery'); ?></td>
								<td><strong><span style="color:#10b981; font-weight: bold;">&#10004;</span> <?php esc_html_e('Server-Side AJAX Load More & Spinner Options', 'new-photo-gallery'); ?></strong></td>
							</tr>
							<tr>
								<td><?php esc_html_e('CPT Duplicate Gallery', 'new-photo-gallery'); ?></td>
								<td><?php esc_html_e('No', 'new-photo-gallery'); ?></td>
								<td><strong><span style="color:#10b981; font-weight: bold;">&#10004;</span> <?php esc_html_e('Yes, 1-click duplication on the CPT list view', 'new-photo-gallery'); ?></strong></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Custom Click Actions', 'new-photo-gallery'); ?></td>
								<td><?php esc_html_e('Open in Lightbox only', 'new-photo-gallery'); ?></td>
								<td><strong><span style="color:#10b981; font-weight: bold;">&#10004;</span> <?php esc_html_e('Open Lightbox OR redirect individual cards to custom URLs', 'new-photo-gallery'); ?></strong></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Lightbox Integration', 'new-photo-gallery'); ?></td>
								<td><?php esc_html_e('Standard lightGallery with loop toggles', 'new-photo-gallery'); ?></td>
								<td><strong><?php esc_html_e('Premium LightGallery with Fullscreen, Zoom, slideshow transitions, & custom skins', 'new-photo-gallery'); ?></strong></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Insights & Video Analytics', 'new-photo-gallery'); ?></td>
								<td><?php esc_html_e('Not Available', 'new-photo-gallery'); ?></td>
								<td><strong><span style="color:#10b981; font-weight: bold;">&#10004;</span> <?php esc_html_e('Yes, track play statistics, views, and actions in Chart.js', 'new-photo-gallery'); ?></strong></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Bulk Import / Export Utility', 'new-photo-gallery'); ?></td>
								<td><?php esc_html_e('No', 'new-photo-gallery'); ?></td>
								<td><strong><span style="color:#10b981; font-weight: bold;">&#10004;</span> <?php esc_html_e('Yes, export/import settings & attachments via JSON files', 'new-photo-gallery'); ?></strong></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Support', 'new-photo-gallery'); ?></td>
								<td><?php esc_html_e('Community Forums', 'new-photo-gallery'); ?></td>
								<td><strong><?php esc_html_e('Priority 24/7 Developer Support', 'new-photo-gallery'); ?></strong></td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- Footer CTA -->
				<div class="npg-pro-cta" style="display: flex; flex-direction: column; align-items: center; gap: 15px;">
					<div style="display: flex; justify-content: center; gap: 15px; flex-wrap: wrap;">
						<a href="https://awplife.com/wordpress-plugins/video-gallery-wordpress-plugin/" target="_blank" class="npg-btn npg-btn-premium lg">
							<span class="dashicons dashicons-cart"></span> <?php esc_html_e('Get Video Gallery Premium Now', 'new-photo-gallery'); ?>
						</a>
						<a href="https://awplife.com/demo/video-gallery-premium/" target="_blank" class="npg-btn npg-btn-secondary lg">
							<span class="dashicons dashicons-welcome-view-site"></span> <?php esc_html_e('Check Live Demo', 'new-photo-gallery'); ?>
						</a>
					</div>
					<p style="margin: 0;"><?php esc_html_e('One-time payment. Lifetime updates. 100% Satisfaction.', 'new-photo-gallery'); ?></p>
				</div>
			</div>
		</div>

	</div>
</div>