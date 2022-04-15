$( document ).ready( function() {

    const error       = $( ".error" );
    const loginNotice = $( ".login-notice" );
    const resetNotice = $( ".reset-notice" );

	console.log( 'FADETIME' , FADETIME);

	loginNotice.hide();
	error.hide();
	resetNotice.hide();

	//--- LOGIN
	$("#submit-button-login").on( 'click', function() {

		console.log( this );
		$(".login-form").submit( () => false );

		$.post( "login-data.php", {
            'username'    : $( "#input-username" ).val(),
            'password'    : $( "#input-password" ).val(),
            'remember-me' : $( "#input-remember" ).val()
		},( data ) => {
			if( data == 'ok' ) {
				window.location.href = "main.php";
			} else {
				loginNotice.html( data );
				loginNotice.fadeIn( FADETIME );
			}
		});
	});

	$( "#login-button" ).on( 'click', () => {
		// $( ".index-wrap" ).hide();
		// TODO: switch to first tab
		$( ".login-wrap" ).fadeIn( FADETIME );
	});

	$( "#forgot-pass-link" ).on( 'click', () => {
		// $( ".index-wrap" ).hide();
		// TODO: switch to second tab
		$( ".login-wrap" ).hide();
		$( ".reset-wrap" ).fadeIn( FADETIME );
	});


	// ---RESET PASSWORD
	$( "#reset-submit" ).on( 'click', () => {
		$( ".reset-form" ).submit( () => false );

		$.post( "reset-password.php", {
            'email' : $( "#reset-email" ).val(),
            'flag'  : 'user'
		},( data ) => {
			resetNotice.fadeIn( FADETIME );
			resetNotice.html(`<p>${data}</p>`);
		});
	});

	// SUBMIT REGISTER
	$( "#submit-button" ).on( 'click', () => {
		$( ".register-form" ).submit( () => false);

		$.post( "register-data.php", {
            'username'  : $( "#username-register" ).val(),
            'email'     : $( "#email-register" ).val(),
            'password'  : $( "#password-register" ).val(),
            'rpassword' : $( "#rpassword-register" ).val()
		}, ( data ) => {
			error.fadeIn( FADETIME );
			error.html( "" );
			for( const i in data ) {
				error.append( `${data[i]}<br>` );
			}
		}, "json" );
	});

	// CHANGE LANGUAGE
	$( ".language-wrap button" ).on( 'click', function() {
		$.post( "changeLang.php", {"lang" : $( this ).attr( "id" )}, () => {
			location.reload();
		});
	});

	// $( '#image1' ).magnificPopup({
	// 	items: {
	// 		src: './img/image1.png'
	// 	},
	// 	type: 'image'
	// });

	// $( '#image2' ).magnificPopup({
	// 	items: {
	// 		src: './img/image2.png'
	// 	},
	// 	type: 'image'
	// });

	// $( '#image3' ).magnificPopup({
	// 	items: {
	// 		src: './img/image3.png'
	// 	},
	// 	type: 'image'
	// });

});

