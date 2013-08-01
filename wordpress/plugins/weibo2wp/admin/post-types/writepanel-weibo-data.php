<?php
/**
 * Product Data
 *
 * Function for displaying the dvd data meta boxes
 *
 * @author 		Odsea
 * @category 	Admin
 * @package 	Odsea/Admin/WritePanels
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function weibo2wp_meta_boxes() {
	global $post;

	//Only show meta box if it is a weibo
	if( is_weibo() )
		add_meta_box( 'weibo2wp-image', __( 'Weibo Informations', 'odsea' ), 'weibo2wp_image_box', 'post', 'normal', 'high' );
}

add_action( 'add_meta_boxes', 'weibo2wp_meta_boxes' );


/**
 * Display the dvd data meta box.
 *
 * Displays the dvd data box, tabbed, with several panels covering price etc.
 *
 * @access public
 * @return void
 */
function weibo2wp_image_box() {
	global $post, $wpdb, $thepostid, $odsea;
	wp_nonce_field( 'weibo2wp_save_data', 'weibo2wp_meta_nonce' );

	$thepostid = $post->ID;

	$id 	= get_post_meta( $thepostid, '_weibo_id', true );
	$image_weibo 	= get_post_meta( $thepostid, '_weibo_image', true );
	$image_image 	= get_post_meta( $thepostid, '_image_image', true );
	
	$image_weibo = $image_weibo ? unserialize( $image_weibo ) : array();
	$image_image = $image_image ? unserialize( $image_image ) : array();
	
	?>
	<div class="panel weibo2wp_options_panel">
		<div class="options_group">
			<div>
				<span for="total_sales">Weibo ID</span>
				<?php echo $id;?>
			</div>
		</div>
		
		
		<div class="options_group">
			<div class="image-title clearfix">
				<span><?php _e( 'Weibo Images', 'weibo2wp' ); ?></span>
				<button type="button" class="add_row button" data-type="weibo"><?php _e( 'Add New Image', 'weibo2wp' ); ?></button>
			</div>
			<table class="wp-list-table widefat image-table" cellspacing="0">
				<tbody id="weibo2wp_image_weibo_list">
					<?php foreach($image_weibo as $val):?>
					<tr>
						<td class="weibo-image-thumb">
							<img src="<?php echo $val . '/120'; ?>">
						</td>
						<td class="weibo-image-url">
							<?php _e( 'Image URL', 'weibo2wp' ); ?>: <input type="text" name="image_weibo[]" value="<?php echo $val; ?>">
						</td>
						<td class="weibo-image-button">
							<button type="button" class="remove_row button"><?php _e( 'Remove', 'weibo2wp' ); ?></button>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		
		<div class="options_group">
			<div class="image-title clearfix">
				<span><?php _e( 'Self Defined Images', 'weibo2wp' ); ?></span>
				<button type="button" class="add_row button" data-type="image"><?php _e( 'Add New Image', 'weibo2wp' ); ?></button>
			</div>
			<table class="wp-list-table widefat image-table" cellspacing="0">
				<tbody id="weibo2wp_image_image_list">
					<?php foreach($image_image as $val):?>
					<tr>
						<td class="weibo-image-thumb">
							<img src="<?php echo $val; ?>">
						</td>
						<td class="weibo-image-url">
							<?php _e( 'Image URL', 'weibo2wp' ); ?>: <input type="text" name="image_image[]" value="<?php echo $val; ?>">
						</td>
						<td class="weibo-image-button">
							<button type="button" class="remove_row button"><?php _e( 'Remove', 'weibo2wp' ); ?></button>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		
		<div class="clear"></div>

	</div>
	<?php
}
