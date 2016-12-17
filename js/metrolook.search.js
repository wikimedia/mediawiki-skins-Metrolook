( function ( $, mw ) {
	if ( mw.config.get( 'wgMetrolookSearch' ) ) {
		$( function () {

			function isTouchDevice() {
				return !!( 'ontouchstart' in window );
			}

			function isMobileUserAgent() {
				return !!( /mobi|alcatel|Android|android|webOS|webos|iPhone|iPod|Wii|Silk|BlackBerry|playstation|phone|nintendo|htc[-_]|IEMobile|CriOS|Opera Mini|opera.m|palm|panasonic|philips|samsung|Mobile|mobile/i.test( navigator.userAgent ) );
			}

			/* This is here to fix js issue with iPad (all models) */
			$( function () {
				if ( isTouchDevice() && isMobileUserAgent() ) {
					$( '#p-search' ).hide();
					$( 'img.searchbar' ).click( function ( e ) {
						$( '#p-search' ).fadeToggle( 150 );
						$( '.clicker' ).toggleClass( 'active' );
						e.stopPropagation();
					} );
					$( 'img.searchbar' ).click( function () {
						if ( $( '#p-search' ).is( ':visible' ) ) {
							$( '#p-search', this ).fadeOut( 150 );
							$( '.clicker' ).removeClass( 'active' );
						}
					} );
				}

				/* Fix search bar not showing on iPad */
				if ( /kindle|iPad|PlayBook|Tablet/i.test( navigator.userAgent ) ) {
					$( '#p-search' ).show();
				}
			} );
		} );
	}
}( jQuery, mediaWiki ) );
