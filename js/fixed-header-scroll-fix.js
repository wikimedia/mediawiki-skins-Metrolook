/* Add offset when scrolling to an anchor from a table of contents link */
$( '.toc ul a[href^=#]' ).on( 'click', function( e ) {
	var href = $( this ).attr( 'href' );
	var dest = $( href ).offset().top - $( 'div.vectorMenu#usermenu' ).height();
	window.history.pushState( {}, '', $( this ).prop( 'href' ) );
	$( 'html, body' ).scrollTop( dest );
	e.preventDefault();
} );

/* Add offset when scrolling to an anchor present at page load time */
if ( $( ':target' ).length > 0 ) {
	var d = $( ':target' ).offset().top - $( 'div.vectorMenu#usermenu' ).height();
	$( 'html, body' ).scrollTop( d );
}
