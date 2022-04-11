window.lang = document.documentElement.lang;
window.tagTemp = "";
window.positionCheck = "groups";

const FADETIME = 420;

$( document ).ready( () => {

	// #region - FUN STUFF ---------------------------------------- //

	const logTitlePrimary = 'color: #ff8300; font-size: 13px; font-weight: bold; padding: 10px 5px 0; border-left:3px solid #ff8300; ';
	const logTitleWhite = 'color: #fff; font-size: 13px; font-weight: bold; padding: 10px 5px 0; border-left:3px solid #ff8300; ';
	const logContent = 'color: #ff8300; font-size: 13px; padding: 5px; border-left:4px solid #ff8300;';
	const logEnd = 'color: #ff8300; font-size: 13px; padding: 5px; border-left:4px solid #ff8300;';
	const logPrimaryBG = 'color: #fff; background-color: rgba(234, 131, 0, .42); font-size: 14px; padding: 6px 16px; border-left:6px solid #ff8300;';
	const logSecondaryBG = 'color: #fff; background-color: rgba(131, 0, 0, .42); font-size: 14px; padding: 6px 16px; border-left:6px solid #830000;';
	const clearStyles = '';

	// --- set stupid console message
	console.log( '%c 📋 -> 🗃 Welcome to RawSnippets 🐌 ----------------------------------------- 🎇', logTitlePrimary );
	console.log( '%c 🎉 the horribly outdated, weird snippet management system ✨',  logContent );
	console.log( '%c ..."sort of" maintained by 👨‍💻rawsta...',  logEnd );
	console.log( '%c Expect bugs👾 and surprise✨features!', logPrimaryBG );
	console.log( '%c ...and weird behaviour!🐱‍💻', logSecondaryBG );
	// setTimeout( function() {
	// 	console.clear();
	// }, 6666);

	// #endregion

	// #region - CACHE THINGS ---------------------------------------- //

	const language = document.documentElement.lang;
	const groupSelect = $( "#groupSelect" );

	const snippetError = $( "#snippet-error" );

	// const lineNumbersSpan = $( ".line-numbers span" ); //settings

	// const fontChooser = $( ".font-chooser" );
	// const fontChooserUl = $( ".font-chooser ul" );
	// const fontChooserSpan = $( ".font-chooser span" );

	// const settingOk = $( ".setting-ok" );
	// const sNotification = $( "#s-notification" );
	// const sNotificationPass = $( "#s-notification-pass" );

	const snippetOptions = document.querySelector( ".snippet-options" );
	const fancySwitch = document.querySelector( ".fancy" );
	const shareLabel = $( "#share-label" );
	const shareLink = $( "#share-link" );

	// const headerActions = document.querySelector( '.header-actions' );
	// const menuIcon = document.querySelector( '.menu-icon' );
	// const userNav = document.querySelector( '.user-nav' );

	const codeBlock = document.getElementById( 'code-block' );

	const modals = document.querySelectorAll( '[data-modal]' );
	const blur = document.getElementById( 'blur' );

	const header = {
        appTitle     : document.querySelector( '.appTitle' ),
        options      : document.querySelector( '.header-options' ),
        searchInput  : document.getElementById("search-input"),
        searchButton : document.getElementById("search-button"),
        actions      : document.querySelector( '.header-actions' ),
        menuIcon     : document.querySelector( '.menu-icon' ),
        userNav      : document.querySelector( '.user-nav' ),
	}

	const settings = {
        mainSettings    : document.querySelector( '.main-settings' ),
        mailSettings    : document.querySelector( '.mail-settings' ),
        passSettings    : document.querySelector( '.password-settings' ),
        lineNumbersSpan : document.querySelector( '.header-options' ),
        fontChooser     : document.querySelector( '.font-chooser' ),
        fontChooserUl   : document.querySelector( '.font-chooser ul' ),
        fontChooserSpan : document.querySelector( '.font-chooser span' ),
        settingOk       : document.querySelector( '.setting-ok' ),
        sNotification   : document.querySelector( '#s-notification' ),
        sNotificationPass  : document.querySelector( '#s-notification-pass' ),
	}

	// #endregion

	// #region - HIDE THINGS ---------------------------------------- //
	/* Hide various things on load. TODO: find a better way */

	snippetError.hide();

	$( "#details-button" ).hide();
	$( ".snippet-group-option" ).hide();

	// #endregion

	// #region - HELPER ---------------------------------------- //
	/* Lazy shortcuts for lazy coder. */

	const toggleMenu = () => {
		header.actions.classList.toggle( 'menu-open' );
	};

	const toggleBlur = () => {
		blur.classList.toggle( 'open' );
	};

	const Toast = Swal.mixin( {
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: 3030,
		timerProgressBar: true,
		didOpen: ( toast ) => {
			toast.addEventListener( 'mouseenter', Swal.stopTimer )
			toast.addEventListener( 'mouseleave', Swal.resumeTimer )
		}
	});


	// Hide modal on click outside.
		// document.addEventListener( 'click', function( event ) {
		// 	var modal = document.getElementsByClassName( 'modal' )[0];

		// 	if ( ! modal || event.target.closest( '.modal' ) ) {
		// 		return;
		// 	}

		// 	if ( '1' === modal.style.opacity ) {
		// 		hide( 'modal' );
		// 	}
		// });


	// #endregion

	// #region - EVENT LISTENER ---------------------------------------- //

	// --- Switch Language
	$( ".language-wrap button" ).on( 'click', function() {
		$.post( "changeLang.php", {"lang" : $( this ).attr( "id" )}, () => {
			location.reload();
		});
	});
	$.post(`lang/${language}.php`, {"lang" : "true"}, ( data ) => {
		lang = data;
	}, "json" );


	// --- TOGGLE MENU
	header.menuIcon.addEventListener( 'click', () => {
		toggleMenu();
	});
	// menu-item click
	header.userNav.addEventListener( 'click', () => {
		toggleMenu();
	});

	// --- fancy search

	const expand = () => {
		header.searchButton.classList.toggle("close");
		header.searchInput.classList.toggle("square");
	};

	header.searchButton.addEventListener( "click", expand );



	// --- TOGGLE SNIPPET OPTIONS / SHARING
	fancySwitch.addEventListener( 'click', () => {
		snippetOptions.classList.toggle( 'options-open' );
	});

	// Select Group in Add Snippet
	groupSelect.on( 'click', function() {
			console.info('selecting associated groups...' );
		$( this ).css( "border-radius",  "5px 5px 0 0" );
		$( ".groupDropDown" ).fadeIn( FADETIME );
	});

	// --- SELECT GROUP FOR SNIPPET
	$( ".groupDropDown li" ).on( 'click', function() {
			console.info('group for snippet selected...' );
		const t = $( this );
		groupSelect.attr( "data-id", t.attr( "id" ));
		groupSelect.text( t.text());
		$( ".groupDropDown" ).fadeOut( FADETIME );

	});

	// #endregion

	// #region - ADD GROUP ---------------------------------------- //

	// --- ADD GROUP - show form
	$( ".bottom-add-group" ).on( 'click', () => {
		$( ".add-group" ).fadeIn( FADETIME );
		toggleBlur();
	});

	// --- SUBMIT GROUP
	$( "#addGroupSubmit" ).on( 'click', () => {
		$( "#addGroupForm" ).ajaxForm( {
				url : "add-group.php",
				type: "post",
				success: addGroupCallBack
			} );

		function addGroupCallBack( data ) {
			if( data != "ok" ) {
				$( "#addGroupError" ).html( data );
				$( "#addGroupError" ).fadeIn( FADETIME );
			} else {
				window.location.reload();
			}
		}
	});

	// CANCEL ADDING - fade out stuff
	$( "#addGroupCancel" ).on( 'click', () => {
		$( "#addGroupError" ).fadeOut( FADETIME );
		$( "#addGroupError" ).html( "" );
		$( ".add-group" ).fadeOut( FADETIME );
		// $( ".blur" ).fadeOut( FADETIME );
		// blur.classList.remove( 'open' );
		toggleBlur();
	});

	// --- DELETE GROUP
	$( ".group-delete" ).on( 'click', function( e )  {
		e.stopPropagation();
		const t = $( this );
		const id = t.data( "id" );

		Swal.fire({
			title: 'Bist du dir sicher?',
			text: 'Was weg ist, ist weg. Wiederholen geht nicht!',
			icon: 'warning',
			showDenyButton: true,
			showCancelButton: false,
			confirmButtonText: 'Jop',
			denyButtonText: 'Nope'
		}).then((result) => {
			if (result.isConfirmed) {

				$.post( "delete-group.php", {"id" : id}, ( data ) => {
					if( data == "ok" ) {
						Swal.fire(
							'Deleted!',
							'Gruppe wurde gel&ouml;scht',
							'success'
						);
						setTimeout( function() {
							window.location.reload();
						}, 2500 );
					}
					// TODO: catch error
				});

			} else if (result.isDenied) {
				Swal.fire('Save!', 'Gruppe doch nicht gel&ouml;scht', 'info' )
			}
		})

	});

	// #endregion

	// #region - SETTINGS ---------------------------------------- //

	$( ".settings-sidebar ul li" ).on( 'click', function() {
		settings.settingOk.textContent = lang.apply;
		settings.sNotification.textContent =  "";
		settings.sNotificationPass.textContent =  "";
		$( ".settings-sidebar ul li" ).removeClass( "setting-active" );
		$( this ).addClass( "setting-active" );

		if( $( this ).text() == lang.mainSettings ) {
			settings.mainSettings.style.display = 'block';
			$( ".mail-settings" ).hide();
			$( ".password-settings" ).hide();
			settings.settingOk.data( "current", "main" );
		} else if( $( this ).text() == lang.email) {
			settings.mainSettings.style.display = 'none';
			$( ".mail-settings" ).show();
			$( ".password-settings" ).hide();
			settings.settingOk.data( "current", "mail" );
		} else if( $( this ).text() == lang.password ) {
			settings.mainSettings.style.display = 'none';
			$( ".mail-settings" ).hide();
			$( ".password-settings" ).show();
			settings.settingOk.data( "current", "password" );
		}
	});


	// close settings
	$( ".setting-close" ).on( 'click', () => {
		if( check == 1 ) location.reload();
		else{
			$( ".settings-form" ).fadeOut( FADETIME );
			// $( ".blur" ).fadeOut( FADETIME );
			// blur.classList.remove( 'open' );
			// toggleBlur();
		}
	});

	// dropdown font chooser
	settings.fontChooser.addEventListener( 'click', () => {
			console.info('font-selection activated.' );
		settings.fontChooserUl.fadeIn( FADETIME );
		settings.fontChooser.css( "background-color", "var(--raw-color_primary )" );
	});

	// font selected
	$( ".font-chooser ul li" ).on( 'click', function( e )  {
		e.stopPropagation();
			console.log('new font selected.' );
		settings.settingOk.textContent =  lang.apply;
		settings.sNotification.textContent = "" ;
		settings.sNotificationPass.textContent = "" ;
		settings.fontChooserSpan.textContent = $( this ).text();
		settings.fontChooserSpan.css( "font-family", $( this ).text());
		$( ".current-font" ).data( "font", $( this ).text());
		settings.fontChooser.css( "background-color", "var(--raw-color_primary )" );
		settings.fontChooserUl.fadeOut( FADETIME );
	});

	// linenumber option
	settings.lineNumbersSpan.addEventListener( 'click', () => {
		settings.settingOk.textContent = lang.apply;
		settings.sNotification.textContent = "";
		settings.sNotificationPass.textContent = "";
		if( settings.lineNumbersSpan.css( "background-color" ) == "var(--raw-color_success )" ) {
			settings.lineNumbersSpan.textContent = lang.disabled;
			settings.lineNumbersSpan.css( "background-color", "var(--raw-color_error)" );
			settings.lineNumbersSpan.data( "value", 0);
		} else {
			settings.lineNumbersSpan.textContent = lang.enabled;
			settings.lineNumbersSpan.css( "background-color", "var(--raw-color_success )" );
			settings.lineNumbersSpan.data( "value", 1);
		}
	});

	// Save the settings // TODO: Strings in lang.php
	settings.settingOk.addEventListener( 'click', function() {
			console.info('processing set data-values...' );
		if( $( this ).data( "current" ) == 'main' ) {

			$.post( "settings.php", {
				'line-nums' : $( "#line-num-span" ).data( 'value' ),
				'font' : $( ".current-font" ).data( 'font' ),
				'size' : $( ".font-size" ).data( 'size' ),
				'set' : '1'
			}, ( data ) => {
				if( data == 'ok' ) { // lang.settingsNotSaved
					check = 1;
						console.log('new system settings accepted.' );
					settings.settingOk.textContent = lang.applied;
					Toast.fire( {
						icon: 'success',
						title: 'Einstellungen gespeichert!'
					} );
				} else {
					// fail / lang.settingsSaved
					Toast.fire( {
						icon: 'error',
						title: 'Fehler! Daten nicht gespeichert.'
					} );
					settings.settingOk.textContent = lang.error;
				}
			});

		} else if( $( this ).data( "current" ) == 'mail' ) {

			$.post( "settings.php", {
				'new-mail' : $( "#s-new-email" ).val(),
				'rep-mail' : $( "#s-rep-email" ).val(),
				'set' : '2'
			}, ( data ) => {
				if( data == 'ok' ) {
					Toast.fire( {
						icon: 'success',
						title: 'Passwort ge&auml;ndert!'
					} );
					settings.settingOk.textContent = lang.applied;
					settings.sNotification.css( "color", "var(--raw-color_success )" );
					settings.sNotification.textContent = lang.checkEmailDetails;
				} else {
					// fail
					Toast.fire( {
						icon: 'error',
						title: 'Fehler! Konnte nicht gespeichert werden.'
					} );
					settings.settingOk.textContent = lang.error;
					settings.sNotification.css( "color", "var(--raw-color_error )" );
					settings.sNotification.textContent = data;
				}
			});

		} else if( $( this ).data( "current" ) == 'password' ) {

			$.post( "settings.php", {
				'old-pass' : $( "#s-old-pass" ).val(),
				'new-pass' : $( "#s-new-pass" ).val(),
				'rep-pass' : $( "#s-rep-pass" ).val(),
				'set' : '3'
			}, ( data ) => {
				if( data == 'ok' ) {
					settings.settingOk.textContent = lang.applied;
					settings.sNotificationPass.css( "color", "var(--raw-color_success )" );
					settings.sNotificationPass.textContent = lang.passwordChanged;
				} else {
					// fail
					Toast.fire( {
						icon: 'error',
						title: 'Fehler! Konnte nicht gespeichert werden.'
					} );
					settings.sNotificationPass.css( "color", "var(--raw-color_error)" );
					settings.sNotificationPass.textContent = data;
				}
			});

		}

	});

	$( ".font-size span" ).on( 'click', function() {
		settings.settingOk.textContent = lang.apply;
		settings.sNotification.textContent = "";
		settings.sNotificationPass.textContent = "";
		$( ".font-size span" ).removeClass( "active-size" );
		$( this ).addClass( "active-size" );

		if( $( this ).text() == lang.small) {
			$( ".font-size" ).data( "size", '60' );
		} else if( $( this ).text() == lang.medium) {
			$( ".font-size" ).data( "size", '75' );
		} else {
			$( ".font-size" ).data( "size", '90' );
		}
	});



	// #endregion

	// #region - MODALS ---------- //

	modals.forEach( ( trigger ) => {
		trigger.addEventListener( 'click', ( e ) => {
			e.preventDefault();
			const modal = document.getElementById( trigger.dataset.modal );
			// blur.classList.add( 'open' );
			modal.classList.add( 'open' );
			toggleBlur();

			const exits = modal.querySelectorAll( '.modal-exit' );
			exits.forEach( ( exit ) => {
				exit.addEventListener( 'click', ( e ) => {
					e.preventDefault();
					// blur.classList.remove( 'open' );
					modal.classList.remove( 'open' );
					toggleBlur();
				});
			});
		});
	});

	// #endregion

	// #region - IMPORT OPTIONS ---------------------------------------- //

	let check = 0;
	window.tempSnippet = 0;
	$( "#upload-import" ).on( 'click', () => {

		console.info('starting filetransfer...' );
		$( '#upload-form' ).ajaxForm({
				url: 'import.php',
				type: 'post',
				beforeSubmit: showMessage,
				success: receiveData
			});

		function showMessage() {
			console.info('...validating new data...' );
			$( "#upload-message" ).text( lang.importingCodeSnippets );
		}

		function receiveData( data ) {
			console.info('...new data received.' );
			$( "#upload-message" ).text( data );
			if(data == 'ok' ) {
				setTimeout( () => {
					window.location.reload();
				}, 2000);
			}
		}
	});

	$( "#upload-cancel" ).on( 'click', () => {
		console.info('...upload aborted by user.' );
		$( "#upload-form" ).fadeOut();
		blur.classList.remove( 'open' );
	});


	// #endregion

	// #region - EXPORT OPTIONS ---------------------------------------- //

	$( "#import-label" ).on( 'click', () => {
			console.info('preparing system for import of json-file...' );
		$( "#upload-form" ).fadeIn( FADETIME );
		toggleBlur();
	});


	// #endregion

	// #region - SUBLIME SNIPPET ---------------------------------------- //

	$( "#sublime-label" ).on( 'click', () => {
		console.info('exporting snippet to sublime text...' );
		$( "#sublime-code" ).val( $( ".raw-code" ).html());
		$( "#sublime-title" ).val( $( "#detail-title" ).text());
		$( "#sublime-snippet-input" ).val( "" );
		$( ".sublime-snippet-window" ).fadeIn( FADETIME );
		toggleBlur();
	});

	$( "#submit-sublime-snippet" ).on( 'click', () => {
		$( ".sublime-snippet-window" ).fadeOut( FADETIME );
		toggleBlur();
	});

	$( "#sublime-snippet-cancel" ).on( 'click', () => {
		console.info('...exported aborted by user.' );
		$( ".sublime-snippet-window" ).fadeOut( FADETIME );
		toggleBlur();
	});

	// #endregion

	// #region - SEARCH SNIPPETS ---------------------------------------- //

	const snippets = $( ".snippets" );
	const search_bar = $( ".search-bar" );

	// $( ".code" ).css( "z-index", "-1" );
	// $( ".snippet-icons" ).css( "z-index", "0" );

	//TODO: nur auf keys achten wenn kein popup offen ist
	$( document ).on( "keydown", ( e ) => {
		// focus search on [F3] = 114 and [ctrl] + [f] = 70
		// [/] = 55 - numpad[/] = 111 - e.shiftKey
		if( e.keyCode === 114 || e.keyCode === 111 || ( e.ctrlKey && e.keyCode === 70 ) || ( e.shiftKey && e.keyCode === 55 ) ) {
			console.info('🤖 activating snippetfinder...' );
			$( ".search-bar" ).focus();
			e.preventDefault();
		}

		// open snippet on [enter]
		if( e.keyCode === 13 ) {
			$( ".snippets div" ).first().trigger( "click" );
		}
	});

	// instant search
	search_bar.keyup( () => {
		$.post(
			"search.php",
			{ text : search_bar.val() },
			( data ) => {
				if( data.trim() != "" || search_bar.val() != "" ) {

					if( positionCheck == "tags" ) {

						$( ".tag-list" ).hide( "slide", { direction: "right" }, 300);
						snippets.show( "slide", { direction: "left" }, 300);

					} else if( positionCheck == "groups" ) {

						$( ".groups" ).hide( "slide", { direction: "right" }, 300);
						snippets.show( "slide", { direction: "left" }, 300);

					}
					snippets.html( data );
				}
				if( search_bar.val() == "" ) {
					if( positionCheck == "tags" ) {

						snippets.hide( "slide", { direction: "left" }, 300);
						$( ".tag-list" ).show( "slide", { direction: "right" }, 300);

					} else if( positionCheck == "groups" ) {

						snippets.hide( "slide", { direction: "left" }, 300);
						$( ".groups" ).show( "slide", { direction: "right" }, 300);

					}
				} else if( data.trim() == "" ) {
					snippets.html(`<span class='no-results'>${lang.noResults}</span>`);
				}
		});
	});

	// #endregion

	// #region - 'ADD' Buttons on bottom ---------------------------------------- //

	const id_name = $( "#name" );

	$( ".bottom-add-snippet" ).on( 'click', ( event ) => {
		console.info('preparing system for new Snippet entry...' );
		$( ".groupDropDown" ).hide();
		groupSelect.css( "border-radius", "var(--raw-border-radius) var(--raw-border-radius) 0 0" );
        $( "#save-snippet" ).html( lang.save );
		$( ".check-label" ).data( "type", "save" );
		id_name.val( "" );
		$( "#description" ).val( "" );
		$( "#snippetArea" ).val( "" );
		$( "#myTags" ).tagit( "removeAll" );
		// blur.classList.add( 'open' );
		toggleBlur();
	});

	$( "#snippet-cancel" ).on( 'click', () => {
		Toast.fire( {
			icon: 'info',
			title: 'Abgebrochen! Es wurde nichts gespeichert.'
		} );
		// $( ".full" ).fadeOut( FADETIME );
		// blur.classList.remove( 'open' );
		toggleBlur();
	});

	// #endregion

	// #region - SAVE SNIPPET ---------------------------------------- //

	$( "#save-snippet" ).on( 'click', () => {
		if( $( ".check-label" ).data( 'type' ) == 'save' ) {
			$.post( "input-snippet.php", {
				'name' : id_name.val(),
				'description' : $( "#description" ).val(),
				'snippet' : $( "#snippetArea" ).val(),
				'tags' : JSON.stringify( $( "#myTags" ).tagit( "assignedTags" )),
				'flag' : false,
				"groups" : groupSelect.attr( "data-id" )
			}, ( data ) => {
					if( data == 'ok' ) {
						Toast.fire( {
							icon: 'success',
							title: 'Snippet gespeichert!'
						} );
						setTimeout( function() {
							window.location.reload();
							return;
						}, 3000 );
						// location.reload();
						// return;
					}
					Toast.fire( {
						icon: 'error',
						title: 'Sorry, da ist etwas schief gelaufen.'
					} );
					snippetError.html( "" );
					snippetError.fadeIn( FADETIME );
					snippetError.html( data );

				});
		} else if( $( ".check-label" ).data( 'type' ) == 'update' ) {
			$.post( "input-snippet.php", {
				'name' : id_name.val(),
				'description' : $( "#description" ).val(),
				'snippet' : $( "#snippetArea" ).val(),
				'tags' : JSON.stringify( $( "#myTags" ).tagit( "assignedTags" )),
				'flag' : true,
				'id' : $( ".id-holder" ).val(),
				"groups" : groupSelect.attr( "data-id" )
			}, ( data ) => {
					if( data == 'ok' ) {
						Toast.fire( {
							icon: 'success',
							title: 'Snippet aktualisiert!'
						} );
						setTimeout( function() {
							window.location.reload();
							return;
						}, 3000 );
						// window.location.reload();
						// return;
					}
					Toast.fire( {
						icon: 'error',
						title: 'Sorry, da ist etwas schief gelaufen.'
					} );
					snippetError.html( "" );
					snippetError.fadeIn( FADETIME );
					snippetError.html( data );
				});
		}
	});

	// SIDEBAR selecting snippet
	$( document ).on( "click", ".snippet", function() {
			console.info('...selecting snippet...' );
		$( ".snippet" ).removeClass( "active" );
		$( this ).addClass( "active" );
	});


	// show details for snippet
	const id_details_button = $( "#details-button" );
	// getting necessary height
	const titlearea_height = $('.title-area' ).outerHeight();
	const detailstop_height = $('.details-window-top' ).outerHeight();
	const top_height = `${titlearea_height + detailstop_height}px`;

	id_details_button.on( 'click', function( e ) {
		e.preventDefault();

		if ( $( this ).hasClass( "isDown" ) ) {
				console.info('...hiding additional data...' );
			$( ".details-window-under" ).animate({"top": `-${top_height}`, "opacity" : 0}, FADETIME);
			$( this ).removeClass( "isDown" );
			id_details_button.removeClass( "box_rotate" );
			id_details_button.prop( "title", lang.showMoreDetails );
		} else {
				console.info('...showing additional data...' );
			$( ".details-window-under" ).animate({"top" : top_height, "opacity" : 1}, FADETIME);
			$( this ).addClass( "isDown" );
			id_details_button.addClass( "box_rotate" );
			id_details_button.prop( "title", lang.hideDetails );
		}
		return false;

	});


	// #endregion

	// #region - SHARING ---------------------------------------- //

	shareLabel.on( 'click', () => {
		$( ".share-window" ).fadeIn( FADETIME );
		// blur.classList.add( 'open' );
		toggleBlur();
	});

	$( "#share-close" ).on( 'click', () => {
		$( ".share-window" ).fadeOut( FADETIME );
		// blur.classList.remove( 'open' );
		toggleBlur();
	});

	$( "#share-option" ).on( 'click', function() {
		if( $( this ).text() == lang.yes ) {
			$( this ).text( lang.no);
			Toast.fire( {
				icon: 'info',
				title: lang.snippetPublic
			} );
			$( this ).css( "background-color", "var(--raw-color_error)" );
			shareLink.prop( 'disabled', false );
			shareLink.addClass( "active-share" );
			shareLink.removeClass( "inactive-share" );
			$( "#share-label span" ).text( lang.public );
			shareLabel.prop( 'title', lang.snippetPublic);
			$.post( "share.php", {'value' : 1, 'id' : tempSnippet});
		} else {
			$( this ).text( lang.yes );
			Toast.fire( {
				icon: 'info',
				title: lang.snippetPrivate
			} );
			$( this ).css( "background-color", "var(--raw-color_success )" );
			shareLink.prop( 'disabled', true);
			shareLink.removeClass( "active-share" );
			shareLink.addClass( "inactive-share" );
			$( "#share-label span" ).text( lang.private );
			shareLabel.prop( 'title',lang.snippetPrivate );
			$.post( "share.php", {'value' : 0, 'id' : tempSnippet});
		}
	});

	// open code raw in new tab
	$( "#code-label" ).on( 'click', () => {
		const win = window.open( 'about:blank' );
		with( win.document ) {
			open();
			write( $( ".raw-code" ).html().replace(/\n/g,"<br>" ));
			close();
		}
	});

	// Copy Content to Clipboard
	const clipboard = new ClipboardJS( '#copy-label' );
	clipboard.on( 'success', ( e )  => {
		Toast.fire( {
			icon: 'success',
			title: 'Daten kopiert!'
		} );
		console.info( 'Action:', e.action );
		console.info('the snippet is in your system now.' );
		e.clearSelection();
	});

	clipboard.on( 'error', ( e )  => {
		Toast.fire( {
			icon: 'error',
			title: 'Fehler beim kopieren!'
		} );
		console.error( 'Action:', e.action );
	});

	// #endregion

}); // END --- doc.ready ------------- //



