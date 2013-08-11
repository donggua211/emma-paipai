<?php

/**
 * Handle redirects before content is output - hooked into template_redirect so is_page works.
 *
 * @access public
 * @return void
 */
function odsea_template_redirect() {
	global $odsea, $wp_query;

	// When default permalinks are enabled, redirect shop page to post type archive url
	if ( is_page( get_gallery_page_id() ) ) {
		wp_safe_redirect( get_post_type_archive_link('ez_gallery') );
		exit;
	}
}

add_action( 'template_redirect', 'odsea_template_redirect' );



function ez_gellary_pre_post( $q ) {
	if ( is_post_type_archive( 'ez_gallery' ) )
		$q->set( 'posts_per_page', -1 );
}

add_filter( 'pre_get_posts', 'ez_gellary_pre_post' );