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
	    global $weibo2wp, $current_section;

	    // Save settings
	    if ( ! empty( $_POST ) ) {

	    	if ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'weibo2wp-settings' ) )
	    		die( __( 'Action failed. Please refresh the page and retry.', 'weibo2wp' ) );

				
			$fields = array( 'default_setting' );
			
			foreach($fields as $field)
			{
				if( !isset( $_POST[$field] ) )
					continue;
				
				$val = $_POST[$field];
				
				update_option( 'weibo2wp_' . $field, $val );
			}
				
			
			// Redirect back to the settings page
			$weibo2wp->add_message( __( 'Option Updated!', 'weibo2wp' ) );
			$weibo2wp->set_messages();
			
			wp_safe_redirect( admin_url( 'admin.php?page=weibo2wp_settings' ) );
			exit;
		}

		echo '<div id="icon-options-general" class="icon32"><br></div><h2>' . __( 'Weibo2wp Setting', 'weibo2wp' ) . '</h2>';
		
		
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
		
		?>
		
		<form method="post" id="mainform" action="" enctype="multipart/form-data">
		<?php wp_nonce_field( 'weibo2wp-settings', '_wpnonce', true, true ); ?>
	
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<th scope="row" class="titledesc">
						<label for="default_setting">Setting: </label>
					</th>
					<td class="forminp forminp-text">
						<input name="default_setting" id="default_setting" type="text" style="min-width:300px;" value="<?php echo get_option( 'weibo2wp_default_setting', '' );?>" class=""> <span class="description">Default Setting</span>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<?php if ( ! isset( $GLOBALS['hide_save_button'] ) ) : ?>
				<input name="save" class="button-primary" type="submit" value="<?php _e( 'Save changes', 'weibo2wp' ); ?>" />
			<?php endif; ?>
			<input type="hidden" name="subtab" id="last_tab" />
		</p>
		</form>
		<?php
		
	}
}
