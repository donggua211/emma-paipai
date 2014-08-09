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
				
				if(get_current_uri() == $sub_val['uri'] || (isset($sub_val['alias']) && in_array(get_current_uri(), $sub_val['alias']))) {
					echo ' class="open"';
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

/*
 * Output page title
 */
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

/*
 * Output site Nav menu
 */
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