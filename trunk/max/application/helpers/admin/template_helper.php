<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function get_admin_site_nav() {
	static $site_nav = null;

	if ($site_nav === null) {
		$CI =& get_instance();
		
		$CI->config->load('admin/site_nav.php');
		$site_nav = $CI->config->config['site_nav'];
	}
	
	return $site_nav;
}

/*
  Template Functions.
*/
function show_result_page($message, $back_url = '', $extra_html = '') {
	if(empty($back_url)) {
		if(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
			$back_url = $_SERVER['HTTP_REFERER'];
		} else {
			$back_url = site_url('admin');
		}
	} else {
		$back_url = site_url($back_url);
	}
	
	$CI =& get_instance();
	
	$data['message'] = $message;
	$data['back_url'] = $back_url;
	$data['extra_html'] = $extra_html;
	$CI->load->admin_template('parts/show_result', $data);
}

function template_nav_menu() {
	$site_nav = get_admin_site_nav();
	if(empty($site_nav)) {
		return false;
	}
	
	foreach($site_nav as $key => $val) {
		echo '<div class="menu-box">
			<h3 class="menu-bar" id="'.$key.'">
				<span>'.$val['title'].'</span>
				<button oritabindex="0" tabindex="0">-</button>
			</h3>
			<ul id="menu-list-'.$key.'">';
		
		if(isset($val['sub']) && !empty($val['sub'])) {
			foreach($val['sub'] as $sub_val) {
				if(isset($sub_val['hidden']) && true == $sub_val['hidden']) {
					continue;
				}
				
				echo '<li';
				
				if(get_current_uri() == $sub_val['uri']) {
					echo ' class="open"';
				} elseif(isset($sub_val['sub'])) {
					foreach($sub_val['sub'] as $sub_sub_val) {
						if(get_current_uri() == $sub_sub_val['uri']) {
							echo ' class="open"';
							break;
						}
					}
				}
				
				echo '><span>';
				if(isset($sub_val['uri']) && !empty($sub_val['uri'])) {
					echo '<a href="'.site_url('admin/'.$sub_val['uri']).'">'.$sub_val['title'].'</a>';
				} else {
					echo $sub_val['title'];
				}
				echo '</span></li>';
		
			}
		}
		
		echo '
			</ul>
		</div>';
	}
	
	return true;
}

function template_page_title($page_title = '', $seperator = ' | ' ) {
	$site_name = 'Max美国代购管理';
	
	if(empty($page_title)) {
		$site_nav = get_admin_site_nav();
		if(!empty($site_nav)) {
			foreach($site_nav as $top_level) {
				//search for sub nav first.
				if(isset($top_level['sub']) && !empty($top_level['sub'])) {
					if(in_array(get_current_uri(), array('home/index'))) {
						$page_title = array($top_level['title']);
						break 1;
					}
					
					foreach($top_level['sub'] as $sub_val) {
						if(get_current_uri() == $sub_val['uri']) {
							$page_title = array($sub_val['title'], $top_level['title']);
							break 2;
						}
						
						if(isset($sub_val['sub'])) {
							foreach($sub_val['sub'] as $sub_sub_val) {
								if(get_current_uri() == $sub_sub_val['uri']) {
									$page_title = array($sub_sub_val['title'], $sub_val['title'], $top_level['title']);
									break 3;
								}
							}
						}
					}
				}
				
				//if not found in sub nav, then try to match top nav
				if(get_current_uri() == $top_level['uri']) {
					$page_title = $top_level['title'];
					break;
				}
				
			}
		}
	}
	
	//OUTPUT
	if( empty( $page_title ) ) {
		echo $site_name;
	} else {
		if( !is_array( $page_title ) ) {
			$page_title = (array) $page_title;
		}
		
		$page_title[] = $site_name;
		echo implode( $seperator, $page_title);
	}
	
	return true;
}

function template_breadcrumbs() {
	$site_nav = get_admin_site_nav();
	if(empty($site_nav)) {
		return false;
	}
	
	//DO not show breadcrumbs for home page
	if( in_array( get_current_uri(), array('home/index') ) ) {
		return false;
	}
	
	foreach($site_nav as $top_level) {
		//search for sub nav first.
		if( isset( $top_level['sub'] ) && !empty( $top_level['sub'] ) )	{
			foreach($top_level['sub'] as $sub_val) {
				if(get_current_uri() == $sub_val['uri']) {
					$breadcrumbs[] = array(
						'title' => $top_level['title'],
						'uri' => $top_level['uri'],
					);
					$breadcrumbs[] = array(
						'title' => $sub_val['title'],
					);
					
					break 2;
				}
				
				if(isset($sub_val['sub'])) {
					foreach($sub_val['sub'] as $sub_sub_val) {
						if(get_current_uri() == $sub_sub_val['uri']) {
							$breadcrumbs[] = array(
								'title' => $top_level['title'],
								'uri' => $top_level['uri'],
							);
							$breadcrumbs[] = array(
								'title' => $sub_val['title'],
								'uri' => $sub_val['uri'],
							);
							$breadcrumbs[] = array(
								'title' => $sub_sub_val['title'],
							);
							break 3;
						}
					}
				}
			}
		}
		
		//if not found in sub nav, then try to match top nav
		if(get_current_uri() == $top_level['uri']) {
			$breadcrumbs[] = array(
				'title' => $top_level['title'],
			);
			break;
		}
		
	}
	
	if(empty($breadcrumbs)) {
		$breadcrumbs[] = array(
			'title' => '其他',
		);
	}
	
	//Start Output
	echo '<div class="breadcrumbs clearfix">';
	
	//output 首页 link
	echo '<a href="home.php">首页</a><span>&gt;</span>';
	
	//Loop
	foreach($breadcrumbs as $val) {
		if(isset( $val['uri'])) {
			echo '<a href="' . site_url('admin/'.$val['uri']) . '">' . $val['title'] . '</a><span>&gt;</span>';
		} else {
			echo $val['title'];
		}
	}
	
	//Output end
	echo '</div>';
}