<?php

function get_gallery_page_id() {
	$page = get_option('ez_gallery_page_id');
	return ( $page ) ? $page : -1;
}