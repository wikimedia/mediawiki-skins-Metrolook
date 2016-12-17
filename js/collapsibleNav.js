/**
 * Collapsible navigation for Metrolook
 */
( function ( mw, $ ) {
	'use strict';
	var map;

	// Use the same function for all navigation headings - don't repeat
	function toggle( $element ) {
		var isCollapsed = $element.parent().is( '.collapsed' );

		$.cookie(
			'metrolook-nav-' + $element.parent().attr( 'id' ),
			isCollapsed,
			{ expires: 30, path: '/' }
		);

		$element
			.parent()
			.toggleClass( 'expanded' )
			.toggleClass( 'collapsed' )
			.find( '.body' )
			.slideToggle( 'fast' );
		isCollapsed = !isCollapsed;

		$element
			.find( '> a' )
			.attr( {
				'aria-pressed': isCollapsed ? 'false' : 'true',
				'aria-expanded': isCollapsed ? 'false' : 'true'
			} );
	}

	/* Browser Support */

	map = {
		// Left-to-right languages
		ltr: {
			// Collapsible Nav is broken in Opera < 9.6 and Konqueror < 4
			opera: [ [ '>=', 9.6 ] ],
			konqueror: [ [ '>=', 4.0 ] ],
			blackberry: false,
			ipod: [ [ '>=', 6 ] ],
			iphone: [ [ '>=', 6 ] ],
			ps3: false
		},
		// Right-to-left languages
		rtl: {
			opera: [ [ '>=', 9.6 ] ],
			konqueror: [ [ '>=', 4.0 ] ],
			blackberry: false,
			ipod: [ [ '>=', 6 ] ],
			iphone: [ [ '>=', 6 ] ],
			ps3: false
		}
	};
	if ( !$.client.test( map ) ) {
		return true;
	}

	$( function ( $ ) {
		var $headings;

		/* General Portal Modification */

		// Apply a class to the entire panel to activate styles
		$( '#mw-panel' ).addClass( 'collapsible-nav' );
		// Use cookie data to restore preferences of what to show and hide
		$( '#mw-panel > .portal:not(.persistent)' )
			.each( function ( i ) {
				var id = $( this ).attr( 'id' ),
					state = $.cookie( 'metrolook-nav-' + id );
				$( this ).find( 'ul:first' ).attr( 'id', id + '-list' );
				// Add anchor tag to heading for better accessibility
				$( this ).find( 'h5' ).wrapInner(
					$( '<a>' )
						.attr( {
							href: '#',
							'aria-haspopup': 'true',
							'aria-controls': id + '-list',
							role: 'button'
						} )
						.click( false )
				);
				// In the case that we are not showing the new version, let's show the languages by default
				if (
					state === 'true' ||
					( state === null && i < 1 ) ||
					( state === null && id === 'p-lang' )
				) {
					$( this )
						.addClass( 'expanded' )
						.removeClass( 'collapsed' )
						.find( '.body' )
						.hide() // bug 34450
						.show();
					$( this ).find( 'h5 > a' )
						.attr( {
							'aria-pressed': 'true',
							'aria-expanded': 'true'
						} );
				} else {
					$( this )
						.addClass( 'collapsed' )
						.removeClass( 'expanded' );
					$( this ).find( 'h5 > a' )
						.attr( {
							'aria-pressed': 'false',
							'aria-expanded': 'false'
						} );
				}
				// Re-save cookie
				if ( state !== null ) {
					$.cookie( 'metrolook-nav-' + $( this ).attr( 'id' ), state, { expires: 30, path: '/' } );
				}
			} );

		/* Tab Indexing */

		$headings = $( '#mw-panel > .portal:not(.persistent) > h5' );

		// Make it keyboard accessible
		$headings.attr( 'tabindex', '0' );

		// Toggle the selected menu's class and expand or collapse the menu
		$( '#mw-panel' )
			.delegate( '.portal:not(.persistent) > h5', 'keydown', function ( e ) {
				// Make the space and enter keys act as a click
				if ( e.which === 13 /* Enter */ || e.which === 32 /* Space */ ) {
					toggle( $( this ) );
				}
			} )
			.delegate( '.portal:not(.persistent) > h5', 'mousedown', function ( e ) {
				if ( e.which !== 3 ) { // Right mouse click
					toggle( $( this ) );
					$( this ).blur();
				}
				return false;
			} );
	} );
}( mediaWiki, jQuery ) );
