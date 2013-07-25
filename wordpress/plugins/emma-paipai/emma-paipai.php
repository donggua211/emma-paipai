<?php
/*
 * Plugin Name: emma-paipai.com
 * Plugin URI: http://www.emma-paipai.com/plugin/emma-paipai/
 * Description: This is a plugin for emma-paipai.com. Contains Google Analysis account.
 * Author: Yuan Zhao
 * Version: 1.0
 * Author URI: http://www.emma-paipai.com/donggua211/
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function emmapaipai_favicon(){
	$output = '<link rel="shortcut icon" type="image/x-icon" href="' . plugins_url( '/favicon.ico', __FILE__ ) . '">';
	
	if ( $output != '' )
		echo stripslashes( $output ) . "\n";
}
add_action( 'wp_head','emmapaipai_favicon' );


function emmapaipai_analytics(){
	$output = "
	<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-42736021-1', 'emma-paipai.com');
		ga('send', 'pageview');
	</script>";
	
	if ( $output != '' )
		echo stripslashes( $output ) . "\n";
}
add_action( 'wp_footer','emmapaipai_analytics' );