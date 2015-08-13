/* global $ */

function isTouchDevice() {
	return !!('ontouchstart' in window);
}

/* This is here to fix js issue with iPad (all models) */
$(function () {
	if( isTouchDevice() ) {
		if( /Android|webOS|iPhone|iPod|BlackBerry|IEMobile|CriOS|Opera Mini|Mobile|mobile/i.test(navigator.userAgent) ) {
			$( '#p-search' ).hide();
			$( 'img.searchbar' ).click(function(e) {
				$( '#p-search' ).fadeToggle(150);
				$('.clicker').toggleClass( 'active' );
				e.stopPropagation();
			});
			$( 'img.searchbar').click(function() {
				if ($( '#p-search' ).is( ':visible' )) {
					$( '#p-search', this).fadeOut(150);
					$( '.clicker' ).removeClass( 'active' );
				}
			});
		}
	}
});