// --- SIDEBAR ---------------------------------------- //

// #region - UPPER OPTIONS ---------- //

function showTags() {
	$( "#groupsTrigger" ).removeClass( "upperOptionsActive" );
	$( "#tagsTrigger" ).addClass( "upperOptionsActive" );

	positionCheck = "tags";

	$( ".tag-list" ).fadeIn( FADETIME );
	$( ".snippets" ).fadeOut();
	$( ".groups" ).fadeOut();
}

function showGroups() {
	$( "#groupsTrigger" ).addClass( "upperOptionsActive" );
	$( "#tagsTrigger" ).removeClass( "upperOptionsActive" );

	positionCheck = "groups";

	$( ".tag-list" ).fadeOut();
	$( ".snippets" ).fadeOut();
	$( ".groups" ).fadeIn( FADETIME );
}

// #endregion

// #region - GO BACK/TO GROUP ---------- //

function goBack() {
	$( ".snippets" ).hide( "slide", { direction: "left" }, 300 );
	$( ".tag-list" ).show( "slide", { direction: "right" }, 300 );
}

function goBackGroup() {
	$( ".snippets" ).hide( "slide", { direction: "left" }, 300 );
	$( ".groups" ).show( "slide", { direction: "right" }, 300 );
}

// #endregion

// #region - FIND SNIPPETS IN GROUP ---------- //

