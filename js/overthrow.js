/* ! overthrow - An overflow:auto polyfill for responsive design. - v1.2.0 - 2016-08-21
* Copyright (c) 2016 Scott Jehl, Filament Group, Inc.; Licensed MIT */
/* ! Overthrow. An overflow:auto polyfill for responsive design. (c) 2012: Scott Jehl, Filament Group, Inc. http://filamentgroup.github.com/Overthrow/license.txt */
( function ( w, undefined ) {

	var doc = w.document,
		docElem = doc.documentElement,
		enabledClassName = 'overthrow-enabled',

		// Touch events are used in the polyfill, and thus are a prerequisite
		canBeFilledWithPoly = 'ontouchmove' in doc,

		// The following attempts to determine whether the browser has native overflow support
		// so we can enable it but not polyfill
		nativeOverflow =
			// Features-first. iOS5 overflow scrolling property check - no UA needed here. thanks Apple :)
			'WebkitOverflowScrolling' in docElem.style ||
			// Test the windows scrolling property as well
			'msOverflowStyle' in docElem.style ||
			// Touch events aren't supported and screen width is greater than X
			// ...basically, this is a loose "desktop browser" check.
			// It may wrongly opt-in very large tablets with no touch support.
			( !canBeFilledWithPoly && w.screen.width > 800 ) ||
			// Hang on to your hats.
			// Whitelist some popular, overflow-supporting mobile browsers for now and the future
			// These browsers are known to get overlow support right, but give us no way of detecting it.
			( function () {
				var ua = w.navigator.userAgent,
					// Webkit crosses platforms, and the browsers on our list run at least version 534
					webkit = ua.match( /AppleWebKit\/([0-9]+)/ ),
					wkversion = webkit && webkit[ 1 ],
					wkLte534 = webkit && wkversion >= 534;

				return (
					/* Android 3+ with webkit gte 534
					~: Mozilla/5.0 (Linux; U; Android 3.0; en-us; Xoom Build/HRI39) AppleWebKit/534.13 (KHTML, like Gecko) Version/4.0 Safari/534.13 */
					ua.match( /Android ([0-9]+)/ ) && RegExp.$1 >= 3 && wkLte534 ||
					/* Blackberry 7+ with webkit gte 534
					~: Mozilla/5.0 (BlackBerry; U; BlackBerry 9900; en-US) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.0.0 Mobile Safari/534.11+ */
					ua.match( / Version\/([0-9]+)/ ) && RegExp.$1 >= 0 && w.blackberry && wkLte534 ||
					/* Blackberry Playbook with webkit gte 534
					~: Mozilla/5.0 (PlayBook; U; RIM Tablet OS 1.0.0; en-US) AppleWebKit/534.8+ (KHTML, like Gecko) Version/0.0.1 Safari/534.8+ */
					ua.indexOf( 'PlayBook' ) > -1 && wkLte534 && !ua.indexOf( 'Android 2' ) === -1 ||
					/* Firefox Mobile (Fennec) 4 and up
					~: Mozilla/5.0 (Mobile; rv:15.0) Gecko/15.0 Firefox/15.0 */
					ua.match( /Firefox\/([0-9]+)/ ) && RegExp.$1 >= 4 ||
					/* WebOS 3 and up (TouchPad too)
					~: Mozilla/5.0 (hp-tablet; Linux; hpwOS/3.0.0; U; en-US) AppleWebKit/534.6 (KHTML, like Gecko) wOSBrowser/233.48 Safari/534.6 TouchPad/1.0 */
					ua.match( /wOSBrowser\/([0-9]+)/ ) && RegExp.$1 >= 233 && wkLte534 ||
					/* Nokia Browser N8
					~: Mozilla/5.0 (Symbian/3; Series60/5.2 NokiaN8-00/012.002; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/533.4 (KHTML, like Gecko) NokiaBrowser/7.3.0 Mobile Safari/533.4 3gpp-gba
					~: Note: the N9 doesn't have native overflow with one-finger touch. wtf */
					ua.match( /NokiaBrowser\/([0-9\.]+)/ ) && parseFloat( RegExp.$1 ) === 7.3 && webkit && wkversion >= 533
				);
			}() );

	// Expose overthrow API
	w.overthrow = {};

	w.overthrow.enabledClassName = enabledClassName;

	w.overthrow.addClass = function () {
		if ( docElem.className.indexOf( w.overthrow.enabledClassName ) === -1 ) {
			docElem.className += ' ' + w.overthrow.enabledClassName;
		}
	};

	w.overthrow.removeClass = function () {
		docElem.className = docElem.className.replace( w.overthrow.enabledClassName, '' );
	};

	// Enable and potentially polyfill overflow
	w.overthrow.set = function () {

		// If nativeOverflow or at least the element canBeFilledWithPoly, add a class to cue CSS that assumes overflow scrolling will work (setting height on elements and such)
		if ( nativeOverflow ) {
			w.overthrow.addClass();
		}

	};

	// expose polyfillable
	w.overthrow.canBeFilledWithPoly = canBeFilledWithPoly;

	// Destroy everything later. If you want to.
	w.overthrow.forget = function () {

		w.overthrow.removeClass();

	};

	// Expose overthrow API
	w.overthrow.support = nativeOverflow ? 'native' : 'none';

}( this ) );

