<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function ez_gallery_meta_boxes() {
	
	remove_meta_box( 'commentsdiv', 'ez_gallery' , 'normal' );
	remove_meta_box( 'commentstatusdiv', 'ez_gallery' , 'normal' );
	add_meta_box( 'ez_gallery-data', __( 'Gallery Images', 'ez_gallery' ), 'ez_gallery_image_box', 'ez_gallery', 'normal', 'high' );
}

add_action( 'add_meta_boxes', 'ez_gallery_meta_boxes' );


/**
 * Display the dvd data meta box.
 *
 * Displays the dvd data box, tabbed, with several panels covering price etc.
 *
 * @access public
 * @return void
 */
function ez_gallery_image_box() {
	global $post, $wpdb, $thepostid;
	wp_nonce_field( 'ez_gallery_save_data', 'ez_gallery_meta_nonce' );

	$thepostid = $post->ID;

	$images 	= get_post_meta( $thepostid, '_images', true );
	
	$images = $images ? unserialize( $images ) : array();
	
	?>
	<div class="panel ez_gallery_options_panel">
	
		<div class="options_group">
			<div class="image-title clearfix">
				<button type="button" class="add_row button"><?php _e( 'Add New Image', 'ez_gallery' ); ?></button>
			</div>
			<div class="ez-gallery-image-wrap">
				<table class="wp-list-table widefat image-table" cellspacing="0">
					<tbody id="ez_gallery_image_list">
						<?php if( !empty( $images ) ):?>
							<?php foreach($images as $val):?>
							<tr>
								<td class="image-thumb">
									<img src="<?php echo $val['url'] ; ?>">
								</td>
								<td class="image-url">
									<?php _e( 'Image URL', 'ez_gallery' ); ?>: <input type="text" name="images[]" value="<?php echo $val['url']; ?>">
								</td>
								<td class="image-button">
									<button type="button" class="remove_row button"><?php _e( 'Remove', 'ez_gallery' ); ?></button>
								</td>
							</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td class="image-thumb">
									<img src="">
								</td>
								<td class="image-url">
									<?php _e( 'Image URL', 'ez_gallery' ); ?>: <input type="text" name="images[]" value="">
								</td>
								<td class="image-button">
									<button type="button" class="remove_row button"><?php _e( 'Remove', 'ez_gallery' ); ?></button>
								</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
		
		<div class="clear"></div>

	</div>
	<?php
}
