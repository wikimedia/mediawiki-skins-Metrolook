/**
 * Metrolook-specific scripts
 */
$( () => {

	/**
	 * Collapsible tabs
	 */
	let $cactions = $( '#p-cactions' ),
		$tabContainer = $( '#p-views ul' ),
		// Avoid forced style calculation during page load
		initialCactionsWidth = function () {
			const width = $cactions.width();
			initialCactionsWidth = function () {
				return width;
			};
			return width;
		};

	requestAnimationFrame( initialCactionsWidth );

	/**
	 * Dropdown menu accessibility
	 */
	$( 'div.vectorMenu' ).each( function () {
		const $el = $( this );
		$el.find( '> h5 > span' ).parent()
			.attr( 'tabindex', '0' )
			// For accessibility, show the menu when the h3 is clicked (bug 24298/46486)
			.on( 'click keypress', ( e ) => {
				if ( e.type === 'click' || e.which === 13 ) {
					$el.toggleClass( 'menuForceShow' );
					e.preventDefault();
				}
			} )
			// When the heading has focus, also set a class that will change the arrow icon
			.on( 'focus', () => {
				$el.addClass( 'vectorMenuFocus' );
			} )
			.on( 'blur', () => {
				$el.removeClass( 'vectorMenuFocus' );
			} );
	} );

	// Bind callback functions to animate our drop down menu in and out
	// and then call the collapsibleTabs function on the menu
	$tabContainer
		.on( 'beforeTabCollapse', () => {
			// If the dropdown was hidden, show it
			if ( $cactions.hasClass( 'emptyPortlet' ) ) {
				$cactions.removeClass( 'emptyPortlet' );
				$cactions.find( 'h5' )
					.css( 'width', '1px' )
					.animate( { width: initialCactionsWidth() }, 'normal' );
			}
		} )
		.on( 'beforeTabExpand', () => {
			// If we're removing the last child node right now, hide the dropdown
			if ( $cactions.find( 'li' ).length === 1 ) {
				$cactions.find( 'h5' ).animate( { width: '1px' }, 'normal', function () {
					$( this ).attr( 'style', '' )
						.parent().addClass( 'emptyPortlet' );
				} );
			}
		} )
		.collapsibleTabs( {
			expandCondition: function ( eleWidth ) {
				// (This looks a bit awkward because we're doing expensive queries as late as possible.)

				const distance = $.collapsibleTabs.calculateTabDistance();
				// If there are at least eleWidth + 1 pixels of free space, expand.
				// We add 1 because .width() will truncate fractional values but .offset() will not.
				if ( distance >= eleWidth + 1 ) {
					return true;
				} else {
					// Maybe we can still expand? Account for the width of the "Actions" dropdown if the
					// expansion would hide it.
					if ( $cactions.find( 'li' ).length === 1 ) {
						return distance >= eleWidth + 1 - initialCactionsWidth();
					} else {
						return false;
					}
				}
			},
			collapseCondition: function () {
				// (This looks a bit awkward because we're doing expensive queries as late as possible.)
				// TODO The dropdown itself should probably "fold" to just the down-arrow (hiding the text)
				// if it can't fit on the line?

				// If there's an overlap, collapse.
				if ( $.collapsibleTabs.calculateTabDistance() < 0 ) {
					// But only if the width of the tab to collapse is smaller than the width of the dropdown
					// we would have to insert. An example language where this happens is Lithuanian (lt).
					if ( $cactions.hasClass( 'emptyPortlet' ) ) {
						return $tabContainer.children( 'li.collapsible:last' ).width() > initialCactionsWidth();
					} else {
						return true;
					}
				} else {
					return false;
				}
			}
		} );
} );
