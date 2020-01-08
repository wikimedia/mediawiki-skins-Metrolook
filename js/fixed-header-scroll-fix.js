jQuery( document ).ready( function ( $ ) {
	/* Add offset when scrolling to an anchor from a table of contents link */
	var dest,
		headerHeight = $( 'div.vectorMenu#usermenu' ).height() + 10,
		// Split hash
		hash;

	$( '.toc ul a[href*="#"]' ).click( function ( e ) {
		if ( this && this.href !== '' ) {
			// Split location and hash
			hash = $.escapeSelector( this.href.match( /[#](.*)/ )[ 1 ] );

			// Don't reload page if already at same location as last clicked
			$( '#' + hash ).each( function () {
				$( 'html, body' ).animate( {
					scrollTop: $( this ).offset().top - headerHeight
				}, 200 );
			} );
		}
		e.preventDefault();
		return false;
	} );

	/* Add offset when scrolling to an anchor present at page load time */
	$( ':target' ).each( function () {
		dest = $( this ).offset().top - headerHeight;
		$( 'html, body' ).animate( {
			scrollTop: dest // also use scrollTop to animate scrolling
		} );
	} );
} );
