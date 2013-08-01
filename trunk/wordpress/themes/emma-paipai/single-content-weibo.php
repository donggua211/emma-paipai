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
		
		<div class="post-meta">
			<span><?php the_time( 'Y年n月j日 H:i' ) ?></span>
			
			<?php if ( comments_open() ) : ?>
				<span><?php comments_popup_link( __( '评论', 'emmapaipai' ), __( '评论(1)', 'emmapaipai' ), __( '评论(%)', 'emmapaipai' ) ); ?></span>
			<?php endif; // comments_open() ?>
			
			<?php edit_post_link( '编辑', '<span>', '</span>' ); ?>
			
			<?php if ( is_singular() && get_the_author_meta( 'description' ) && is_multi_author() ) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries. ?>
				<div class="author-info">
					<div class="author-avatar">
						<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'emmapaipai_author_bio_avatar_size', 68 ) ); ?>
					</div><!-- .author-avatar -->
					<div class="author-description">
						<h2><?php printf( __( 'About %s', 'emmapaipai' ), get_the_author() ); ?></h2>
						<p><?php the_author_meta( 'description' ); ?></p>
						<div class="author-link">
							<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
								<?php printf( __( 'View all posts by %s <span class="meta-nav">&rarr;</span>', 'emmapaipai' ), get_the_author() ); ?>
							</a>
						</div><!-- .author-link	-->
					</div><!-- .author-description -->
				</div><!-- .author-info -->
			<?php endif; ?>
		</div><!-- .post-meta -->
	</div><!-- #post -->
