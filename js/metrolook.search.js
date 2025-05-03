( function () {
	if ( mw.config.get( 'wgMetrolookSearch' ) ) {
		$( () => {

			function isTouchDevice() {
				return !!( 'ontouchstart' in window );
			}

			function isMobileUserAgent() {
				return !!( /mobi|alcatel|Android|android|webOS|webos|iPhone|iPod|Wii|Silk|BlackBerry|playstation|phone|nintendo|htc[-_]|IEMobile|CriOS|Opera Mini|opera.m|palm|panasonic|philips|samsung|Mobile|mobile/i.test( navigator.userAgent ) );
			}

			/* This is here to fix js issue with iPad (all models) */
			$( () => {
				if ( isTouchDevice() && isMobileUserAgent() ) {
					$( '#p-search' ).hide();
					$( 'img.searchbar' ).on( 'click', ( e ) => {
						$( '#p-search' ).fadeToggle( 150 );
						$( '.clicker' ).toggleClass( 'active' );
						e.stopPropagation();
					} );
					$( 'img.searchbar' ).on( 'click', function () {
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
}() );
