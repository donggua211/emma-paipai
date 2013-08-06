<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<div class="site-content-full">

		<?php while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID(); ?>" <?php post_class('entry-gallery-wrap'); ?>>
				<?php
				$images = get_post_meta( get_the_ID(), '_images', true );
				$images = $images ? unserialize( $images ) : array();

				if( !empty( $images ) ) :
				?>
				 <div id="gallery" class="ad-gallery" style="margin-left: 30px;">
					<div class="ad-image-wrapper"></div>
					<div class="ad-nav">
						<div class="ad-thumbs">
							<ul class="ad-thumb-list">
							<?php
							foreach($images as $val)
							{
								echo '
								<li>
									<a href="'.$val['url'].'">
										<img src="'.$val['url'].'" style="max-height: 40px; max-width: 60px;">
									</a>
								</li>';
							}
							?>
							
							</ul>
						</div>
					</div>
				</div><!-- .post-content -->
				
				<div id="descriptions">

				</div>
				<?php endif; ?>
				
			</div><!-- #post -->

			<?php comments_template( '', true ); ?>

		<?php endwhile; // end of the loop. ?>
	</div><!-- #primary -->
<?php get_footer(); ?>