//Onload event
$(function() {
	//Header Nav
	$( "#navItem_" + nav_id ).addClass( "selected" );
	
	$( "#J_nav li" ).hover(
		function() {
			var id = $( this ).attr('id');
			id = id.charAt(id.length-1);
			
			if(id != nav_id) {
				$( this ).addClass( "selected" );
			}
			$( "#navSub_" + id ).removeClass( "hidden" );
		}, function() {
			var id = $( this ).attr('id');
			id = id.charAt(id.length-1);
			
			if(id != nav_id) {
				$( this ).removeClass( "selected" );
			}
			$( "#navSub_" + id ).addClass( "hidden" );
		}
	);
	
	$( ".nav_sub" ).hover(
		function() {
			var id = $( this ).attr('id');
			id = id.charAt(id.length-1);
			
			if(id != nav_id) {
				$( "#navItem_" + id ).addClass( "selected" );
			}
			$( this ).removeClass( "hidden" );
		}, function() {
			var id = $( this ).attr('id');
			id = id.charAt(id.length-1);
			
			if(id != nav_id) {
				$( "#navItem_" + id ).removeClass( "selected" );
			}
			
			$( this ).addClass( "hidden" );
		}
	);
});