function findSnippetsFromGroups( id, u ) {
	$( "#copy-label" ).text( lang.copy );
	$.post( "find-snippetsGroup.php", {'groupId' : id}, ( data ) => {

		$( ".groups" ).hide( "slide", { direction: "right" }, 300 );
		$( ".snippets" ).show( "slide", { direction: "left" }, 300 );

		$( ".snippets" ).html( "" );
		$( ".snippets" ).append(`<div onclick='goBackGroup();' class='back'><i class='las la-chevron-left'></i> ${lang.back}</div>`);

		for( const i in data.title ) {
			$( ".snippets" ).append(`<div onclick='if( event.target === this ) getSnippet(${data.snippetId[i]});' data-snippetId=${data.snippetId[i]} class='snippet'><p onclick='if(event.target === this) getSnippet( ${data.snippetId[i]});'> ${data.title[i]}</p><span onclick='removeSnippet(${data.snippetId[i]});'><i class="lar la-trash-alt"></i></span><span onclick='editSnippet( ${data.snippetId[i]});'><i class="las la-pencil-alt"></i></span></div>`);
		}

		if( u ) {
			$( ".snippets div:nth-child(2)" ).addClass( "active" );
		}

	}, "json" );
}

// #endregion

// #region - FIND SNIPPETS ---------- //

