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
		/* @var string */
		public $plugin_url;
		
		/**
		 * @var string
		 */
		public $template_url;
		
		/**
		 * @var string
		 */
		public $plugin_path;
	
		/**
		 * Constructor.
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			// Installation
			register_activation_hook( __FILE__, array( $this, 'activate' ) );
		
			// Include required files
			$this->includes();

			add_action( 'init', array( $this, 'init' ), 0 );
		}

		/**
		 * activate function.
		 *
		 * @access public
		 * @return void
		 */
		public function activate() {
			include_once( 'admin/admin-install.php' );
			do_install_ez_gallery();
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
			include_once( 'hook-functions.php' );
		}

		/**
		 * Init when WordPress Initialises.
		 *
		 * @access public
		 * @return void
		 */
		public function init() {
			$this->template_url			= 'ez_gallery/';
			
			if ( ! is_admin() || defined('DOING_AJAX') )
			{
				add_filter( 'template_include', array( $this, 'template_loader' ) );
			}
			$this->init_taxonomy();
		}

		/**
		 * Load a template.
		 *
		 * Handles template usage so that we can use our own templates instead of the themes.
		 *
		 * Templates are in the 'templates' folder. odsea looks for theme
		 * overrides in /theme/odsea/ by default
		 *
		 * For beginners, it also looks for a odsea.php template first. If the user adds
		 * this to the theme (containing a odsea() inside) this will be used for all
		 * odsea templates.
		 *
		 * @access public
		 * @param mixed $template
		 * @return string
		 */
		public function template_loader( $template ) {

			$find = array( 'odsea.php' );
			$file = '';

			if ( is_single() && get_post_type() == 'ez_gallery' ) {

				$file 	= 'single-gallery.php';
				$find[] = $file;
				$find[] = $this->template_url . $file;

			} elseif ( is_tax( 'gallery_cat' ) ) {

				$term = get_queried_object();

				$file 		= 'taxonomy-' . $term->taxonomy . '.php';
				$find[] 	= 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
				$find[] 	= $this->template_url . 'taxonomy-' . $term->taxonomy . '-' . $term->slug . '.php';
				$find[] 	= $file;
				$find[] 	= $this->template_url . $file;

			} elseif ( is_post_type_archive( 'ez_gallery' ) || is_page( get_gallery_page_id() ) ) {

				$file 	= 'archive-gallery.php';
				$find[] = $file;
				$find[] = $this->template_url . $file;

			}

			if ( $file ) {
				$template = locate_template( $find );
				if ( ! $template ) $template = $this->plugin_path() . '/templates/' . $file;
			}

			return $template;
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
					'supports' 				=> array( 'title', 'thumbnail' ),
					'has_archive' 			=> true,
					'show_in_nav_menus' 	=> true
				)
			);
		}
		
		/**
		 * Get the plugin url.
		 *
		 * @access public
		 * @return string
		 */
		public function plugin_url() {
			if ( $this->plugin_url ) return $this->plugin_url;
			return $this->plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
		}
		
		/**
		 * Get the plugin path.
		 *
		 * @access public
		 * @return string
		 */
		public function plugin_path() {
			if ( $this->plugin_path ) return $this->plugin_path;

			return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
		}
	}

	$GLOBALS['ez_gallery'] = new EZ_Gallery();

} // class_exists check