// TABCORDION
(function() {
	'use strict';

	const keyboardSupport = function( container, hasTabs ) {
		const tablist = container.querySelectorAll( '[role="tablist"]' )[0];
		let tabs;
		let panels;

		const generateArrays = function() {
			panels = container.querySelectorAll( '[role="tabpanel"]' );
			tabs = container.querySelectorAll( '[role="tab"]' );
		};

		generateArrays();

		// For easy reference
		const keys = {
			end: 35,
			home: 36,
			left: 37,
			up: 38,
			right: 39,
			down: 40,
			delete: 46,
			enter: 13,
			space: 32
		};

		// Add or subtract depending on key pressed
		const direction = {
			37: -1,
			38: -1,
			39: 1,
			40: 1
		};

		// Deactivate all tabs and tab panels
		const deactivateTabs = function() {
			for( let t = 0; t < tabs.length; t++ ) {
				tabs[t].setAttribute( 'tabindex', '-1' );
				tabs[t].setAttribute( 'aria-selected', 'false' );
			}
		};

		// Activates any given tab panel
		const activateTab = function( tab, setFocus ) {
			setFocus = setFocus || true;

			// Deactivate all other tabs
			deactivateTabs();

			// Remove tabindex attribute
			tab.removeAttribute( 'tabindex' );

			// Set the tab as selected
			tab.setAttribute( 'aria-selected', 'true' );

			// Set focus when required
			if( setFocus ) {
				tab.focus();
			}
		};

		const triggerTabClick = function( e ) {
			const clickedId = e.target.getAttribute( 'id' );
			if( clickedId ) {
				const clickedTab = container.querySelector( '[aria-controls="' + clickedId + '"]' );
				clickedTab.click();
				document.getElementById( clickedId ).scrollIntoView( {
					behavior: 'smooth'
				} );
			}
		};

		const accordionClickevtListener = function( evt ) {
			triggerTabClick( evt );
		};

		// When a tab is clicked, activateTab is fired to activate it
		const clickevtListener = function( evt ) {
			const tab = evt.target;
			activateTab( tab, false );
		};

		// Make a guess
		const focusFirstTab = function() {
			const target = hasTabs ? tabs : panels;
			target[0].focus();
		};

		// Make a guess
		const focusLastTab = function() {
			const target = hasTabs ? tabs : panels;
			target[target.length - 1].focus();
		};

		// Either focus the next, previous, first, or last tab
		// depending on key pressed
		const switchTabOnArrowPress = function( evt ) {
			const pressed = evt.keyCode;

			if( direction[pressed] ) {
				const target = evt.target;
				const targetElems = hasTabs ? tabs : panels;
				if( target.index !== undefined ) {
					if( targetElems[target.index + direction[pressed]] ) {
						targetElems[target.index + direction[pressed]].focus();
					} else if( pressed === keys.left || pressed === keys.up ) {
						focusLastTab();
					} else if( pressed === keys.right || pressed == keys.down ) {
						focusFirstTab();
					}
				}
			}
		};

		// When a tablist's aria-orientation is set to vertical,
		// only up and down arrow should function.
		// In all other cases only left and right arrow function.
		const determineOrientation = function( evt ) {
			const key = evt.keyCode;
			const vertical = tablist ? tablist.getAttribute( 'aria-orientation' ) === 'vertical' : null;
			let proceed = false;

			if ( vertical || !hasTabs) {
				if ( key === keys.up || key === keys.down ) {
					evt.prevtDefault();
					proceed = true;
				}
			} else {
				if (key === keys.left || key === keys.right ) {
					proceed = true;
				}
			}

			if( proceed ) {
				switchTabOnArrowPress( evt );
			}
		};

		// Handle keydown on tabs
		const keydownevtListener = function( evt ) {
			const key = evt.keyCode;
			switch( key ) {
				case keys.end:
					evt.prevtDefault();
					// Activate last tab
					focusLastTab();
				break;
				case keys.home:
					evt.prevtDefault();
					// Activate first tab
					focusFirstTab();
				break;

				// Up and down are in keydown
				// because we need to prevt page scroll >:)
				case keys.up:
				case keys.down:
					determineOrientation( evt );
				break;
			}
		};

		// Handle keyup on tabs
		const keyupevtListener = function( evt ) {
			const key = evt.keyCode;
			switch( key ) {
				case keys.left:
				case keys.right:
					determineOrientation( evt );
					break;
				case keys.enter:
				case keys.space:
					if( hasTabs ) {
						activateTab( evt.target );
					} else {
						triggerTabClick( evt );
					}
				break;
			}
		};

		const addListeners = function( index ) {
		const target = hasTabs ? tabs[index] : panels[index];
		tabs[index].addevtListener( 'click', clickevtListener );

		if( target ) {
			if( !hasTabs ) {
				target.addevtListener( 'click', accordionClickevtListener );
			}
			target.addevtListener( 'keydown', keydownevtListener );
			target.addevtListener( 'keyup', keyupevtListener );
			// Build an array with all tabs (<button>s) in it
			target.index = index;
		}
		};

		// Bind listeners
		for( let i = 0; i < tabs.length; ++i ) {
			addListeners( i );
		}

		// Accordion mode
		if( !hasTabs ) {
			for( const panel of panels ) {
				panel.onclick = function( e ) {
					triggerTabClick( e );
				};
			}
		}

	}; // /ende keyboard handling

	const toggleClass = function( otherElems, thisELem, className = 'is-active' ) {
		for( const otherElem of otherElems ) {
			otherElem.classList.remove( className );
		}
		thisELem.classList.add(className);
	};

	const toggleVerticalTabs = function( tabContainer, tabs, items, item ) {
		item.onclick = function( e ) {
			const currId = item.getAttribute( 'id' );
			const tab = tabContainer.querySelector( '.tabcordion--tabs [aria-controls="' + currId + '"]' );
			toggleClass( tabs, tab);
			toggleClass( items, item );
		};
	};

	const toggleTabs = function( tabContainer ) {
		const tabs = tabContainer.querySelectorAll( '.tabcordion--tabs .tab' );
		const items = tabContainer.querySelectorAll( '.tabcordion--entry' );
		for( const tab of tabs) {
			tab.onclick = function() {
				const target = tab.getAttribute( 'aria-controls' );
				const content = document.getElementById( target );
				toggleClass( tabs, tab);
				toggleClass( items, content);
			};
		}
		for( const item of items) {
			toggleVerticalTabs(tabContainer, tabs, items, item );
		}
	};

	const hasTabs = function(container) {
		return container.classList.contains( 'has-tabs' );
	};

	const modeSwitcher = function( tabContainer, containerWidth) {
		const tabs = tabContainer.querySelectorAll( '.tab' );
		const container = tabs[0].closest( '.tabcordion' );
		let totalW = 0;

		for( const tab of tabs) {
			totalW += tab.offsetWidth;
		}
		if (totalW >= containerWidth) {
			container.classList.remove( 'has-tabs' );
		} else {
			container.classList.add( 'has-tabs' );
		}
		keyboardSupport(tabContainer, hasTabs(container));
	};

	const resizeObserver = new ResizeObserver(entries => {
		for( let entry of entries) {
			modeSwitcher(entry.target, entry.contentRect.width);
		}
	});

	const tabContainers = document.querySelectorAll( '.tabcordion' );
	for( const tabContainer of tabContainers) {
		const tabList = tabContainer.querySelector( '.tabcordion--tabs' );
		resizeObserver.observe(tabList);
		toggleTabs(tabContainer);
		keyboardSupport(tabContainer, hasTabs(tabContainer));
	}
})();