/* ! Overthrow. An overflow:auto polyfill for responsive design. (c) 2012: Scott Jehl, Filament Group, Inc. http://filamentgroup.github.com/Overthrow/license.txt */
/* overthrow.toss simply retains the overthrow.toss api by pulling in the external toss dependency and borring its methods */
( function ( w, t, undefined ) {

	// t is a reference to the external toss dependency
	if ( t === undefined ) {
		return;
	}

	// w.overthrow is overthrow reference from overthrow-polyfill.js
	if ( w.overthrow === undefined ) {
		w.overthrow = {};
	}

	w.overthrow.toss = t;
	w.overthrow.easing = t.easing;
	w.overthrow.tossing = t.tossing;
	w.overthrow.intercept = function ( elem ) {
		if ( !elem ) {
			return;
		}
		w.overthrow.tossing( elem, false );
	};

}( this, this.toss ) );

/* ! Overthrow. An overflow:auto polyfill for responsive design. (c) 2012: Scott Jehl, Filament Group, Inc. http://filamentgroup.github.com/Overthrow/license.txt */
( function ( w, o, undefined ) {

	// o is overthrow reference from overthrow-polyfill.js
	if ( o === undefined ) {
		return;
	}

	o.scrollIndicatorClassName = 'overthrow';

	var doc = w.document,
		docElem = doc.documentElement,
		// o api
		nativeOverflow = o.support === 'native',
		canBeFilledWithPoly = o.canBeFilledWithPoly,
		configure = o.configure,
		set = o.set,
		forget = o.forget,
		scrollIndicatorClassName = o.scrollIndicatorClassName;

	// find closest overthrow (elem or a parent)
	o.closest = function ( target, ascend ) {
		return !ascend && target.className && target.className.indexOf( scrollIndicatorClassName ) > -1 && target || o.closest( target.parentNode );
	};

	// polyfill overflow
	var enabled = false;
	o.set = function () {

		set();

		// If nativeOverflow or it doesn't look like the browser canBeFilledWithPoly, our job is done here. Exit viewport left.
		if ( enabled || nativeOverflow || !canBeFilledWithPoly ) {
			return;
		}

		w.overthrow.addClass();

		enabled = true;

		o.support = 'polyfilled';

		o.forget = function () {
			forget();
			enabled = false;
			// Remove touch binding (check for method support since this part isn't qualified by touch support like the rest)
			if ( doc.removeEventListener ) {
				doc.removeEventListener( 'touchstart', start, false );
			}
		};

		// Fill 'er up!
		// From here down, all logic is associated with touch scroll handling
		// elem references the overthrow element in use
		var elem,

			// The last several Y values are kept here
			lastTops = [],

			// The last several X values are kept here
			lastLefts = [],

			// lastDown will be true if the last scroll direction was down, false if it was up
			lastDown,

			// lastRight will be true if the last scroll direction was right, false if it was left
			lastRight,

			// For a new gesture, or change in direction, reset the values from last scroll
			resetVertTracking = function () {
				lastTops = [];
				lastDown = null;
			},

			resetHorTracking = function () {
				lastLefts = [];
				lastRight = null;
			},

			// On webkit, touch events hardly trickle through textareas and inputs
			// Disabling CSS pointer events makes sure they do, but it also makes the controls innaccessible
			// Toggling pointer events at the right moments seems to do the trick
			// Thanks Thomas Bachem http://stackoverflow.com/a/5798681 for the following
			inputs,
			setPointers = function ( val ) {
				inputs = elem.querySelectorAll( 'textarea, input' );
				for ( var i = 0, il = inputs.length; i < il; i++ ) {
					inputs[ i ].style.pointerEvents = val;
				}
			},

			// For nested overthrows, changeScrollTarget restarts a touch event cycle on a parent or child overthrow
			changeScrollTarget = function ( startEvent, ascend ) {
				if ( doc.createEvent ) {
					var newTarget = ( !ascend || ascend === undefined ) && elem.parentNode || elem.touchchild || elem,
						tEnd;

					if ( newTarget !== elem ) {
						tEnd = doc.createEvent( 'HTMLEvents' );
						tEnd.initEvent( 'touchend', true, true );
						elem.dispatchEvent( tEnd );
						newTarget.touchchild = elem;
						elem = newTarget;
						newTarget.dispatchEvent( startEvent );
					}
				}
			},

			// Touchstart handler
			// On touchstart, touchmove and touchend are freshly bound, and all three share a bunch of vars set by touchstart
			// Touchend unbinds them again, until next time
			start = function ( e ) {

				// Stop any throw in progress
				if ( o.intercept ) {
					o.intercept();
				}

				// Reset the distance and direction tracking
				resetVertTracking();
				resetHorTracking();

				elem = o.closest( e.target );

				if ( !elem || elem === docElem || e.touches.length > 1 ) {
					return;
				}

				setPointers( 'none' );
				var touchStartE = e,
					scrollT = elem.scrollTop,
					scrollL = elem.scrollLeft,
					height = elem.offsetHeight,
					width = elem.offsetWidth,
					startY = e.touches[ 0 ].pageY,
					startX = e.touches[ 0 ].pageX,
					scrollHeight = elem.scrollHeight,
					scrollWidth = elem.scrollWidth,

					// Touchmove handler
					move = function ( e ) {

						var ty = scrollT + startY - e.touches[ 0 ].pageY,
							tx = scrollL + startX - e.touches[ 0 ].pageX,
							down = ty >= ( lastTops.length ? lastTops[ 0 ] : 0 ),
							right = tx >= ( lastLefts.length ? lastLefts[ 0 ] : 0 );

						// If there's room to scroll the current container, prevent the default window scroll
						if ( ( ty > 0 && ty < scrollHeight - height ) || ( tx > 0 && tx < scrollWidth - width ) ) {
							e.preventDefault();
						}
						// This bubbling is dumb. Needs a rethink.
						else {
							changeScrollTarget( touchStartE );
						}

						// If down and lastDown are inequal, the y scroll has changed direction. Reset tracking.
						if ( lastDown && down !== lastDown ) {
							resetVertTracking();
						}

						// If right and lastRight are inequal, the x scroll has changed direction. Reset tracking.
						if ( lastRight && right !== lastRight ) {
							resetHorTracking();
						}

						// remember the last direction in which we were headed
						lastDown = down;
						lastRight = right;

						// set the container's scroll
						elem.scrollTop = ty;
						elem.scrollLeft = tx;

						lastTops.unshift( ty );
						lastLefts.unshift( tx );

						if ( lastTops.length > 3 ) {
							lastTops.pop();
						}
						if ( lastLefts.length > 3 ) {
							lastLefts.pop();
						}
					},

					// Touchend handler
					end = function ( e ) {

						// Bring the pointers back
						setPointers( 'auto' );
						setTimeout( function () {
							setPointers( 'none' );
						}, 450 );
						elem.removeEventListener( 'touchmove', move, false );
						elem.removeEventListener( 'touchend', end, false );
					};

				elem.addEventListener( 'touchmove', move, false );
				elem.addEventListener( 'touchend', end, false );
			};

		// Bind to touch, handle move and end within
		doc.addEventListener( 'touchstart', start, false );
	};

}( this, this.overthrow ) );

/* ! Overthrow. An overflow:auto polyfill for responsive design. (c) 2012: Scott Jehl, Filament Group, Inc. http://filamentgroup.github.com/Overthrow/license.txt */
( function ( w, undefined ) {

	// Auto-init
	w.overthrow.set();

}( this ) );