function findSnippets( tag, u ) {
	$( "#copy-label" ).text( lang.copy );
	$.post( "find-snippets.php", {'tag' : tag}, ( data ) => {

		$( ".tag-list" ).hide( "slide", { direction: "right" }, 300 );
		$( ".snippets" ).show( "slide", { direction: "left" }, 300 );
		$( ".snippets" ).html( "" );
		$( ".snippets" ).append(`<div onclick='goBack();' class='back'><i class='las la-caret-left la-lg la-fw'></i>  ${lang.back}</div>`);

		for( const i in data.title ) { //TODO: Fix these label
			$( ".snippets" ).append(`<div onclick='if (event.target === this ) getSnippet( ${data.snippetId[i]});' data-snippetId=${data.snippetId[i]} class='snippet'><p onclick='if (event.target === this ) getSnippet( ${data.snippetId[i]});'>${data.title[i]}</p><span onclick='removeSnippet( ${data.snippetId[i]});'><i class="lar la-trash-alt la-lg la-fw"></i></span><span onclick='editSnippet( ${data.snippetId[i]});'><i class="las la-pencil-alt la-lg la-fw"></i></span></div>`);
		}
		if( u ) {
			$( ".snippets div:nth-child(2)" ).addClass( "active" );
		}
	}, "json" );
	tagTemp = tag;
}


