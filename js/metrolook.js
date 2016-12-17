( function ( $ ) {
	$( function () {
		var openDiv;

		function toggleDiv( divID ) {
			$( '#' + divID ).fadeToggle( 150, function () {
				openDiv = $( this ).is( ':visible' ) ? divID : null;
			} );
		}

		$( document ).click( function ( e ) {
			if ( !$( e.target ).closest( '#' + openDiv ).length ) {
				toggleDiv( openDiv );
			}
		} );

		function isTouchDevice() {
			return !!( 'ontouchstart' in window );
		}

		function isMobileUserAgent() {
			return !!( /mobi|alcatel|Android|android|kindle|webOS|webos|iPhone|iPad|iPod|Tablet|PlayBook|Wii|Silk|BlackBerry|playstation|phone|nintendo|htc[-_]|IEMobile|CriOS|Opera Mini|opera.m|palm|panasonic|philips|samsung|Mobile|mobile/i.test( navigator.userAgent ) );
		}

		$( function () {
			if ( isTouchDevice() && isMobileUserAgent() ) {
				$( '#usermenu > div' ).toggleClass( 'no-js js' );
				$( '#usermenu .js div' ).hide();
				$( '#usermenu .js' ).click( function ( e ) {
					$( '#usermenu .js div' ).fadeToggle( 150 );
					$( '#usermenu' ).toggleClass( 'active' );
					e.stopPropagation();
				} );

				$( '.actionmenu > div' ).toggleClass( 'no-js js' );
				$( '.actionmenu .js div' ).hide();
				$( '.actionmenu .js' ).click( function ( e ) {
					$( '.actionmenu .js div' ).fadeToggle( 150 );
					$( '.clicker' ).toggleClass( 'active' );
					e.stopPropagation();
				} );

				$( document ).click( function () {
					if ( $( '.actionmenu .js div' ).is( ':visible' ) ) {
						$( '.actionmenu .js div', this ).fadeOut( 150 );
						$( '.clicker' ).removeClass( 'active' );
					}

					if ( $( '#usermenu .js div' ).is( ':visible' ) ) {
						$( '#usermenu .js div', this ).fadeOut( 150 );
						$( '#usermenu' ).removeClass( 'active' );
					}
				} );
			} // end mobile-only code

			$( '#hamburgerIcon' ).click( function ( e ) {
				$( '#mw-panel' ).fadeToggle( 150 );
				$( '.clicker' ).toggleClass( 'active' );
				if ( $( '#mw-panel' ).is( ':visible' ) ) {
					$( '#mw-panel', this ).fadeOut( 150 );
					$( '.clicker' ).removeClass( 'active' );
				}
				e.stopPropagation();
			} );

			$( 'img.editbutton' ).click( function ( e ) {
				$( '#left-navigation' ).fadeToggle( 150 );
				$( '.clicker' ).toggleClass( 'active' );
				if ( $( '#left-navigation' ).is( ':visible' ) ) {
					$( '#left-navigation', this ).fadeOut( 150 );
					$( '.clicker' ).removeClass( 'active' );
				}
				e.stopPropagation();
			} );

			$( 'img.downarrow' ).click( function ( e ) {
				toggleDiv( 'bartile' );
				if ( $( '#bartile' ).is( ':visible' ) ) {
					$( '#bartile', this ).fadeOut( 150 );
					$( '.clicker' ).removeClass( 'active' );
				}
				e.stopPropagation();
			} );

			// Listen to clicks (taps on mobile) to the black bar and if it was
			// clicked/tapped (instead of an individual tile), dismiss the menu.
			// This improves usability especially on lower-end mobile devices with
			// smaller screens.
			// Fixes https://phabricator.wikimedia.org/T105785
			$( '#tilegroup' ).not( '.tile-wrapper' ).on( 'click', function () {
				if ( $( '#bartile' ).is( ':visible' ) ) {
					toggleDiv( 'bartile' );
				}
			} );
		} );
	} );
}( jQuery ) );
