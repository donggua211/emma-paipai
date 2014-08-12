<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
  Debug Functions.
*/
function pr($val) {
	echo '<pre>';
	if(empty($val)) {
		var_dump($val);
	} else {
		print_r($val);
	}
	echo '</pre>';
	echo '<hr/>';
}


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

function array_2_tree(&$array) {
	$map = array(
		0 => array('children' => array())
	);

	foreach ($array as &$val) {
		$val['children'] = array();
		$map[$val['id']] = &$val;
	}

	foreach ($array as &$val) {
		$map[$val['parent_id']]['children'][$val['id']] = &$val;
	}

	return $map[0]['children'];
}

/*
 Common Template Functions
*/
function template_tree_select($array, $display_field, $selected = '', $top_option = FALSE, $select_id = 'parent_id') {
	echo '<select name="'.$select_id.'">';
		if($top_option !== FALSE) {
			echo '<option value="">'.$top_option.'</option>';
		}
		
		_template_tree_deep($array, $display_field, $selected);
		
	echo '</select>';
}

function _template_tree_deep($array, $display_field, $selected = '', $depth = 0) {
	$depth ++;
	foreach($array as $key => $val) {
		$prefix = '';
		for($i = 1; $i < $depth; $i++) {
			$prefix .= '　　';
		}
		echo '<option value="'.$key.'" '.($selected ==  $key ? 'SELECTED' : '').' >'.$prefix.$val[$display_field].'</option>';
		
		if(isset($val['children']) && !empty($val['children'])) {
			_template_tree_deep($val['children'], $display_field, $selected, $depth);
		}
	}
}

function template_tree_list($array, $display_field, $option_links = false, $depth = 0) {
	$depth ++;
	foreach($array as $key => $val) {
		$prefix = '';
		for($i = 1; $i < $depth; $i++) {
			$prefix .= '— ';
		}
		echo '<tr>';
		foreach($display_field as $display_key) {
			echo "<td>$prefix{$val[$display_key]}</td>\n";
		}
		
		if($option_links !== FALSE && is_array($option_links)) {
			echo '<td class="center">';
			$total = count($option_links);
			$count = 1;
			foreach($option_links as $option_link) {
				echo '<a href="'.site_url($option_link['uri']).'/'.$val['id'].'" '.(isset($option_link['class']) ? ' class="'.$option_link['class'].'" ' : '').'>'.$option_link['text'].'</a>';
				
				if($count < $total) {
					echo ' | ';
				}
				$count ++;
			}
			echo '</td>';
		}
		echo '</tr>';
		
		if(isset($val['children']) && !empty($val['children'])) {
			template_tree_list($val['children'], $display_field, $option_links, $depth);
		}
	}
}