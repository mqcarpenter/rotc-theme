<?php

/*
 Plugin Name: VW Sports Pro Posttype
 Plugin URI: https://www.vwthemes.com/
 Description: Creating new post type for VW Sports Pro Theme.
 Author: VW Themes
 Version: 1.1
 Author URI: https://www.vwthemes.com/
*/
define( 'VW_SPORTS_PRO_POSTTYPE_VERSION', '1.1' );
add_action( 'init', 'vw_sports_pro_posttype_create_post_type' );
add_action( 'init', 'gamescategory');

function vw_sports_pro_posttype_create_post_type() {

  register_post_type( 'games',
    array(
        'labels' => array(
            'name' => __( 'Games','vw-sports-pro-posttype' ),
            'singular_name' => __( 'Games','vw-sports-pro-posttype' )
        ),
        'capability_type' =>  'post',
        'menu_icon'  => 'dashicons-welcome-learn-more',
        'public' => true,
        'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'page-attributes',
        'comments'
        )
    )
  );

  register_post_type( 'team',
    array(
      'labels' => array(
        'name' => __( 'Team','vw-sports-pro-posttype' ),
        'singular_name' => __( 'Team','vw-sports-pro-posttype' )
      ),
        'capability_type' => 'post',
        'menu_icon'  => 'dashicons-businessman',
        'public' => true,
        'supports' => array( 
          'title',
          'editor',
          'thumbnail'
      )
    )
  );
}


//  --------------- Games  Meta ---------------

function gamescategory() {
  // Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name'              => __( 'Categories', 'vw-sports-pro-posttype' ),
    'singular_name'     => __( 'Categories', 'vw-sports-pro-posttype' ),
    'search_items'      => __( 'Search cats', 'vw-sports-pro-posttype' ),
    'all_items'         => __( 'All Categories', 'vw-sports-pro-posttype' ),
    'parent_item'       => __( 'Parent Categories', 'vw-sports-pro-posttype' ),
    'parent_item_colon' => __( 'Parent Categories:', 'vw-sports-pro-posttype' ),
    'edit_item'         => __( 'Edit Categories', 'vw-sports-pro-posttype' ),
    'update_item'       => __( 'Update Categories', 'vw-sports-pro-posttype' ),
    'add_new_item'      => __( 'Add New Categories', 'vw-sports-pro-posttype' ),
    'new_item_name'     => __( 'New Categories Name', 'vw-sports-pro-posttype' ),
    'menu_name'         => __( 'Categories', 'vw-sports-pro-posttype' ),
  );
  $args = array(
    'hierarchical'      => true,
    'labels'            => $labels,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'gamescategory' ),
  );
  register_taxonomy( 'gamescategory', array( 'games' ), $args );
}
function vw_sports_pro_posttype_bn_custom_meta_games() {

    add_meta_box( 'bn_meta', __( 'Games Meta', 'vw-sports-pro-posttype-pro' ), 'vw_sports_pro_posttype_bn_meta_callback_games', 'games', 'normal', 'high' );
}
/* Hook things in for admin*/
if (is_admin()){
  add_action('admin_menu', 'vw_sports_pro_posttype_bn_custom_meta_games');
}

function vw_sports_pro_posttype_bn_meta_callback_games( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );

    $game_date = get_post_meta( $post->ID, 'meta-game-date', true );
    $game_location = get_post_meta( $post->ID, 'meta-game-location', true );
    $game_stadium = get_post_meta( $post->ID, 'meta-game-stadium', true );
    ?>
  <div id="property_stuff">
    <table id="list-table">     
      <tbody id="the-list" data-wp-lists="list:meta">
        <tr id="meta-2">
          <td class="left">
            <?php _e( 'Match Date', 'vw-sports-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="meta-game-date" id="meta-game-date" value="<?php echo esc_html($game_date); ?>" />
          </td>
        </tr>
        <tr id="meta-3">
          <td class="left">
            <?php _e( 'Match Location', 'vw-sports-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="meta-game-location" id="meta-game-location" value="<?php echo esc_html($game_location); ?>" />
          </td>
        </tr>
        <tr id="meta-3">
          <td class="left">
            <?php _e( 'Stadium Name', 'vw-sports-pro-posttype' )?>
          </td>
          <td class="left" >
            <input type="text" name="meta-game-stadium" id="meta-game-stadium" value="<?php echo esc_html($game_stadium); ?>" />
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <?php
}

function vw_sports_pro_posttype_bn_meta_save_games( $post_id ) {

  if (!isset($_POST['bn_nonce']) || !wp_verify_nonce($_POST['bn_nonce'], basename(__FILE__))) {
    return;
  }

  if (!current_user_can('edit_post', $post_id)) {
    return;
  }

  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }

  if( isset( $_POST[ 'meta-game-date' ] ) ) {
    update_post_meta( $post_id, 'meta-game-date', sanitize_text_field($_POST[ 'meta-game-date' ]) );
  } 
  if( isset( $_POST[ 'meta-game-location' ] ) ) {
    update_post_meta( $post_id, 'meta-game-location', sanitize_text_field($_POST[ 'meta-game-location' ]) );
  }
  if( isset( $_POST[ 'meta-game-stadium' ] ) ) {
    update_post_meta( $post_id, 'meta-game-stadium', sanitize_text_field($_POST[ 'meta-game-stadium' ]) );
  }
}
add_action( 'save_post', 'vw_sports_pro_posttype_bn_meta_save_games' );

