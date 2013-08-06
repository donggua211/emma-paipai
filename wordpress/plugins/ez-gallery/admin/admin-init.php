<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Include some admin files conditonally.
 *
 * @access public
 * @return void
 */
function ez_gallery_admin_init() {
	global $pagenow;
	
	if ( $pagenow == 'post-new.php' || $pagenow == 'post.php' || $pagenow == 'edit.php' ) {

		include_once( 'post-types/writepanel-ez-gallery.php' );
	}
	
	$print_css_on = array( 'post-new.php', 'edit.php', 'post.php' );

    foreach ( $print_css_on as $page )
    	add_action( 'admin_print_styles-'. $page, 'ez_gallery_admin_css' );
}
add_action('admin_init', 'ez_gallery_admin_init');

function ez_gallery_admin_css() {
	global $ez_gallery;

	wp_enqueue_style( 'ez_gallery_admin_styles', $ez_gallery->plugin_url() . '/assets/css/admin.css' );
}


function ez_gallery_admin_scripts() {
	global $ez_gallery;

	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-effects-core');
	wp_enqueue_script('jquery-effects-highlight');
	wp_enqueue_script('jquery-effects-fade');
	
	wp_enqueue_script( 'ez_gallery_writepanel', $ez_gallery->plugin_url() . '/assets/js/write-panels.js', array('jquery') );
	
	$ez_gallery_witepanel_params = array(
		'remove_button'			=> __( 'Remove', 'ez_gallery' ),
		'image_url' 			=> __( 'Image URL', 'ez_gallery' ),
	 );

	wp_localize_script( 'ez_gallery_writepanel', 'ez_gallery_writepanel_params', $ez_gallery_witepanel_params );
}
add_action( 'admin_enqueue_scripts', 'ez_gallery_admin_scripts' );

function ez_gallery_meta_boxes_save( $post_id, $post ) {
	if ( empty( $post_id ) || empty( $post ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( is_int( wp_is_post_revision( $post ) ) ) return;
	if ( is_int( wp_is_post_autosave( $post ) ) ) return;
	if ( empty( $_POST['ez_gallery_meta_nonce'] ) || ! wp_verify_nonce( $_POST['ez_gallery_meta_nonce'], 'ez_gallery_save_data' ) ) return;
	if ( !current_user_can( 'edit_post', $post_id )) return;

	set_time_limit(0);
	
	$images = isset( $_POST['images'] ) ? array_unique( $_POST['images'] ) : array();
	
	$images_result = array();
	if( !empty( $images ) )
	{
		foreach($images as $key => $val)
		{
			if( empty( $val ) )
				continue;
			
			$image_size = getimagesize( $val );
			if( empty( $image_size ) )
				continue;
			
			$images_result[] = array(
				'width' 	=> $image_size[0],
				'height' 	=> $image_size[1],
				'url' 		=> $val,
			);
		}
		
		update_post_meta( $post_id, '_images', serialize( $images_result ) );
	}
}
add_action( 'save_post', 'ez_gallery_meta_boxes_save', 1, 2 );


function ez_gallery_comments_on( $data )
{
    if( $data['post_type'] == 'ez_gallery' )
	{
        $data['comment_status'] = 'open';
    }

    return $data;
}
add_filter( 'wp_insert_post_data', 'ez_gallery_comments_on' );


function ez_gallery_disable_autosave_for_orders(){
    global $post;

    if ( $post && get_post_type( $post->ID ) === 'ez_gallery' ) {
        wp_dequeue_script( 'autosave' );
    }
}
add_action( 'admin_print_scripts', 'ez_gallery_disable_autosave_for_orders' );