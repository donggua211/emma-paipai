<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
		</div><!-- .container -->
		
		<div class="site-footer">
			Copyright © emma-paipai.com
		</div><!-- .site-footer -->
			
	</div><!-- .wrap-inner -->
</div><!-- .wrap -->

<script type="text/javascript" src="http://unionjs.dianxin.com/fm.js" name="cpv" data-said="44073" data-width="250" data-height="250" data-type="0" charset="utf-8" ></script>

<?php wp_footer(); ?>
<?php echo $wpdb->num_queries; ?> queries. 
<?php timer_stop(1); ?> seconds.
</body>
</html>