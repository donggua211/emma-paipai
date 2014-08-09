<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front_Controller extends CI_Controller {
	
	var $template_data = array();
	
	function __construct() {
		parent::__construct();
	}
}

class Admin_Controller extends CI_Controller {

	function __construct() {
		parent::__construct();
		
		$current_controller = $this->router->fetch_class();
		
		if (!in_array($current_controller, array('admin_user')) && !$this->_admin_check_login()) {
			$uri = urlencode(uri_string());
			redirect('admin/admin_user/login/?uri='.$uri);
		}
		
		//Load helper
		$this->load->helper('admin/template');
	}
	
	/*
	  Login Functions.
	*/
	function _admin_check_login() {
		return $this->session->userdata('admin_id');
	}
}
