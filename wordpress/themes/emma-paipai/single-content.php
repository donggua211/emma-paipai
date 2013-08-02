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
		<div class="post-header">
			<div class="post-title">
				<strong><span><?php the_title(); ?></span></strong>
			</div>
		</div><!-- .post-header -->
		
		<?php get_template_part( 'single-content-toolbar' ); ?>
		
		<div class="post-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'emmapaipai' ), 'after' => '</div>' ) ); ?>
		
		</div><!-- .post-content -->
		
		<?php get_template_part( 'single-content-toolbar' ); ?>
		
	</div><!-- #post -->
