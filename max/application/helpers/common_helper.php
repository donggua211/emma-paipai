<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
  Common Functions.
*/
function get_current_uri() {
	static $uri = null;
	
	if ($uri === null) {
		$CI =& get_instance();
		
		$class = $CI->router->fetch_class();
		$method = $CI->router->fetch_method();
		$uri = "$class/$method";
	}
	
	return $uri;
}