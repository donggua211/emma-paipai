<div class="layout">
	<div class="header">
		<div class="header-bd">
			<div class="logo">
				<h1 class="site-title"><a href="home.php" rel="home">Max美国代购管理</a></h1>
			</div>
		</div>
	</div><!-- #header -->
	
	<div>
		<form name="form1" method="post" action="<?php echo current_url(); ?>">
		<div class="login_base">
			
			<?php if(isset($message) && !empty($message)): ?>
			<div class="main-content">
				<div class="msg msg-full">
					<?php
						foreach($message as $type => $val) {
							if(!is_array($val)) {
								$val = array($val);
							}
							
							foreach($val as $msg) {
								echo '<p class="'.$type.'">'.$msg.'</p>';
							}
						}
					?>
				</div>
			</div>
			<?php endif;?>
			
			<table width="480" border="0" cellspacing="0" cellpadding="0" summary="login" class="login">
				<tbody><tr>
					<td class="login_01"><p>用户名:</p></td>
					<td class="login_02">
					<p><input name="user_name" type="text" value="<?php echo !empty($user_name) ? $user_name : ''; ?>" /></p></td>
				</tr>
				<tr>
					<td class="login_01"><p>密码:</p></td>
					<td class="login_02">
					<p><input name="password" type="password" value="" /></p></td>
				</tr>
				<tr>
					<td colspan="2">
						<div class="submit"><input type="submit" name="submit" value="　Login　"></div>
					</td>
				</tr>
				</tbody>
			</table>
		</div>
		<input type="hidden" name="uri" value="<?php echo $uri; ?>">
		</form>
	</div>
</div>

<div class="footer clearfix">
	<span>Powered by 熊猫爸爸</span>
</div><!-- #footer -->