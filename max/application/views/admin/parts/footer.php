<?php if(!isset($header_silent) || $header_silent == FALSE): ?>

			</div><!-- #main-content -->
		</div><!-- #main .wrapper -->
	</div><!-- #layout -->
</div><!-- #wrap -->

<div class="footer clearfix">
	<a href="home.php">首页</a>
	<b>|</b>
	<a href="<?php echo site_url('admin/admin_user/logout'); ?>">退出</a>
	<span>Powered by 熊猫爸爸</span>
</div><!-- #footer -->
<?php endif; ?>

</body>
</html>