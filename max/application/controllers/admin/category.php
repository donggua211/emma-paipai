<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends Admin_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->model('admin/category_model');
	}
	
	public function index() {
		$data['category_list'] = $this->category_model->tree();
		$this->load->admin_template('category/index', $data);
	}
	
	public function add() {
		if(isset($_POST) && !empty($_POST)) {
			foreach($_POST as $key => $val) {
				if(in_array($key, array('submit'))) {
					continue;
				}
				
				if(in_array($key, array('parent_id'))) {
					$data[$key] = intval($this->input->post($key));
				} else {
					$data[$key] = $this->input->post($key);
				}
			}
			
			if(empty($data['category_name'])) {
				$data['message']['error'] = '名称不能为空.';
			} else {
				if($this->category_model->add($data)) {
					$data['message']['ok'] = '添加成功! ';
					show_result_page($data['message'], 'admin/category');
					return true;
				} else {
					$data['message']['error'] = '添加失败, 请重试.';
				}
			}
		}
		
		$data['category_list'] = $this->category_model->tree();
		$this->load->admin_template('category/add', $data);
	}
	
	public function edit($id = 0) {
		$id = $this->input->get_post('id') ? $this->input->get_post('id') : $id;
		if($id <= 0) {
			$data['message']['error'] = '您输入的页面不存在, 请返回重试! ';
			show_result_page($data['message'], 'admin/category');
			return false;
		}
		
		$catetory_info = $this->category_model->one($id);
		if(empty($catetory_info)) {
			$data['message']['error'] = '您输入的页面不存在, 请返回重试! ';
			show_result_page($data['message'], 'admin/category');
			return false;
		}
		
		if(isset($_POST) && !empty($_POST)) {
			foreach($_POST as $key => $val) {
				if(in_array($key, array('submit'))) {
					continue;
				}
				
				if(in_array($key, array('parent_id'))) {
					$data[$key] = intval($this->input->post($key));
				} else {
					$data[$key] = $this->input->post($key);
				}
			}
			
			$update_field = array();
			foreach($data as $key => $val) {
				if(($val != $catetory_info[$key])) {
					$update_field[$key] = $val;
				}
			}
			
			if($this->category_model->update($id, $update_field)) {
				$data['message']['ok'] = '更新成功! ';
				show_result_page($data['message'], 'admin/category');
				return true;
			} else {
				$data['message']['error'] = '分类添加失败, 请重试.';
			}
		} else {
			$data = $catetory_info;
		}
		
		$data['category_list'] = $this->category_model->tree();
		$this->load->admin_template('category/edit', $data);
	}

	function remove($id = 0) {
		$id = $this->input->get_post('id') ? $this->input->get_post('id') : $id;
		if($id <= 0) {
			$data['message']['error'] = '您输入的页面不存在, 请返回重试! ';
			show_result_page($data['message'], 'admin/category');
			return false;
		}
		
		$catetory_info = $this->category_model->one($id);
		if(empty($catetory_info)) {
			$data['message']['error'] = '您输入的页面不存在, 请返回重试! ';
			show_result_page($data['message'], 'admin/category');
			return false;
		}
		
		if($this->category_model->remove($id)) {
			$data['message']['ok'] = '删除成功! ';
		} else {
			$data['message']['error'] = '删除失败, 请重试.';
		}
		
		show_result_page($data['message'], 'admin/category');
		return true;
	}
}