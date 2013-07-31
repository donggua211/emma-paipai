<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

	<div id="post-<?php the_ID(); ?>" <?php post_class('entry_wrap'); ?>>
		<div class="entry-header">
			<?php the_post_thumbnail(); ?>
			<?php if ( is_single() ) : ?>
				<?php the_title(); ?>
			<?php else : ?>
				<?php
				$weibo_id 	= get_post_meta( get_the_ID(), '_weibo_id', true );
				if ( empty( $weibo_id ) ) :
				?>
					<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'emmapaipai' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
				<?php else : ?>
					<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'emmapaipai' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_author(); ?></a>
				<?php endif; // empty( $weibo_id ) ?>
			<?php endif; // is_single() ?>
		</div><!-- .entry-header -->

		
		<div class="entry-content">
			<?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'emmapaipai' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'emmapaipai' ), 'after' => '</div>' ) ); ?>
		
		</div><!-- .entry-content -->
		
		<?php
		$image_weibo 	= get_post_meta( get_the_ID(), '_weibo_image', true );
		$image_image 	= get_post_meta( get_the_ID(), '_image_image', true );
		
		$image_weibo = $image_weibo ? unserialize( $image_weibo ) : array();
		$image_image = $image_image ? unserialize( $image_image ) : array();
		
		if( !empty( $image_weibo ) || !empty( $image_image ) )
			echo '<div id="gallery" class="gallery entry-images">';
		
		foreach($image_weibo as $val)
			echo '<a href="' . $val . '/2000" rel="prettyPhoto'.get_the_ID().'[pp_gal]" > <img src="' . $val . '/160" /></a>';
		
		foreach($image_image as $val)
			echo '<a href="' . $val . '" rel="prettyPhoto'.get_the_ID().'[pp_gal]" > <img src="' . $val . '" /></a>';
		
		if( !empty( $image_weibo ) || !empty( $image_image ) )
			echo '</div><!-- .entry-images -->';
		
		?>
		
		<div class="entry-meta">
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
		</div><!-- .entry-meta -->
	</div><!-- #post -->
