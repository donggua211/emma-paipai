<?php
/**
 * Plugin Name: EZ_Gallery
 * Plugin URI: http://www.emma-paipai.com/plugin/ez_gallery
 * Description: An Easy gallery.
 * Version: 1.0
 * Author: Yuan Zhao
 * Author URI: http://www.emma-paipai.com/donggua211/
 * Requires at least: 3.0
 * Tested up to: 3.6
 *
 * @package ez_gallery
 * @category Core
 * @author Yuan Zhao
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'EZ_Gallery' ) ) {

class EZ_Gallery {

	/**
	 * Constructor.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		// Include required files
		$this->includes();

		add_action( 'init', array( $this, 'init' ), 0 );
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 *
	 * @access public
	 * @return void
	 */
	function includes() {
		if ( is_admin() )
			$this->admin_includes();
		if ( ! is_admin() || defined('DOING_AJAX') )
			$this->frontend_includes();

		// Functions
		include_once( 'core-functions.php' );								// Contains core functions for the front/back end
	}


	/**
	 * Include required admin files.
	 *
	 * @TODO
	 * @access public
	 * @return void
	 */
	public function admin_includes() {
		include_once( 'admin/admin-init.php' );			// Admin section
	}

	/**
	 * Include required frontend files.
	 *
	 * @TODO
	 * @access public
	 * @return void
	 */
	public function frontend_includes() {
	
	}

	/**
	 * Init when WordPress Initialises.
	 *
	 * @access public
	 * @return void
	 */
	public function init() {
		$this->init_taxonomy();
	}

	/**
	 * Init taxonomies.
	 *
	 * @access public
	 * @return void
	 */
	public function init_taxonomy() {

		if ( post_type_exists('dvd') )
			return;

		/**
		 * Taxonomies
		 **/
		register_taxonomy( 'gallery_cat', array('ez_gallery'), 
			array(
	            'hierarchical' 			=> true,
	            'update_count_callback' => '_update_post_term_count',
	            'label' 				=> __( 'EZ Gallery Categories', 'ez_gallery'),
	            'labels' => array(
	                    'name' 				=> __( 'EZ Gallery Categories', 'ez_gallery'),
	                    'singular_name' 	=> __( 'EZ Gallery Category', 'ez_gallery'),
						'menu_name'			=> _x( 'Categories', 'Admin menu name', 'ez_gallery' ),
	                    'search_items' 		=> __( 'Search EZ Gallery Categories', 'ez_gallery'),
	                    'all_items' 		=> __( 'All EZ Gallery Categories', 'ez_gallery'),
	                    'parent_item' 		=> __( 'Parent EZ Gallery Category', 'ez_gallery'),
	                    'parent_item_colon' => __( 'Parent EZ Gallery Category:', 'ez_gallery'),
	                    'edit_item' 		=> __( 'Edit EZ Gallery Category', 'ez_gallery'),
	                    'update_item' 		=> __( 'Update EZ Gallery Category', 'ez_gallery'),
	                    'add_new_item' 		=> __( 'Add New EZ Gallery Category', 'ez_gallery'),
	                    'new_item_name' 	=> __( 'New EZ Gallery Category Name', 'ez_gallery')
	            	),
	            'show_ui' 				=> true,
	            'query_var' 			=> true,
	            'rewrite' 				=> array(
	            	'slug' => 'ez_gallery_cat',
	            	'with_front' => false,
	            	'hierarchical' => true,
	            ),
	        )
	    );

	    /**
		 * Post Types
		 **/
		register_post_type( "ez_gallery",
			array(
				'labels' => array(
						'name' 					=> __( 'EZ Gallery', 'ez_gallery' ),
						'singular_name' 		=> __( 'EZ Gallery', 'ez_gallery' ),
						'menu_name'				=> _x( 'EZ Gallery', 'Admin menu name', 'ez_gallery' ),
						'add_new' 				=> __( 'Add EZ Gallery', 'ez_gallery' ),
						'add_new_item' 			=> __( 'Add New EZ Gallery', 'ez_gallery' ),
						'edit' 					=> __( 'Edit', 'ez_gallery' ),
						'edit_item' 			=> __( 'Edit EZ Gallery', 'ez_gallery' ),
						'new_item' 				=> __( 'New EZ Gallery', 'ez_gallery' ),
						'view' 					=> __( 'View EZ Gallery', 'ez_gallery' ),
						'view_item' 			=> __( 'View EZ Gallery', 'ez_gallery' ),
						'search_items' 			=> __( 'Search EZ Gallery', 'ez_gallery' ),
						'not_found' 			=> __( 'No EZ Gallery found', 'ez_gallery' ),
						'not_found_in_trash' 	=> __( 'No EZ Gallery found in trash', 'ez_gallery' ),
						'parent' 				=> __( 'Parent EZ Gallery', 'ez_gallery' )
					),
				'description' 			=> __( 'This is where you can manage EZ Gallery.', 'ez_gallery' ),
				'public' 				=> true,
				'show_ui' 				=> true,
				'show_in_menu' 			=> true,
				'capability_type' 		=> 'post',
				'map_meta_cap'			=> true,
				'publicly_queryable' 	=> true,
				'exclude_from_search' 	=> false,
				'hierarchical' 			=> false,
				'rewrite' 				=> array( 'slug' => 'ez_gallery', 'with_front' => false, 'feeds' => true),
				'query_var' 			=> true,
				'supports' 				=> array( 'title', 'editor', 'excerpt', 'thumbnail', 'comments', 'page-attributes' ),
				'has_archive' 			=> true,
				'show_in_nav_menus' 	=> true
			)
		);
	}
}

$GLOBALS['ez_gallery'] = new EZ_Gallery();

} // class_exists check