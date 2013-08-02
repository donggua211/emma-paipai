<?php
/**
 * The default template for displaying content. Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

	<div id="post-<?php the_ID(); ?>" <?php post_class('entry-wrap'); ?>>
		<div class="entry-header">
			<?php the_post_thumbnail(); ?>
			<?php if ( is_weibo() ) : ?>
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_author(); ?></a>
			<?php else : ?>
				<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
			<?php endif; // is_weibo() ?>
		</div><!-- .entry-header -->

		
		<div class="entry-content">
			<?php echo utf8_substr( get_the_content(), 300, '...<a href="'.get_permalink().'" title="阅读全文" class="read-more">阅读全文</a>' ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'emmapaipai' ), 'after' => '</div>' ) ); ?>
		
		</div><!-- .entry-content -->
		
		<?php
		$image_weibo 	= get_post_meta( get_the_ID(), '_weibo_image', true );
		$image_image 	= get_post_meta( get_the_ID(), '_image_image', true );
		
		$image_weibo = $image_weibo ? unserialize( $image_weibo ) : array();
		$image_image = $image_image ? unserialize( $image_image ) : array();
		
		if( !empty( $image_weibo ) || !empty( $image_image ) )
			echo '<div class="entry-images">';
		
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
			
		</div><!-- .entry-meta -->
	</div><!-- #post -->
