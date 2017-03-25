jQuery( document ).ready( function( $ ) {
	/* Add offset when scrolling to an anchor from a table of contents link */
	var dest,
		headerHeight = $( 'div.vectorMenu#usermenu' ).height() + 10,
		// Split hash
		hash;

	$( '.toc ul a[href*="#"]' ).click( function( e ) {
		try {
			if ( this && this.href !== '' ) {
				// Split location and hash
				hash = this.href.match( /[#].*/g )[ 0 ],

				// Don't reload page if already at same location as last clicked
				$( 'html, body' ).animate( {
					scrollTop: $( hash ).offset().top - 41
				}, 200 );
			}
			e.preventDefault();
			return false;
		} catch ( e ) { }
	} );

	/* Add offset when scrolling to an anchor present at page load time */
	if ( $( ':target' ).length > 0 ) {
		dest = $( this ).offset().top - headerHeight;
		$( 'html, body' ).animate( {
			scrollTop: dest // also use scrollTop to animate scrolling
		} );
	}
} );