// #endregion

// #region - GET SNIPPET ---------- //

function getSnippet( id ) {
	$( "#copy-label" ).text( lang.copy );
	$( "#copy-label" ).css( "right", "65px" );
	getDetails( id );
	$( "#details-button" ).show();
	$( ".details-window-top" ).show();
	// $( ".snippet-option-wrap" ).css( "z-index", "1" );

	$.post( "get-snippet.php", {'id' : id, 'flag' : true}, ( data ) => {
		$( "#code-block" ).html( data );
		$( ".raw-code" ).html( data );

		// $( '.prettyprinted' ).removeClass( 'prettyprinted' );
		// prettyPrint();
		Prism.highlightElement($('#code-block' )[0]);

		// const linenums = $( "pre[class*=line-number] > li" ).length / 100;
		// const temp = 10 * parseInt( ( linenums - 2 ) ) + 30;
		// if( linenums > 2 ) {
		// 	$( "#code-block ol > li" ).css( "left", `${temp}px` );
			// $( ".prettyprint ol.linenums > li" ).css( "left", `${temp}px` );
		// }

		// $( ".code" ).css( "z-index", "0" );
		// $( ".snippet-icons" ).css( "z-index", "1" );
	});
}


// #endregion

// #region - SNIPPET DETAILS ---------- //

