let wpm_modal = ( show = true ) => {
	if(show) {
		jQuery('#wp-to-epaymaker-modal').show();
	}
	else {
		jQuery('#wp-to-epaymaker-modal').hide();
	}
}

jQuery(function($){

	// $( "#nid_submit" ).on( 'click', function(e){
	$('#nid_submit').submit(function(e){
		e.preventDefault();
		let $name 	= $( '#wptp_name' ).val();
		let $f_name = $( '#wptp_f_name' ).val();
		let $nid  	= $( '#wptp_nid' ).val();
		wpm_modal(true);
		$.ajax({
			url: WP_TO_PAYMAKER.ajaxurl,
			data: {action : 'store_nid', 
				name 	: $name, 
				f_name 	: $f_name, 
				nid 	: $nid, 
			},
			type: 'POST',
			dataType: 'JSON',
			success: function(resp) {
				// $('#cf7s-contact-msg').text(resp.data.message);
				console.log( resp );
				wpm_modal(false);
			},
			error: function(err) {
				// $('#cf7s-contact-msg').text(err.data.message);
				wpm_modal(false);
			}
		});
			console.log( $f_name );
	});
})