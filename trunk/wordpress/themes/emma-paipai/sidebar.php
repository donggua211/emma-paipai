<?php
/**
 * The sidebar containing the main widget area.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>


<div class="widget-area">
	<a href="http://count.chanet.com.cn/click.cgi?a=515855&d=373368&u=&e=" target="_blank"><IMG SRC="http://file.chanet.com.cn/image.cgi?a=515855&d=373368&u=&e=" width="180" height="150"  border="0"></a>
</div>

<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
	<div id="secondary" class="widget-area">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</div><!-- #secondary -->
<?php endif; ?>