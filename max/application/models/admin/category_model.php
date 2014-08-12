<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model {

	var $cache_key = 'category_list';
	
	public function __construct() {
		parent::__construct();
		
		//Load Driver
		$this->load->driver('cache', array('adapter' => 'file'));
	}
	
	function add($data) {
		$fields['category_name'] = $data['category_name'];
		$fields['parent_id'] = $data['parent_id'];
		$fields['add_time'] = date('Y-m-d H:i:s');
		
		if($this->db->insert('categories', $fields)) {
			$this->cache_update();
			
			return true;
		} else {
			return false;
		}
	}
	
	function update($id, $update_field = array()) {
		if(empty($update_field)) {
			return true;
		}
		
		$this->db->where('id', $id);
		
		if($this->db->update('categories', $update_field)) {
			$this->cache_update();
			
			return true;
		} else {
			return false;
		}
	}
	
	private function cache_get() {
		$category_list = $this->cache->get($this->cache_key);
		if(empty($category_list)) {
			$category_list = array();
		}
		
		return $category_list;
	}
	
	private function cache_update() {
		$sql = "SELECT * FROM {$this->db->dbprefix('categories')}
				ORDER BY parent_id ASC";
		$query = $this->db->query($sql);
		
		$category_list = array();
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $val) {
				$category_list[$val['id']] = $val;
			}
			
			$category_list = array_2_tree($category_list);
		}
		
		$this->cache->save($this->cache_key, $category_list, 24 * 60 *60 * 365);
		return $category_list;
	}
	
	public function tree() {
		//Get frin Cache first.
		$category_list = $this->cache_get();
		
		if(empty($category_list)) {
			$category_list = $this->cache_update();
		}
		
		return $category_list;
	}
	
	public function one($id) {
		$sql = "SELECT * FROM {$this->db->dbprefix('categories')}
				WHERE id = '$id'
				LIMIT 1";
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0) {
			return $query->row_array();
		} else {
			return false;
		}
	}
	
	public function remove($id) {
		$this->_deep_remove($id);
		
		$this->cache_update();
		
		return true;
	}
	
	public function _deep_remove($id) {
		//Delete
		$this->db->delete('categories', array('id' => $id)); 
		
		//Check children
		$sql = "SELECT * FROM {$this->db->dbprefix('categories')}
				WHERE parent_id = '$id'";
		$query = $this->db->query($sql);
		
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $val) {
				$this->_deep_remove($val['id']);
			}
		}
		
		return true;
	}
	
	function getSubCategoris($cat_id)
	{
		$query = $this->db->get_where('article_cat', array('parent_id ' => $cat_id));
		if ($query->num_rows() > 0)
		{
			return $query->result_array();
		}
		else
		{
			return array();
		}	
	}
	
}
?>