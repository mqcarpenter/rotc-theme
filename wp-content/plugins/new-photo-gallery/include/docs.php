<?php
if (!defined('ABSPATH')) {
	exit;
}
?>
<div class="wrap npg-docs-wrap">

    <div class="npg-docs-sidebar">
        <div>
            <div class="npg-sidebar-logo">
                <span class="dashicons dashicons-images-alt2"></span>
                <span><?php esc_html_e('Mastery Guide', 'new-photo-gallery'); ?></span>
            </div>
            <ul class="npg-toc">
                <li><a href="#tab-1" class="active"><span class="dashicons dashicons-format-image"></span> <?php esc_html_e('1. Add Photos & Videos', 'new-photo-gallery'); ?></a></li>
                <li><a href="#tab-2"><span class="dashicons dashicons-layout"></span> <?php esc_html_e('2. Layout & Design', 'new-photo-gallery'); ?></a></li>
                <li><a href="#tab-3"><span class="dashicons dashicons-welcome-view-site"></span> <?php esc_html_e('3. Lightbox Settings', 'new-photo-gallery'); ?></a></li>
                <li><a href="#section-deployment"><span class="dashicons dashicons-shortcode"></span> <?php esc_html_e('Shortcode Deployment', 'new-photo-gallery'); ?></a></li>
            </ul>
        </div>

        <div class="npg-sidebar-actions">
            <a href="https://awplife.com/wordpress-plugins/video-gallery-wordpress-plugin/" target="_blank" class="npg-action-btn npg-btn-premium">
                <span class="dashicons dashicons-cart"></span> <?php esc_html_e('Buy Pro Version', 'new-photo-gallery'); ?>
            </a>
            <a href="https://awplife.com/demo/video-gallery-premium/" target="_blank" class="npg-action-btn npg-btn-demo">
                <span class="dashicons dashicons-welcome-view-site"></span> <?php esc_html_e('Live Demo', 'new-photo-gallery'); ?>
            </a>
            <a href="https://wordpress.org/support/plugin/new-photo-gallery/reviews/" target="_blank" class="npg-action-btn npg-btn-rating">
                <span class="dashicons dashicons-star-filled"></span> <?php esc_html_e('Rate 5 Stars', 'new-photo-gallery'); ?>
            </a>
        </div>
    </div>

    <div class="npg-docs-content">
        <header class="npg-main-header">
            <h1><?php esc_html_e('Photo & Video Gallery: Complete Encyclopedia', 'new-photo-gallery'); ?> <span class="npg-version-badge">v<?php echo esc_html(NPG_VER); ?></span></h1>
            <p><?php esc_html_e('A step-by-step tutorial aligned with your gallery settings workflow.', 'new-photo-gallery'); ?></p>
        </header>

        <!-- Tab 1: Add Photos -->
        <section id="tab-1" class="npg-info-section">
            <h2><span class="dashicons dashicons-format-image"></span> <?php esc_html_e('Step 1: Content Management (Add Photos)', 'new-photo-gallery'); ?></h2>
            <div class="npg-tutorial-card">
                <h3><?php esc_html_e('Image Assets & Metadata', 'new-photo-gallery'); ?></h3>
                <p><?php esc_html_e('This tab controls the source media files and their individual properties.', 'new-photo-gallery'); ?></p>
                <table class="npg-settings-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Setting / Tool', 'new-photo-gallery'); ?></th>
                            <th><?php esc_html_e('Description & Impact', 'new-photo-gallery'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong><?php esc_html_e('Drag & Drop Sort', 'new-photo-gallery'); ?></strong></td>
                            <td><?php esc_html_e('Drag individual cards to manually specify the grid sequence.', 'new-photo-gallery'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php esc_html_e('Sort ASC / DESC', 'new-photo-gallery'); ?></strong></td>
                            <td><?php esc_html_e('Bulk sorting helper buttons to dynamically reorganize your entire media stack.', 'new-photo-gallery'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php esc_html_e('Slide Type (Image/Video)', 'new-photo-gallery'); ?></strong></td>
                            <td><?php esc_html_e('Choose whether the thumbnail acts as an image or a YouTube/Vimeo video player link.', 'new-photo-gallery'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php esc_html_e('Fetch Poster', 'new-photo-gallery'); ?></strong></td>
                            <td><?php esc_html_e('Fetch the high-resolution video thumbnail poster image automatically from YouTube or Vimeo.', 'new-photo-gallery'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php esc_html_e('Revert Poster', 'new-photo-gallery'); ?></strong></td>
                            <td><?php esc_html_e('Discard the custom fetched poster and restore the original uploaded media library image.', 'new-photo-gallery'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Tab 2: Layout & Design -->
        <section id="tab-2" class="npg-info-section">
            <h2><span class="dashicons dashicons-layout"></span> <?php esc_html_e('Step 2: Architecture & Styling (Layout & Design)', 'new-photo-gallery'); ?></h2>
            <div class="npg-tutorial-card">
                <h3><?php esc_html_e('Layout Configuration', 'new-photo-gallery'); ?></h3>
                <table class="npg-settings-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Setting', 'new-photo-gallery'); ?></th>
                            <th><?php esc_html_e('How it Works', 'new-photo-gallery'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong><?php esc_html_e('Thumbnail Resolution', 'new-photo-gallery'); ?></strong></td>
                            <td><?php esc_html_e('Select specific WordPress image sizes. Use "Medium" for speed or "Full" for ultra-high pixel density.', 'new-photo-gallery'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php esc_html_e('Thumbnail Spacing (Gaps)', 'new-photo-gallery'); ?></strong></td>
                            <td><?php esc_html_e('Enable or disable borders and gaps between thumbnails. Disabling spacing drops card border radii to 0px automatically.', 'new-photo-gallery'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php esc_html_e('Thumbnail Title Overlay', 'new-photo-gallery'); ?></strong></td>
                            <td><?php esc_html_e('Toggle titles overlaid on thumbnails. Upgraded to match premium grid styles with smooth gradient masks.', 'new-photo-gallery'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php esc_html_e('Responsive Columns', 'new-photo-gallery'); ?></strong></td>
                            <td><?php esc_html_e('Configure unique columns (1-6) for Large Desktops, Desktops, Tablets, and Mobile phones.', 'new-photo-gallery'); ?></td>
                        </tr>
                    </tbody>
                </table>

                <h3 style="margin-top:40px;"><?php esc_html_e('Advanced Visual Effects', 'new-photo-gallery'); ?></h3>
                <ul class="npg-bullet-list">
                    <li><strong><?php esc_html_e('Grayscale Amount (%):', 'new-photo-gallery'); ?></strong> <?php esc_html_e('Enforce custom B&W percentage filters (0% to 100%) on rest, restoring full colors smoothly on hover.', 'new-photo-gallery'); ?></li>
                    <li><strong><?php esc_html_e('Hover Interactions:', 'new-photo-gallery'); ?></strong> <?php esc_html_e('Choose from advanced thumbnail hover animations like Grow Shadow, Float Shadow, and Glow.', 'new-photo-gallery'); ?></li>
                </ul>
            </div>
        </section>

        <!-- Tab 3: Lightbox -->
        <section id="tab-3" class="npg-info-section">
            <h2><span class="dashicons dashicons-welcome-view-site"></span> <?php esc_html_e('Step 3: Interaction Logic (Lightbox)', 'new-photo-gallery'); ?></h2>
            <div class="npg-tutorial-card">
                <table class="npg-settings-table">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Setting', 'new-photo-gallery'); ?></th>
                            <th><?php esc_html_e('Description', 'new-photo-gallery'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong><?php esc_html_e('Active Lightbox Script', 'new-photo-gallery'); ?></strong></td>
                            <td><?php esc_html_e('Enable/disable the frontend popup lightbox viewer.', 'new-photo-gallery'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php esc_html_e('Lightbox Loop', 'new-photo-gallery'); ?></strong></td>
                            <td><?php esc_html_e('Toggle loop navigation when cycling to the end of the media slides.', 'new-photo-gallery'); ?></td>
                        </tr>
                        <tr>
                            <td><strong><?php esc_html_e('Show Lightbox Thumbnails', 'new-photo-gallery'); ?></strong></td>
                            <td><?php esc_html_e('Toggle the bottom thumbnail strip in the lightbox viewport. Loads lightweight medium resolutions to optimize speeds.', 'new-photo-gallery'); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Final Step: Deployment -->
        <section id="section-deployment" class="npg-info-section">
            <h2><span class="dashicons dashicons-shortcode"></span> <?php esc_html_e('Deployment & Shortcuts', 'new-photo-gallery'); ?></h2>
            <div class="npg-tutorial-card">
                <p><?php esc_html_e('Once configured, copy the shortcode from the sidebar metabox and paste it into any page:', 'new-photo-gallery'); ?></p>
                <div class="npg-code-sample">
                    <code>[NPG id=<?php esc_html_e('XXXX', 'new-photo-gallery'); ?>]</code>
                </div>
            </div>
        </section>

        <footer class="npg-content-footer">
            <p><?php esc_html_e('Documentation synchronized with Photo & Video Gallery Version', 'new-photo-gallery'); ?> <?php echo esc_html(NPG_VER); ?></p>
        </footer>
    </div>
</div>

<script>
jQuery(document).ready(function($) {
    // Toc Link Active Toggling
    var sections = $('.npg-info-section');
    var navLinks = $('.npg-toc a');
    
    $('.npg-docs-content').on('scroll', function() {
        var currentScroll = $(this).scrollTop();
        
        sections.each(function() {
            var top = $(this).position().top - 50;
            var bottom = top + $(this).outerHeight();
            
            if (currentScroll >= top && currentScroll <= bottom) {
                navLinks.removeClass('active');
                $('.npg-toc a[href="#' + $(this).attr('id') + '"]').addClass('active');
            }
        });
    });

    // Smooth Scroll navigation
    $('.npg-toc a').on('click', function(e) {
        e.preventDefault();
        navLinks.removeClass('active');
        $(this).addClass('active');
        
        var target = $(this).attr('href');
        var $content = $('.npg-docs-content');
        var targetPosition = $(target).position().top + $content.scrollTop();
        
        $content.animate({
            scrollTop: targetPosition
        }, 300);
    });
});
</script>
