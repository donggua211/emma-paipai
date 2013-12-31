<?php
/**
 * Functions for the settings page in admin.
 *
 * The settings page contains options for the WooCommerce plugin - this file contains functions to display
 * and save the list of options.
 *
 * @author 		WooThemes
 * @category 	Admin
 * @package 	WooCommerce/Admin/Settings
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if ( ! function_exists( 'weibo2wp_settings' ) ) {

	/**
	 * Settings page.
	 *
	 * Handles the display of the main weibo2wp settings page in admin.
	 *
	 * @access public
	 * @return void
	 */
	function weibo2wp_settings() {
	    global $weibo2wp;
		
		//echo title
		echo '<div id="icon-options-general" class="icon32"><br></div><h2>' . __( 'Manage Weibo', 'weibo2wp' ) . '</h2>';
		
		
		$error		 = $weibo2wp->get_errors();
		$messages	 = $weibo2wp->get_messages();

		// Get any returned messages
		if ( !empty( $error ) )
		{
			foreach ( $error as $message )
			{
				echo '<div id="message" class="message error"><p><strong>' . esc_html( $message ) . '</strong></p></div>';
			}
		}
		
		if ( !empty( $messages ) )
		{
			foreach ( $messages as $message )
			{
				echo '<div id="message" class="message updated"><p><strong>' . esc_html( $message ) . '</strong></p></div>';
			}
		}
		
		
		//用户已授权
		if ( $weibo2wp->has_authed() ) {
		
		?>
		<div class="wrap">
			<table class="wp-list-table widefat" cellspacing="0">
				<thead>
					<tr>
						<th class="column-weibo-image"><span><?php _e( 'weibo Icon', 'weibo2wp' ); ?></span></th>
						<th><span><?php _e( 'weibo name', 'weibo2wp' ); ?></span></th>
						<th><span><?php _e( 'opetation', 'weibo2wp' ); ?></span></th>
					</tr>
				</thead>

				<tbody>
				<?php
				foreach($weibo2wp->get_auth_list() as $auth_info)
				{
					$remove_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=weibo2wp-remove-weibo&openid=' . $auth_info['openid'] ), 'weibo2wp-remove-weibo' );
					$synch_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=weibo2wp-synch-weibo&openid=' . $auth_info['openid'] ), 'weibo2wp-synch-weibo' );
					$synch_all_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=weibo2wp-synch-weibo&type=all&openid=' . $auth_info['openid'] ), 'weibo2wp-synch-weibo' );
					$delete_all_url = wp_nonce_url( admin_url( 'admin-ajax.php?action=weibo2wp-delete-post&openid=' . $auth_info['openid'] ), 'weibo2wp-delete-post' );
					
					echo '
					<tr>
						<td class="column-weibo-image"><img alt="" src="' . $auth_info['head'] .'/40"></td>
						<td class="column-weibo-name"><strong>' . $auth_info['name'] .'</strong></td>
						<td>
							<span class="edit"><a href="' . $synch_url . '">' . __( 'Synch weibo', 'weibo2wp' ) . '</a> | </span>
							<span class="edit"><a href="' . $synch_all_url . '">' . __( 'Synch All weibo', 'weibo2wp' ) . '</a></span><br/>
							<span class="delete"><a href="' . $remove_url . '">' . __( 'Remove weibo', 'weibo2wp' ) . '</a> | </span>
							<span class="delete"><a href="' . $delete_all_url . '">' . __( 'Remove all Post', 'weibo2wp' ) . '</a></span>
						</td>
					</tr>';
				}
				?>
				</tbody>
			</table>
		<?php
		}
			
		$callback = site_url();//回调url
		$oauth = new OAuth();
		$url = $oauth->getAuthorizeURL($callback);
		?>
	
			<form method="post" id="mainform" action="<?php echo $url; ?>" enctype="multipart/form-data">
				<p class="submit">
						<input name="save" class="button-primary" type="submit" value="<?php _e( 'authorization new weibo', 'weibo2wp' ); ?>" />
				</p>
			</form>
		</div>
		<?php
	}
}
