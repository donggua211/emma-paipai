<?php
/*
 * Plugin Name: Weibo to Wordpress
 * Plugin URI: http://donggua211.emma-paipai.com/index.php/weibo2wp/
 * Description: The goal of this plugin is to help people Synchronize their Weibo( which is a very popular light blog in China) to WP.
 * Author: Yuan Zhao
 * Version: 1.2.2
 * Author URI: http://donggua211.emma-paipai.com/
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Weibo2wp' ) ) {

	class Weibo2wp {

		/**
		 * @var string
		 */
		public $version = '1.0';
		
		/* Tencent Weibo APP Key, Do not change value unless you know what are you doing */
		public $client_id = '801386946';
		public $client_secret = '8ffa00306c5ddf31de7d9a637ba7126a';
	
		/* session Class var */
		public $session = NULL;
		
		/* added auth list */
		public $auth_list = array();
	
		/* @var string */
		public $plugin_url;
	
		/**
		 * @var string
		 */
		public $plugin_path;
		
		/* message array */
		public $errors = array();
		public $messages = array();
		
		/* debug mode */
		public $debug = true;
		
		public function __construct() {
			// Include required files
			$this->includes();
			
			// Installation
			register_activation_hook( __FILE__, array( $this, 'activate' ) );
		
			/* Get saved auth list from DB */
			$this->init_auth_list();
			
			// Hooks
			add_action( 'init', array( $this, 'init' ), 0 );
		}

		function includes() {
			if ( is_admin() )
				$this->admin_includes();
			if ( defined('DOING_AJAX') )
				$this->ajax_includes();

			//Include Tencent API files
			include_once( 'classes/tencent-sdk2.1.1/class-oauth.php' );
			include_once( 'classes/tencent-sdk2.1.1/class-common.php' );

			include_once( 'classes/class-weibo.php' );						//Weibo Class handle interaction with Tencent Weibo
			include_once( 'classes/class-logger.php' );						//Weibo Class handle interaction with Tencent Weibo
			include_once( 'classes/session/abstract-weibo2wp-session.php' ); 		// Abstract for session implementations
			include_once( 'classes/session/class-weibo2wp-session-handler.php' );   // Odsea Session class
		
			include_once( 'weibo2wp-core-functions.php' );					//Core function
			include_once( 'weibo2wp-functions.php' );					//Core function
			include_once( 'weibo2wp-hooks.php' );						//Hooks function
		}

		public function admin_includes() {
			include_once( 'admin/admin-init.php' );
		}
		
		public function ajax_includes() {
			include_once( 'weibo2wp-ajax.php' );						// Ajax functions for admin and the front-end
		}

		public function init() {
			/* Set client_id and  client_secret */
			$this->check_client();
			
			/* init session class */
			$this->session = new Weibo2wp_Session_Handler();
			
			$this->load_messages();
		}
		
		public function check_client()
		{
			if (!$this->client_id || !$this->client_secret)
			{
				$this->add_error('Client ID or Client Secret can not be empty');
				return false;
			}
			
			return true;
		}
		
		public function activate()
		{
			wp_clear_scheduled_hook( 'weibo2wp_synch_dailly_hook' );
			wp_schedule_event( time(), 'hourly', 'weibo2wp_synch_dailly_hook' );
		
		}
		
		public function init_auth_list() {
			
			$auth_string = get_option('weibo2wp_auth_list', '');
			$this->auth_list = $auth_string ? unserialize( $auth_string ) : array();
			
			return $this->auth_list;
		}
		
		public function has_authed()
		{
			return ( !empty( $this->auth_list ) ) ? true : false;
		}
		
		function get_auth_list()
		{
			return $this->auth_list;
		}
		
		public function logger() {
			return new Weibo2wp_Logger();
		}
		
		function save_auth_list()
		{
			update_option( 'weibo2wp_auth_list', serialize( $this->auth_list ) );
			return true;
		}
		
		function add_auth($auth_info)
		{
			if ( empty( $auth_info ) )
			{
				return false;
			}
			
			$this->auth_list[$auth_info['openid']] = $auth_info;
			
			return $this->save_auth_list();
		}
		
		function check_auth_exist( $openid )
		{
			return array_key_exists( $openid, $this->auth_list );
		}
		
		function get_auth( $openid )
		{
			if( !$this->check_auth_exist( $openid ) )
				return array();
			else
				return $this->auth_list[$openid];
		}
		
		function delete_auth( $openid )
		{
			if ( isset( $this->auth_list[$openid] ) )
			{
				unset( $this->auth_list[$openid] );
				return $this->save_auth_list();
			}
			
			return true;
		}
		
		function clear_auth_info()
		{
			delete_option( 'weibo2wp_auth_list' );
		}
		
		function output_tweets($oauth_token , $oauth_secret, $tweet_count=15)
		{
			$c = new MBApiClient( MB_AKEY , MB_SKEY , $oauth_token , $oauth_secret );
			echo '<div style="display:none;">';
			$me = $c->getUserInfo();
				$p =array(
				'f' => 0,
				't' => 0,		
				'n' => $tweet_count,
				'name' => $me['data']['name']
				);
				
				$ms = $c->getTimeline($p);
				
				
			echo '</div>';
				
				if(count($ms) == 0)
				{
					echo '<p>你还没有发过微博吧，到腾讯微博去发一个呗 :)</p>';
				}
				
				else
				
				{
					$temp = $ms['data']['info'];
					//echo $ms['ret'];
					//echo $ms['Data'];
				?>
				<div >
					<ol id="weibo">
						<?php foreach( $temp as $item ){ ?>
						<li style="list-style-type: none;margin-bottom: 8px;background-repeat: no-repeat;background-position: 0px 0px;padding-left: 24px;">
							<?php
							$text = $this->format_tweet($item['text']);
							echo ($text);
							//$format = human_readable_time($item['created_at']);
							//$tweet_url = 'http://open.t.qq.com/api/t/show?format=xml&id='.$item['user']['id'];
							//echo "&nbsp;&nbsp;<a href='$tweet_url' class='weibo_link' target='_blank'>${format}前</a>";
							?>
						</li>
						<?php }?>
					</ol>
				</div>
				<?php
					echo "<p class='follow_me' style='background-repeat: no-repeat;background-position: 0px 0px;padding-left: 20px;float: right;'><a href='http://t.qq.com/" . $me['data']['name']. "' target='_blank'>关注我吧</a></p><p class='clear'></p>";
				}
		}

		private function format_tweet($tweet_msg)
		{
			$tweet_msg = add_topic_link($tweet_msg);
			//$tweet_msg = add_url_link($tweet_msg);
			$tweet_msg = add_at_link($tweet_msg);
			return $tweet_msg;
		}
		
		
		/** Messages ****************************************************************/
		/**
		 * Load Messages.
		 *
		 * @access public
		 * @return void
		 */
		public function load_messages() {
			$this->errors = $this->session->errors;
			$this->messages = $this->session->messages;
			unset( $this->session->errors, $this->session->messages );

			// Load errors from querystring
			if ( isset( $_GET['odsea_error'] ) )
				$this->add_error( esc_attr( $_GET['odsea_error'] ) );
		}


		/**
		 * Add an error.
		 *
		 * @access public
		 * @param string $error
		 * @return void
		 */
		public function add_error( $error ) {
			$this->errors[] = apply_filters( 'odsea_add_error', $error );
		}


		/**
		 * Add a message.
		 *
		 * @access public
		 * @param string $message
		 * @return void
		 */
		public function add_message( $message ) {
			$this->messages[] = apply_filters( 'odsea_add_message', $message );
		}


		/**
		 * Clear messages and errors from the session data.
		 *
		 * @access public
		 * @return void
		 */
		public function clear_messages() {
			$this->errors = $this->messages = array();
			unset( $this->session->errors, $this->session->messages );
		}


		/**
		 * error_count function.
		 *
		 * @access public
		 * @return int
		 */
		public function error_count() {
			return sizeof( $this->errors );
		}


		/**
		 * Get message count.
		 *
		 * @access public
		 * @return int
		 */
		public function message_count() {
			return sizeof( $this->messages );
		}


		/**
		 * Get errors.
		 *
		 * @access public
		 * @return array
		 */
		public function get_errors() {
			return (array) $this->errors;
		}


		/**
		 * Get messages.
		 *
		 * @access public
		 * @return array
		 */
		public function get_messages() {
			return (array) $this->messages;
		}

		/**
		 * Set session data for messages.
		 *
		 * @access public
		 * @return void
		 */
		public function set_messages() {
			$this->session->errors = $this->errors;
			$this->session->messages = $this->messages;
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

	/**
	 * Init Weibo2wp class
	 */
	$GLOBALS['weibo2wp'] = new Weibo2wp();

}