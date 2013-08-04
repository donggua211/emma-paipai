( function( $ ) {

    // Initialize the Image Gallery widget:
    $('.entry-images a').prettyPhoto({theme:'light_square',show_title: false, social_tools: ''});

	var galleries = $('.ad-gallery').adGallery({
		width: 800,
		height: 600,
		animate_first_image: true
	});
	
} )( jQuery );