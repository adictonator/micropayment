jQuery( function( $ ) {
	$( 'input[name="mode"]' ).on( 'change', function() {
		if ( $( this ).is( ':checked' ) ) {
			$( '[data-mp-stripe-keys="live"]' ).hide()
			$( '[data-mp-stripe-keys="test"]' ).show()
		} else {
			$( '[data-mp-stripe-keys="test"]' ).hide()
			$( '[data-mp-stripe-keys="live"]' ).show()
		}
	} )
} )