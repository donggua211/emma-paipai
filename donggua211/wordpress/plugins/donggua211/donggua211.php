<?php
/*
 * Plugin Name: donggua211.emma-paipai.com
 * Plugin URI: http://www.emma-paipai.com/plugin/donggua211/
 * Description: This is a plugin for donggua211.emma-paipai.com.
 * Author: Yuan Zhao
 * Version: 1.0
 * Author URI: http://www.emma-paipai.com/donggua211/
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


function donggua211_favicon(){
	$output = '<link rel="shortcut icon" type="image/x-icon" href="' . plugins_url( '/favicon.ico', __FILE__ ) . '">';
	
	if ( $output != '' )
		echo stripslashes( $output ) . "\n";
}
add_action( 'wp_head','donggua211_favicon' );


function donggua211_analytics(){
	$output = "
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-42736021-2', 'emma-paipai.com');
  ga('send', 'pageview');

</script>";
	
	if ( $output != '' )
		echo stripslashes( $output ) . "\n";
}
add_action( 'wp_footer','donggua211_analytics' );