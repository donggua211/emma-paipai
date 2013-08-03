jQuery(document).ready( function($) {
	$('td.image-url input').change(function() {
		var img_name = $(this).attr('name');
		var img_url = $(this).val();
		var parent = $(this).parent().parent();
		$(parent).find('.image-thumb img').attr( 'src', img_url );
	});
	
	
	$('button.remove_row').on('click',function() {
		var parent = $(this).parent().parent();
		parent.find('.image-url').effect( "highlight", {color : '#FF8247' }, 500, function() {parent.remove()} );
	});
	
	$('button.add_row').on('click',function() {

		$('#ez_gallery_image_list').append('<tr>\
				<td class="image-thumb">\
					<img src="">\
				</td>\
				<td class="image-url">\
					' + ez_gallery_writepanel_params.image_url + ': <input type="text" name="images[]" value="">\
				</td>\
				<td class="image-button">\
					<button type="button" class="remove_row button">' + ez_gallery_writepanel_params.remove_button + '</button>\
				</td>\
			</tr>');
		
		$('#ez_gallery_image_list :last-child .image-url').effect( "highlight", {}, 500);
		$('#ez_gallery_image_list :last-child .image-url input').focus();
		
		
		$('#ez_gallery_image_list :last-child .image-button button').on('click',function() {
			var parent = $(this).parent().parent();
			parent.find('.image-url').effect( "highlight", {color : '#FF8247' }, 500, function() {parent.remove()} );
		});
		$('#ez_gallery_image_list :last-child .image-url input').change(function() {
			var img_name = $(this).attr('name');
			var img_url = $(this).val();
			var parent = $(this).parent().parent();
			$(parent).find('.image-thumb img').attr( 'src', img_url );
		});
		
	});

});