function getDetails( id ) {
	const shareOption = $( ".snippet-options" );
	const shareLabel = $( "#share-label" );
	const shareLink = $( "#share-link" );
	let tempSnippet;

	$( "#copy-label" ).text( lang.copy );
	// $( "#copy-label" ).css( "right", "65px" );
	$.post( "get-details.php", {'id' : id}, ( data ) => {
		$( ".detail-title" ).html(data.title);
		$( "#detail-desc" ).html(data.description);
		$( "#detail-tags" ).html(data.tags );
		$( "#date-label" ).html(` <i class="las la-calendar-plus"></i>  ${lang.created} [ ${data.date} ] `);

		tempSnippet = data.idSnippet;

		// shareLink.val(`${$( "#sitePath-holder" ).text()}/public.php?id=${data.idSnippet}`);
		shareLink.val(`${$( "#sitePath-holder" ).text()}/public.php?id=${data.idSnippet}`);

		// set sharing dynamically / if not public -disable
		if( data.public == 0 ) { /// TODO: fix and set right order and values
			shareOption.prop( "title", lang.yes );
			shareOption.css( "color", "var(--raw-color_success )" );
			shareLabel.textContent = lang.private;
			shareLabel.prop( 'title', lang.snippetPrivate );
			shareLink.prop( 'disabled', true);
			shareLink.removeClass( "active-share" );
			shareLink.addClass( "inactive-share" );
		} else if( data.public == 1 ) {
			shareOption.prop( "title", lang.no );
			shareOption.css( "color", "var(--raw-color_error)" );
			shareLabel.textContent = lang.public;
			shareLabel.prop( 'title', lang.snippetPublic);
			shareLink.prop( 'disabled', false);
			shareLink.addClass( "active-share" );
			shareLink.removeClass( "inactive-share" );
		}
	}, "json" );
}

