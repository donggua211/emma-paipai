<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
	<div id="post-<?php the_ID(); ?>" <?php post_class('post-wrap'); ?>>
	
		<div class="post-content">
			<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" class="post-author"><?php the_author(); ?>:</a>
			
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'emmapaipai' ), 'after' => '</div>' ) ); ?>
		
		</div><!-- .post-content -->
		
		<?php
		$image_weibo 	= get_post_meta( get_the_ID(), '_weibo_image', true );
		$image_image 	= get_post_meta( get_the_ID(), '_image_image', true );
		
		$image_weibo = $image_weibo ? unserialize( $image_weibo ) : array();
		$image_image = $image_image ? unserialize( $image_image ) : array();
		
		if( !empty( $image_weibo ) || !empty( $image_image ) )
			echo '<div class="post-images">';
		
		foreach($image_weibo as $val)
			echo '<img src="' . $val . '/2000" />';
		
		foreach($image_image as $val)
			echo '<img src="' . $val . '" />';
		
		if( !empty( $image_weibo ) || !empty( $image_image ) )
			echo '</div><!-- .post-images -->';
		
		?>
		
		<?php get_template_part( 'single-content-toolbar' ); ?>
	</div><!-- #post -->
