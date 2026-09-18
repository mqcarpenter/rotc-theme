<?php
if (! defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Register all NPG frontend assets early so they're available in any context.
 */
add_action('wp_enqueue_scripts', 'npg_elementor_register_assets', 5);
function npg_elementor_register_assets() {
    wp_register_style('npg-frontend-css', NPG_PLUGIN_URL . 'assets/css/npg-frontend.css', array(), NPG_VER);
    wp_register_style('lg-hover-css', NPG_PLUGIN_URL . 'assets/css/hover.css', array(), NPG_VER);
    wp_register_style('awplife-npg-light-gallery-css', NPG_PLUGIN_URL . 'include/lightbox/light-gallery/css/lightgallery.css', array(), NPG_VER);
    wp_register_style('awplife-npg-lg-transitions-css', NPG_PLUGIN_URL . 'include/lightbox/light-gallery/css/lg-transitions.css', array(), NPG_VER);
    wp_register_script('awplife-npg-isotope-js', NPG_PLUGIN_URL . 'assets/js/isotope.pkgd.js', array(), NPG_VER, true);
    wp_register_script('awplife-npg-light-gallery-js', NPG_PLUGIN_URL . 'include/lightbox/light-gallery/js/lightgallery.js', array('jquery'), NPG_VER, true);
    wp_register_script('awplife-npg-all-plugins-js', NPG_PLUGIN_URL . 'include/lightbox/light-gallery/js/lightgallery-all.js', array('jquery'), NPG_VER, true);
}

/**
 * Force-enqueue gallery assets on Elementor preview pages.
 */
add_action('elementor/preview/enqueue_styles', 'npg_elementor_enqueue_preview_assets');
function npg_elementor_enqueue_preview_assets() {
    wp_enqueue_style('npg-frontend-css');
    wp_enqueue_style('lg-hover-css');
    wp_enqueue_style('awplife-npg-light-gallery-css');
    wp_enqueue_style('awplife-npg-lg-transitions-css');
    wp_enqueue_script('jquery');
    wp_enqueue_script('imagesloaded');
    wp_enqueue_script('awplife-npg-isotope-js');
    wp_enqueue_script('awplife-npg-light-gallery-js');
    wp_enqueue_script('awplife-npg-all-plugins-js');
}

/**
 * Register Elementor Widget for Photo & Video Gallery
 */
add_action('elementor/widgets/register', 'npg_photo_gallery_register_elementor_widget');
function npg_photo_gallery_register_elementor_widget($widgets_manager) {
    if (class_exists('\Elementor\Widget_Base')) {

        class Elementor_Photo_Gallery_Widget extends \Elementor\Widget_Base {

            public function get_name() {
                return 'photo_gallery_widget';
            }

            public function get_title() {
                return esc_html__('Photo & Video Gallery', 'new-photo-gallery');
            }

            public function get_icon() {
                return 'eicon-gallery-grid';
            }

            public function get_categories() {
                return array('general');
            }

            public function get_keywords() {
                return array('photo', 'gallery', 'video', 'grid', 'npg');
            }

            public function get_style_depends() {
                return array('npg-frontend-css', 'lg-hover-css', 'awplife-npg-light-gallery-css', 'awplife-npg-lg-transitions-css');
            }

            public function get_script_depends() {
                return array('imagesloaded', 'awplife-npg-isotope-js', 'awplife-npg-light-gallery-js', 'awplife-npg-all-plugins-js');
            }

            protected function register_controls() {
                $this->start_controls_section(
                    'section_content',
                    array(
                        'label' => esc_html__('Gallery Source Settings', 'new-photo-gallery'),
                        'tab'   => \Elementor\Controls_Manager::TAB_CONTENT,
                    )
                );

                $all_galleries = get_posts(array(
                    'post_type'      => 'npg_gallery',
                    'posts_per_page' => -1,
                    'post_status'    => 'any',
                    'orderby'        => 'title',
                    'order'          => 'ASC',
                ));

                $gallery_options = array('' => esc_html__('-- Select Gallery --', 'new-photo-gallery'));
                if (!empty($all_galleries)) {
                    foreach ($all_galleries as $g) {
                        $gallery_options[$g->ID] = $g->post_title ? $g->post_title . ' (ID: ' . $g->ID . ')' : esc_html__('(no title)', 'new-photo-gallery') . ' (ID: ' . $g->ID . ')';
                    }
                }

                $this->add_control(
                    'gallery_id',
                    array(
                        'label'     => esc_html__('Select Gallery', 'new-photo-gallery'),
                        'type'      => \Elementor\Controls_Manager::SELECT,
                        'options'   => $gallery_options,
                        'default'   => '',
                    )
                );

                $this->end_controls_section();
            }

            protected function render() {
                $settings = $this->get_settings_for_display();
                
                if (empty($settings['gallery_id'])) {
                    echo '<div style="padding:20px; border:1px dashed #ccc; text-align:center;">' . esc_html__('Please select a Photo & Video Gallery.', 'new-photo-gallery') . '</div>';
                    return;
                }

                $gallery_id = (int)$settings['gallery_id'];

                // Detect Elementor editor/preview context
                $is_elementor_editor = false;
                if (class_exists('\Elementor\Plugin')) {
                    if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
                        $is_elementor_editor = true;
                    }
                }

                if ($is_elementor_editor) {
                    // In Elementor editor: inject CSS <link> tags directly into the HTML output
                    // because wp_enqueue_style() calls are ignored during AJAX widget re-renders.
                    $css_files = array(
                        NPG_PLUGIN_URL . 'assets/css/npg-frontend.css',
                        NPG_PLUGIN_URL . 'assets/css/hover.css',
                        NPG_PLUGIN_URL . 'include/lightbox/light-gallery/css/lightgallery.css',
                    );
                    $ver = NPG_VER;
                    foreach ($css_files as $css_url) {
                        $css_url_versioned = esc_url($css_url) . '?ver=' . esc_attr($ver);
                        echo '<link rel="stylesheet" href="' . $css_url_versioned . '" type="text/css" media="all" />' . "\n";
                    }
                }

                // Render the gallery shortcode
                echo do_shortcode('[NPG id=' . $gallery_id . ']');

                if ($is_elementor_editor) {
                    // In Elementor editor: inject inline JS to initialize Isotope and show gallery.
                    // Inline <script> in shortcode output doesn't execute during AJAX re-render,
                    // so we use a self-executing script that runs immediately.
                    ?>
                    <script type="text/javascript">
                    (function() {
                        function npgInitGallery() {
                            if (typeof jQuery === 'undefined') return;
                            var $ = jQuery;
                            var $grid = $('.all-images-<?php echo esc_js($gallery_id); ?>');
                            if (!$grid.length) return;

                            // Force visibility immediately
                            $grid.css('opacity', '1').addClass('npg-loaded');
                            $grid.find('.single-image').css({
                                'opacity': '1',
                                'animation': 'none'
                            });

                            // Initialize Isotope if available
                            if (typeof $.fn.isotope !== 'undefined') {
                                $grid.imagesLoaded(function() {
                                    $grid.isotope({
                                        itemSelector: '.single-image',
                                        layoutMode: 'masonry',
                                        masonry: {
                                            columnWidth: '.grid-sizer',
                                            percentPosition: true
                                        },
                                        transitionDuration: '0.6s'
                                    });
                                    $grid.isotope('layout');
                                });
                            }
                        }

                        // Try immediately and also after a short delay
                        npgInitGallery();
                        setTimeout(npgInitGallery, 500);
                        setTimeout(npgInitGallery, 1500);
                    })();
                    </script>
                    <?php
                }
            }
        }

        $widgets_manager->register(new \Elementor_Photo_Gallery_Widget());
    }
}
