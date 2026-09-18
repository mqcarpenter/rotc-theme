jQuery(function(jQuery) {
	var file_frame,
	awplife_photo_gallery = {
		ul: '',
		init: function() {
			this.ul = jQuery('.photo-box');
			this.ul.sortable({
				items: '.npg-image-slide',
				handle: '.npg-move-handle',
				placeholder: 'npg-sortable-placeholder',
				forcePlaceholderSize: true,
				tolerance: 'pointer',
				opacity: 0.8,
				revert: false,
				scroll: true
			});
			
			/**
			 * Add Slide Callback Function
			 */
			jQuery(document).on('click', '#add-new-photos', function(event) {
				var lg_add_images_nonce = jQuery("#lg_add_images_nonce").val();
				event.preventDefault();
				if (file_frame) {
					file_frame.open();
					return;
				}
				file_frame = wp.media.frames.file_frame = wp.media({
					multiple: true
				});

				file_frame.on('select', function() {
					var images = file_frame.state().get('selection').toJSON(),
							length = images.length;
					for (var i = 0; i < length; i++) {
						awplife_photo_gallery.get_thumbnail(images[i]['id'], '', lg_add_images_nonce);
					}
				});
				file_frame.open();
			});
			
			/**
			 * Delete Slide Callback Function
			 */
			this.ul.on('click', '.remove-slide', function() {
				if (confirm('Are you sure you want to delete this photo?')) {
					jQuery(this).closest('.npg-image-slide').fadeOut(300, function() {
						jQuery(this).remove();
					});
				}
				return false;
			});
			
			/**
			 * Delete All Slides Callback Function
			 */
			jQuery(document).on('click', '#remove-all-photos', function() {
				if (confirm('Are you sure you want to delete all photos?')) {
					awplife_photo_gallery.ul.empty();
				}
				return false;
			});
		   
		},
		get_thumbnail: function(id, cb, lg_add_images_nonce) {
			cb = cb || function() {};
			var data = {
				action: 'photo_gallery_js',
				slideId: id,
				lg_add_images_nonce: lg_add_images_nonce,
			};
			jQuery.post(ajaxurl, data, function(response) {
				awplife_photo_gallery.ul.append(response);
				cb();
			});
		}
	};
	awplife_photo_gallery.init();

	// Tab Navigation
	jQuery(document).on('click', ".npg-tabs-nav .nav-item", function(e) {
		e.preventDefault();
		jQuery(".npg-tabs-nav .nav-item").removeClass("active");
		jQuery(this).addClass("active");
		var target = jQuery(this).data("target");
		jQuery(".npg-tab-content").removeClass("active");
		jQuery("#" + target).addClass("active");
	});

	// Show/Hide Video URL Wrapper on type select change
	jQuery(document).on('change', '.photo-type', function() {
		var $card = jQuery(this).closest('.npg-image-slide');
		if (jQuery(this).val() === 'video') {
			$card.find('.photo-link-wrapper').slideDown();
		} else {
			$card.find('.photo-link-wrapper').slideUp();
			// Revert the poster if changing back to image
			$card.find('.npg-revert-poster-btn').trigger('click');
		}
	});

	// Fetch Poster Button Action
	jQuery(document).on('click', '.npg-fetch-poster-btn', function(e) {
		e.preventDefault();
		var $btn = jQuery(this);
		var $wrapper = $btn.closest('.photo-link-wrapper');
		var $slide = $btn.closest('.npg-image-slide');
		var videoUrl = $wrapper.find('.photo-link').val().trim();

		if (!videoUrl) {
			alert('Please enter a Video URL first.');
			return;
		}

		$btn.prop('disabled', true).text('Fetching...');

		jQuery.post(ajaxurl, {
			action: 'npg_fetch_video_poster',
			video_url: videoUrl
		}, function(response) {
			if (response.success) {
				var posterUrl = response.data.poster_url;
				$wrapper.find('.photo-poster').val(posterUrl);
				$slide.find('img.photo').attr('src', posterUrl);
				$wrapper.find('.npg-revert-poster-btn').show();
			} else {
				alert(response.data.message || 'Error fetching poster.');
			}
			$btn.prop('disabled', false).html('<span class="dashicons dashicons-download" style="font-size: 14px; width: 14px; height: 14px; line-height: 14px; margin-top: 5px;"></span> Fetch Poster');
		}).fail(function() {
			alert('Error contacting server.');
			$btn.prop('disabled', false).html('<span class="dashicons dashicons-download" style="font-size: 14px; width: 14px; height: 14px; line-height: 14px; margin-top: 5px;"></span> Fetch Poster');
		});
	});

	// Revert Poster Button Action
	jQuery(document).on('click', '.npg-revert-poster-btn', function(e) {
		e.preventDefault();
		var $btn = jQuery(this);
		var $wrapper = $btn.closest('.photo-link-wrapper');
		var $slide = $btn.closest('.npg-image-slide');
		var $img = $slide.find('img.photo');
		var defaultSrc = $img.data('default-src');

		$wrapper.find('.photo-poster').val('');
		if (defaultSrc) {
			$img.attr('src', defaultSrc);
		}
		$btn.hide();
	});

	// Show/Hide Grayscale Percentage Option on radio choice change
	jQuery(document).on('change', 'input[name="image_grayscale"]', function() {
		if (jQuery(this).val() === '1') {
			jQuery('.grayscale_pct_wrapper').slideDown();
		} else {
			jQuery('.grayscale_pct_wrapper').slideUp();
		}
	});

	// Sorting Helper (Global scope as called by onclick)
	window.NPGISortSlides = function(order) {
		if (order == "ASC") {
			jQuery(".photo-box li").sort(sort_li_asc).appendTo('.photo-box');
		}
		if (order == "DESC") {
			jQuery(".photo-box li").sort(sort_li_desc).appendTo('.photo-box');
		}
		function sort_li_asc(a, b) {
			return (jQuery(b).data('position')) > (jQuery(a).data('position')) ? 1 : -1;
		}
		function sort_li_desc(a, b) {
			return (jQuery(b).data('position')) < (jQuery(a).data('position')) ? 1 : -1;
		}
	}
});