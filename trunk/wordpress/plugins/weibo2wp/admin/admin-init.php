<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Setup the Admin menu in WordPress
 *
 * @access public
 * @return void
 */
function weibo2wp_admin_menu() {
    global $menu;

    $main_page = add_menu_page( __( 'Weibo2wp', 'weibo2wp' ), __( 'Weibo2wp', 'weibo2wp' ), 'manage_options', 'weibo2wp' , 'weibo2wp_weibo_page', null, '101' );
	
	$settings_page = add_submenu_page( 'weibo2wp', __( 'Weibo2wp Settings', 'weibo2wp' ),  __( 'Settings', 'weibo2wp' ) , 'manage_options', 'weibo2wp_settings', 'weibo2wp_settings_page');
	
	$print_css_on = array( 'toplevel_page_weibo2wp', 'edit.php', 'post.php' );

    foreach ( $print_css_on as $page )
    	add_action( 'admin_print_styles-'. $page, 'weibo2wp_admin_css' );
}

add_action('admin_menu', 'weibo2wp_admin_menu', 9);

/**
 * Include and display the settings page.
 *
 * @access public
 * @return void
 */
function weibo2wp_weibo_page() {
	include_once( 'pages/admin-weibo-page.php' );
	weibo2wp_settings();
}

function weibo2wp_settings_page() {
	include_once( 'pages/admin-settings-page.php' );
	weibo2wp_settings();
}

function weibo2wp_admin_css() {
	global $weibo2wp;

	wp_enqueue_style( 'weibo2wp_admin_styles', $weibo2wp->plugin_url() . '/assets/css/admin.css' );
}


/**
 * Include some admin files conditonally.
 *
 * @access public
 * @return void
 */
function weibo2wp_admin_init() {
	global $pagenow;
	
	if ( $pagenow == 'post.php' || $pagenow == 'edit.php' ) {

		include_once( 'post-types/writepanel-weibo-data.php' );

	}
}

add_action('admin_init', 'weibo2wp_admin_init');

function weibo2wp_admin_scripts() {
	global $weibo2wp;

	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-effects-core');
	wp_enqueue_script('jquery-effects-highlight');
	wp_enqueue_script('jquery-effects-fade');
	
	wp_enqueue_script( 'weibo2wp_writepanel', $weibo2wp->plugin_url() . '/assets/js/write-panels.js', array('jquery'), $weibo2wp->version );
	
	$weibo2wp_witepanel_params = array(
		'remove_button'			=> __( 'Remove', 'weibo2wp' ),
		'image_url' 			=> __( 'Image URL', 'weibo2wp' ),
	 );

	wp_localize_script( 'weibo2wp_writepanel', 'weibo2wp_writepanel_params', $weibo2wp_witepanel_params );
}

add_action( 'admin_enqueue_scripts', 'weibo2wp_admin_scripts' );


function weibo2wp_meta_boxes_save( $post_id, $post ) {
	if ( empty( $post_id ) || empty( $post ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( is_int( wp_is_post_revision( $post ) ) ) return;
	if ( is_int( wp_is_post_autosave( $post ) ) ) return;
	if ( empty( $_POST['weibo2wp_meta_nonce'] ) || ! wp_verify_nonce( $_POST['weibo2wp_meta_nonce'], 'weibo2wp_save_data' ) ) return;
	if ( !current_user_can( 'edit_post', $post_id )) return;

	$image_weibo = isset( $_POST['image_weibo'] ) ? $_POST['image_weibo'] : array();
	$image_image = isset( $_POST['image_image'] ) ? $_POST['image_image'] : array();
	
	if( !empty( $image_weibo ) )
	{
		foreach($image_weibo as $key => $val)
		{
			if( empty( $val ) )
				unset( $image_weibo[$key] );
		}
		
		update_post_meta( $post_id, '_weibo_image', serialize( $image_weibo ) );
	}
	
	if( !empty( $image_image ) )
	{
		foreach($image_image as $key => $val)
		{
			if( empty( $val ) )
				unset( $image_image[$key] );
		}
		
		update_post_meta( $post_id, '_image_image', serialize( $image_image ) );
	}
}

add_action( 'save_post', 'weibo2wp_meta_boxes_save', 1, 2 );