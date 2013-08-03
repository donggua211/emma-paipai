<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

<?php
$images = get_post_meta( get_the_ID(), '_images', true );
$images = $images ? unserialize( $images ) : array();

$random_key = !empty( $images ) ? array_rand( $images ) : -1;

$thumbnail_image = $random_key >= 0 ? $images[$random_key]['url'] : get_template_directory_uri() . '/image/thumbnail_not_available.jpg';

$thumbnail_image_style = '';

if( $random_key >= 0 )
{
	$width = $images[$random_key]['width'];
	$height = $images[$random_key]['height'];

	$off_set = round( ( abs( $width - $height ) / ( ( $width > $height ) ? $height : $width ) * 135 ) / 2 );

	$thumbnail_image_style = $width > $height ? 'height="135px" style="margin-left: -'.$off_set.'px"' : 'width="135px" style="margin-top: -'.$off_set.'px"';
}
?>
	<div id="post-<?php the_ID(); ?>" <?php post_class('entry-gallery'); ?>>
		<div class=".gallery-thumbnail">
			<div class="thumbnail-image">
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" ><img src="<?php echo $thumbnail_image; ?>" <?php echo $thumbnail_image_style; ?> /></a>
			</div>
		</div>
		
		<div class="gallery-title">
			<?php echo utf8_substr( get_the_title(), 30, '...' ); ?>
		</div><!-- .gallery-title -->
		
		<div class="gallery-meta">
			<?php echo count( $images ); ?>张
		</div><!-- .gallery-meta -->
	</div><!-- #post -->