// #endregion

// #region - EDIT SNIPPET --- //

function editSnippet( id ) {
	$( "#snippet-error" ).hide();
	$( "#snippet-error" ).html( "" );

	$( "#copy-label" ).text( lang.copy );
	$( "#copy-label" ).css( "right", "65px" );
	$( "#name" ).val( "" );
	$( "#description" ).val( "" );
	$( "#snippetArea" ).val( "" );
	$( "#myTags" ).tagit( "removeAll" );

	$.post( "get-snippet.php", {'id':id, 'flag':false}, ( data ) => {
		$( "#name" ).val( data.title );
		$( "#description" ).val( data.description );
		$( "#snippetArea" ).val( data.snippet );
	}, "json" );

	$.post( "get-group.php", {"id" : id}, ( data ) => {
		if( !data.id ) {
			$( "#groupSelect" ).attr( "data-id", data.id );
			$( "#groupSelect" ).text( data.name );
		} else {
			$( "#groupSelect" ).attr( "data-id", null );
			$( "#groupSelect" ).text( lang.groupSelect );
		}
	}, "json" );


	$.post( "get-tags.php", {id}, ( tags ) => {

		for( const i in tags ) {
			$( "#myTags" ).tagit( "createTag", tags[i].toString() );
		}

		$( ".id-holder" ).val( id );
		$( "#save-snippet" ).html( lang.update );
		$( ".check-label" ).data( "type", "update" );

		// $( ".full" ).fadeIn( FADETIME );
		blur.classList.add( 'open' );
	}, "json" );

}