/* projects shortcode */
function vw_sports_pro_posttype_games_func( $atts ) {
  wp_enqueue_style( 'home-page-style', get_template_directory_uri().'/assets/css/main-css/home-page.css',true, null,'all');
  $projects = '';
  $projects = '<div class="row our-game-high" id="vw-game-highlights">';
  $query = new WP_Query( array( 'post_type' => 'games') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=games');
  while ($new->have_posts()) : $new->the_post();
        $post_id = get_the_ID();
        $project_cat= get_post_meta($post_id,'meta-game-date',true);
        $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $url = $thumb['0'];
        $custom_url ='';
        $custom_url = get_permalink();
        $projects .= '
          <div class="col-lg-4 col-md-6 col-sm-6 vw-game-highlights-info">
            <div class="game-box project-image">
              <img src="'.esc_url($url).'" />
              <div class="game-box-content">
                <h5 class="game-title"><a href="'.esc_url($custom_url).'">'. esc_html(get_the_title()) .'</a></h5>
              </div>
            </div>
          </div>';
    if($k%3 == 0){
      $projects.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $projects = '<h2 class="center">'.esc_html__('Post Not Found','vw_sports_pro_posttype').'</h2>';
  endif;
  return $projects;
}
add_shortcode( 'vw-sports-pro-games', 'vw_sports_pro_posttype_games_func' );

/*-------------------------------------- team-------------------------------------------*/
/* Adds a meta box for Designation */
function vw_sports_pro_posttype_bn_team_meta() {
    add_meta_box( 'vw_sports_pro_posttype_bn_meta', __( 'Enter Details','vw-sports-pro-posttype' ), 'vw_sports_pro_posttype_ex_bn_meta_callback', 'team', 'normal', 'high' );
}
// Hook things in for admin
if (is_admin()){
    add_action('admin_menu', 'vw_sports_pro_posttype_bn_team_meta');
}
/* Adds a meta box for custom post */
function vw_sports_pro_posttype_ex_bn_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'vw_sports_pro_posttype_bn_nonce' );
    $bn_stored_meta = get_post_meta( $post->ID );
    $teacher_email = get_post_meta( $post->ID, 'meta-team-email', true );
    $teacher_phone = get_post_meta( $post->ID, 'meta-team-phone', true );
    $teacher_facebook = get_post_meta( $post->ID, 'meta-tfacebookurl', true );
    $teacher_linkedin = get_post_meta( $post->ID, 'meta-tlinkdenurl', true );
    $teacher_twitter = get_post_meta( $post->ID, 'meta-ttwitterurl', true );
    $teacher_gplus = get_post_meta( $post->ID, 'meta-tgoogleplusurl', true );
    $teacher_desig = get_post_meta( $post->ID, 'meta-designation', true );
    $teacher_instagram = get_post_meta( $post->ID, 'meta-tinstagram', true );
    $teacher_pinterest = get_post_meta( $post->ID, 'meta-pinterest', true );
    ?>
  
    <div id="agent_custom_stuff">
        <table id="list-table">         
            <tbody id="the-list" data-wp-lists="list:meta">
                <tr id="meta-1">
                  <td class="left">
                      <?php _e( 'Email', 'vw-sports-pro-posttype' )?>
                  </td>
                  <td class="left" >
                      <input type="text" name="meta-team-email" id="meta-team-email" value="<?php echo esc_html($teacher_email); ?>" />
                  </td>
                </tr>
                <tr id="meta-1">
                  <td class="left">
                      <?php _e( 'Phone', 'vw-sports-pro-posttype' )?>
                  </td>
                  <td class="left" >
                      <input type="text" name="meta-team-phone" id="meta-team-phone" value="<?php echo esc_html($teacher_phone); ?>" />
                  </td>
                </tr>
                <tr id="meta-3">
                  <td class="left">
                    <?php _e( 'Facebook Url', 'vw-sports-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-tfacebookurl" id="meta-tfacebookurl" value="<?php echo esc_html($teacher_facebook); ?>" />
                  </td>
                </tr>
                <tr id="meta-4">
                  <td class="left">
                    <?php _e( 'Linkedin Url', 'vw-sports-pro-posttype' )?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-tlinkdenurl" id="meta-tlinkdenurl" value="<?php echo esc_html($teacher_linkedin); ?>" />
                  </td>
                </tr>
                <tr id="meta-5">
                  <td class="left">
                    <?php _e( 'Twitter Url', 'vw-sports-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-ttwitterurl" id="meta-ttwitterurl" value="<?php echo esc_html($teacher_twitter); ?>" />
                  </td>
                </tr>
                <tr id="meta-6">
                  <td class="left">
                    <?php _e( 'GooglePlus Url', 'vw-sports-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-tgoogleplusurl" id="meta-tgoogleplusurl" value="<?php echo esc_html($teacher_gplus); ?>" />
                  </td>
                </tr>
                <tr id="meta-7">
                  <td class="left">
                    <?php _e( 'Instagram Url', 'vw-sports-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-tinstagram" id="meta-tinstagram" value="<?php echo esc_html($teacher_instagram); ?>" />
                  </td>
                </tr>
                <tr id="meta-8">
                  <td class="left">
                    <?php _e( 'Pinterest Url', 'vw-sports-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="url" name="meta-pinterest" id="meta-pinterest" value="<?php echo esc_html($teacher_pinterest); ?>" />
                  </td>
                </tr>
                <tr id="meta-9">
                  <td class="left">
                    <?php _e( 'Designation', 'vw-sports-pro-posttype' ); ?>
                  </td>
                  <td class="left" >
                    <input type="text" name="meta-designation" id="meta-designation" value="<?php echo esc_html($teacher_desig); ?>" />
                  </td>
                </tr>

            </tbody>
        </table>
    </div>
    <?php
}
/* Saves the custom Designation meta input */
function vw_sports_pro_posttype_ex_bn_metadesig_save( $post_id ) {

  
    if( isset( $_POST[ 'meta-team-email' ] ) ) {
        update_post_meta( $post_id, 'meta-team-email', esc_html($_POST[ 'meta-team-email' ]) );
    }
    if( isset( $_POST[ 'meta-team-phone' ] ) ) {
        update_post_meta( $post_id, 'meta-team-phone', esc_html($_POST[ 'meta-team-phone' ]) );
    }
    
    // Save facebookurl
    if( isset( $_POST[ 'meta-tfacebookurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tfacebookurl', esc_url($_POST[ 'meta-tfacebookurl' ]) );
    }
    // Save linkdenurl
    if( isset( $_POST[ 'meta-tlinkdenurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tlinkdenurl', esc_url($_POST[ 'meta-tlinkdenurl' ]) );
    }
    if( isset( $_POST[ 'meta-ttwitterurl' ] ) ) {
        update_post_meta( $post_id, 'meta-ttwitterurl', esc_url($_POST[ 'meta-ttwitterurl' ]) );
    }
    // Save googleplusurl
    if( isset( $_POST[ 'meta-tgoogleplusurl' ] ) ) {
        update_post_meta( $post_id, 'meta-tgoogleplusurl', esc_url($_POST[ 'meta-tgoogleplusurl' ]) );
    }

    // Save Instagram
    if( isset( $_POST[ 'meta-tinstagram' ] ) ) {
        update_post_meta( $post_id, 'meta-tinstagram', esc_url($_POST[ 'meta-tinstagram' ]) );
    }

    // Save Pinterest
    if( isset( $_POST[ 'meta-pinterest' ] ) ) {
        update_post_meta( $post_id, 'meta-pinterest', esc_url($_POST[ 'meta-pinterest' ]) );
    }
    // Save designation
    if( isset( $_POST[ 'meta-designation' ] ) ) {
        update_post_meta( $post_id, 'meta-designation', esc_html($_POST[ 'meta-designation' ]) );
    }
}
add_action( 'save_post', 'vw_sports_pro_posttype_ex_bn_metadesig_save' );

add_action( 'save_post', 'bn_meta_save' );
/* Saves the custom meta input */
function bn_meta_save( $post_id ) {
  if( isset( $_POST[ 'vw_sports_pro_posttype_team_featured' ] )) {
      update_post_meta( $post_id, 'vw_sports_pro_posttype_team_featured', esc_attr(1));
  }else{
    update_post_meta( $post_id, 'vw_sports_pro_posttype_team_featured', esc_attr(0));
  }
}
/*------------------------------------- team Shorthcode -------------------------------------*/
function vw_sports_pro_posttype_team_func( $atts ) {
  wp_enqueue_style( 'home-page-style', get_template_directory_uri().'/assets/css/main-css/home-page.css',true, null,'all');
  $team = '';
  $team = '<div class="row vw-our-team" id="vw-our-team">';
  $query = new WP_Query( array( 'post_type' => 'team') );

    if ( $query->have_posts() ) :

  $k=1;
  $new = new WP_Query('post_type=team');
  while ($new->have_posts()) : $new->the_post();
        $post_id = get_the_ID();
         $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'large' );
        if(has_post_thumbnail()) { $thumb_url = $thumb['0']; }
        $url = $thumb['0'];
        $custom_url ='';
        $team_desig= get_post_meta($post_id,'meta-designation',true);
        $facebookurl= get_post_meta($post_id,'meta-tfacebookurl',true);
        $linkedin=get_post_meta($post_id,'meta-tlinkdenurl',true);
        $twitter=get_post_meta($post_id,'meta-ttwitterurl',true);
        $instagram=get_post_meta($post_id,'meta-tinstagram',true);
        $googleplusurl= get_post_meta($post_id,'meta-tgoogleplusurl',true);
        $pinterest= get_post_meta($post_id,'meta-pinterest',true);
        $custom_url = get_permalink();
        $team .= '
          <div class="col-lg-4 col-md-4">
            <div class="vw-our-team-content">
              <img src="'.esc_url($url).'">
              <div class="vw-social-profiles vw-hvr-shutter-in-vertical">';
                  if($facebookurl != ''){
                    $team .= '<a class="" href="'.esc_url($facebookurl).'" target="_blank"><i class="fab fa-facebook-f"></i></a>';
                  } if($twitter != ''){
                    $team .= '<a class="" href="'.esc_url($twitter).'" target="_blank"><i class="fab fa-twitter"></i></a>';
                  } if($instagram != ''){
                    $team .= '<a class="" href="'.esc_url($instagram).'" target="_blank"><i class="fab fa-instagram align-middle" aria-hidden="true"></i></a>';
                  } if($linkedin != ''){
                    $team .= '<a class="" href="'.esc_url($linkedin).'" target="_blank"><i class="fab fa-linkedin-in"></i></a>';
                  }if($googleplusurl != ''){
                    $team .= '<a class="" href="'.esc_url($googleplusurl).'" target="_blank"><i class="fab fa-google-plus-g"></i></a>';
                  }if($pinterest != ''){
                    $team .= '<a class="" href="'.esc_url($pinterest).'" target="_blank"><i class="fab fa-pinterest-p align-middle " aria-hidden="true"></i></a>';
                  }
                  $team .= '
              </div>
              <a href="'.esc_url($custom_url).'" class="vw-team-link">
                '. esc_html(get_the_title()) .'
              </a>
              <span class="vw-our-team-desig">  
                '.$team_desig.'
              </span>
            </div>
          </div>';
    if($k%3 == 0){
      $team.= '<div class="clearfix"></div>';
    }
      $k++;
  endwhile;
  else :
    $team = '<h2 class="center">'.esc_html__('Post Not Found','vw_sports_pro_posttype').'</h2>';
  endif;
  return $team;
}

add_shortcode( 'vw-sports-pro-team', 'vw_sports_pro_posttype_team_func' );
