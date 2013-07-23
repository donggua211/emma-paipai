<?php

function weibo2wp_remove_weibo() {

	if ( ! is_admin() ) die;

	if ( ! check_admin_referer('weibo2wp-remove-weibo')) wp_die( __( 'You have taken too long. Please go back and retry.', 'weibo2wp' ) );

	$openid = isset( $_GET['openid'] ) && $_GET['openid'] ? $_GET['openid'] : '';

	if ( empty( $openid ) ) die;

	set_time_limit(0);
	global $weibo2wp;
	
	$weibo = get_weibo( $openid );
	$weibo->delete();
	$weibo->delete_post();
	
	$weibo2wp->add_message( __( 'The selected weibo has been removed!', 'weibo2wp' ) );
	$weibo2wp->set_messages();
	
	wp_safe_redirect( admin_url( 'admin.php?page=weibo2wp' ) );
	exit();
}

add_action('wp_ajax_weibo2wp-remove-weibo', 'weibo2wp_remove_weibo');


function weibo2wp_synch_weibo() {

	if ( ! is_admin() ) die;

	if ( ! check_admin_referer('weibo2wp-synch-weibo')) wp_die( __( 'You have taken too long. Please go back and retry.', 'weibo2wp' ) );

	$openid = isset( $_GET['openid'] ) && $_GET['openid'] ? $_GET['openid'] : '';
	
	$type = isset( $_GET['type'] ) && $_GET['type'] ? $_GET['type'] : '';

	if ( empty( $openid ) ) die;

	set_time_limit(0);
	global $weibo2wp;
	
	$weibo = get_weibo( $openid );
	
	if( $type == 'all' )
		$weibo->synch( 'all' );
	else
		$weibo->synch();
	
	$weibo2wp->add_message( __( 'The selected weibo has been synched!', 'weibo2wp' ) );
	$weibo2wp->set_messages();
	
	wp_safe_redirect( admin_url( 'admin.php?page=weibo2wp' ) );
	exit();
}

add_action('wp_ajax_weibo2wp-synch-weibo', 'weibo2wp_synch_weibo');


function weibo2wp_delete_all_post() {

	if ( ! is_admin() ) die;

	if ( ! check_admin_referer('weibo2wp-delete-post')) wp_die( __( 'You have taken too long. Please go back and retry.', 'weibo2wp' ) );

	$openid = isset( $_GET['openid'] ) && $_GET['openid'] ? $_GET['openid'] : '';
	
	$type = isset( $_GET['type'] ) && $_GET['type'] ? $_GET['type'] : '';

	if ( empty( $openid ) ) die;

	set_time_limit(0);
	global $weibo2wp;
	
	$weibo = get_weibo( $openid );
	
	$weibo->delete_post();
	
	$weibo2wp->add_message( __( 'The Posts of selected weibo has been removed!', 'weibo2wp' ) );
	$weibo2wp->set_messages();
	
	wp_safe_redirect( admin_url( 'admin.php?page=weibo2wp' ) );
	exit();
}

add_action('wp_ajax_weibo2wp-delete-post', 'weibo2wp_delete_all_post');
