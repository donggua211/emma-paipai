<div class="post-sub-title">
	<div class="post-toolbar">
		<ul>
			<li><?php the_time( 'Y年n月j日 H:i' ); ?><span class="sep">|</span></li>
			<?php if( !is_weibo() ): ?>
			<li><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>" title="<?php echo esc_attr( the_title_attribute( 'echo=0' ) ); ?>"><?php the_author(); ?></a><span class="sep">|</span></li>
			<?php endif; ?>
			<?php if ( comments_open() ) : ?>
			<li>
				<?php comments_popup_link( __( '评论', 'emmapaipai' ), __( '评论(1)', 'emmapaipai' ), __( '评论(%)', 'emmapaipai' ) ); ?><span class="sep">|</span>
			</li>
			<?php endif; // comments_open() ?>
			<li class="last"><?php edit_post_link( '编辑', '<span>', '</span>' ); ?><span class="sep">|</span></li>
		</ul>
	</div>
	<div class="post-nav-single">
		<?php previous_post_link( '%link', '上一篇' ); ?>
		
		<?php if( get_adjacent_post( false, '', true ) && get_adjacent_post( false, '', false ) ): ?>
			<span class="sep">|</span>
		<?php endif; ?>
		
		<?php next_post_link( '%link', '下一篇: %title' ); ?>
	</div>
</div>