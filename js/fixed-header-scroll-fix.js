/* Add offset when scrolling to an anchor from a table of contents link */
var href = $( this ).attr( 'href' ),
	dest = $( href ).offset().top - $( 'div.vectorMenu#usermenu' ).height(),
	d = $( ':target' ).offset().top - $( 'div.vectorMenu#usermenu' ).height();

$( '.toc ul a[href^=#]' ).on( 'click', function( e ) {
	window.history.pushState( {}, '', $( this ).prop( 'href' ) );
	$( 'html, body' ).scrollTop( dest );
	e.preventDefault();
} );

/* Add offset when scrolling to an anchor present at page load time */
if ( $( ':target' ).length > 0 ) {
	$( 'html, body' ).scrollTop( d );
}
