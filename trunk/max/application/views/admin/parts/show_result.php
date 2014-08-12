<div class="main-content result-layout">
	<div class="result-wap">
		<?php 
		if(!empty($extra_html)) {
			echo $extra_html;
		}
		?>
		<div class="result">
			<p>请点击返回按钮: <input type="button" value="　返回　" onclick="window.location.href='<?php echo $back_url; ?>'"> </p>
		</div>
	</div>
</div>