<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_User extends Admin_Controller {

	public function __construct() {
		parent::__construct();
		
		$this->load->model('admin/admin_user_model');
	}
	
	public function index() {
		redirect('admin/admin_user/login');
	}
	
	public function login() {
		if($this->_admin_check_login()) {
			redirect('admin');
		}
		
		$data['uri'] = $this->input->get_post('uri');
		
		if(isset($_POST['submit']) && !empty($_POST['submit'])) {
			$data['user_name'] = $this->input->post('user_name');
			$data['password'] = $this->input->post('password');
			
			if(empty($data['user_name'])) {
				$data['message']['error'] = '用户名不能为空!';
			} elseif(empty($data['password'])) {
				$data['message']['error'] = '密码不能为空!';
			} else {
				$admin_id = $this->admin_user_model->check_login($data['user_name'], $data['password']);
				
				if($admin_id > 0) {
					$session_data = array('admin_id' => $admin_id);
					$this->session->set_userdata($session_data);
					
					$uri = !empty($data['uri']) ? $data['uri'] : 'admin';
					redirect($uri);
				} else {
					if($admin_id == -1) {
						$data['message']['error'] = '您所输入的用户名没有找到，请重试!';
					} else {
						$data['message']['error'] = '密码错误，请重试!';
					}
				}
			}
		}
		
		$data['page_title'] = '管理员登陆';
		$data['header_silent'] = true;
		$this->load->admin_template('admin_user/login', $data);
	}
	
	public function logout() {
		$this->session->sess_destroy();
		redirect('admin/admin_user/login');
	}
}