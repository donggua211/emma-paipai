jQuery(document).ready( function($) {
	$('td.weibo-image-url input').change(function() {
		var img_name = $(this).attr('name');
		var img_url = $(this).val();
		if (img_name.indexOf("image_weibo") > -1) {
			img_url += '/120';
		}
		var parent = $(this).parent().parent();
		$(parent).find('.weibo-image-thumb img').attr( 'src', img_url );
	});
	
	
	$('button.remove_row').on('click',function() {
		var parent = $(this).parent().parent();
		parent.find('.image-url').effect( "highlight", {color : '#FF8247' }, 500, function() {parent.remove()} );
	});
	
	$('button.add_row').on('click',function() {
		var type = $( this ).attr('data-type');
		
		$('#weibo2wp_image_' + type + '_list').append('<tr>\
				<td class="weibo-image-thumb">\
					<img src="">\
				</td>\
				<td class="weibo-image-url">\
					' + weibo2wp_writepanel_params.image_url + ': <input type="text" name="image_' + type + '[]" value="">\
				</td>\
				<td class="weibo-image-button">\
					<button type="button" class="remove_row button">' + weibo2wp_writepanel_params.remove_button + '</button>\
				</td>\
			</tr>');
		
		$('#weibo2wp_image_' + type + '_list :last-child .weibo-image-url').effect( "highlight", {}, 500);
		$('#weibo2wp_image_' + type + '_list :last-child .weibo-image-url input').focus();
		
		
		$('#weibo2wp_image_' + type + '_list :last-child .weibo-image-button button').on('click',function() {
			var parent = $(this).parent().parent();
			parent.find('.image-url').effect( "highlight", {color : '#FF8247' }, 500, function() {parent.remove()} );
		});
		$('#weibo2wp_image_' + type + '_list :last-child .weibo-image-url input').change(function() {
			var img_name = $(this).attr('name');
			var img_url = $(this).val();
			if (img_name.indexOf("image_weibo") > -1) {
				img_url += '/120';
			}
			var parent = $(this).parent().parent();
			$(parent).find('.weibo-image-thumb img').attr( 'src', img_url );
		});
		
	});

});