// #endregion

// #region - REMOVE SNIPPET ---------- //

function removeSnippet ( id ) {
	// TODO: setze texte in lang.php
	Swal.fire({
		title: 'Bist du dir sicher?',
		text: 'Was weg ist, ist weg. Wiederholen geht nicht!',
		icon: 'warning',
		showDenyButton: true,
		showCancelButton: false,
		confirmButtonText: 'Jop',
		denyButtonText: 'Nope'
	}).then( ( result ) => {
		if ( result.isConfirmed ) {

			$.post( "remove-snippet.php", {id}, ( message ) => {
				if( message == 'ok' ) {
					Swal.fire(
						'Deleted!',
						'Snippet wurde gel&ouml;scht',
						'success'
					);
					setTimeout( function() {
						window.location.reload();
					}, 2500 );
				} else {
					Swal.fire(
						'ERROR!',
						'Es gab einen Fehler:' + message + '?',
						'error'
					);
				}
			});

		} else if( result.isDenied ) {
			Swal.fire('Save!', 'Snippet doch nicht gel&ouml;scht', 'info' );
		}
	})
	// $.post( "remove-snippet.php", {id}, ( message ) => {
	// 	if( message == 'ok' ) {
	// 		location.reload();
	// 	}
	// });
}

// #endregion
