/*
 * De balk bovenaan.
 *
 * Twee dingen: het menu op de telefoon open- en dichtklappen, en de echte
 * hoogte van de balk doorgeven aan de CSS. Dat tweede is nodig omdat een anker
 * anders precies onder de balk landt, en dan lijkt de kop te ontbreken. De
 * hoogte schatten met vw gaat mis zodra het menu over twee regels valt.
 */
( function () {
	'use strict';

	var header = document.querySelector( '.cjt-header' );
	var menu = document.querySelector( '.cjt-menu' );

	if ( ! header || ! menu ) {
		return;
	}

	var knop = menu.querySelector( '.cjt-menu__knop' );
	var paneel = menu.querySelector( '.cjt-menu__binnen' );

	function kophoogte() {
		var hoogte = Math.round( header.getBoundingClientRect().height );

		if ( hoogte > 0 ) {
			document.documentElement.style.setProperty( '--cjt-kophoogte', hoogte + 'px' );
		}
	}

	kophoogte();

	window.addEventListener( 'resize', kophoogte, { passive: true } );
	window.addEventListener( 'load', kophoogte );

	if ( document.fonts && document.fonts.ready ) {
		// Een andere letter is een andere regelhoogte, dus opnieuw meten.
		document.fonts.ready.then( kophoogte );
	}

	if ( ! knop || ! paneel ) {
		return;
	}

	function zet( open ) {
		menu.dataset.open = open ? 'ja' : 'nee';
		knop.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
	}

	zet( false );

	knop.addEventListener( 'click', function () {
		zet( 'ja' !== menu.dataset.open );
	} );

	// Escape sluit hem, net als overal.
	document.addEventListener( 'keydown', function ( e ) {
		if ( 'Escape' === e.key && 'ja' === menu.dataset.open ) {
			zet( false );
			knop.focus();
		}
	} );

	// Een link aanklikken sluit het paneel, anders blijft hij over de pagina hangen.
	paneel.addEventListener( 'click', function ( e ) {
		if ( e.target.closest( 'a' ) ) {
			zet( false );
		}
	} );

	// Wordt het scherm breed genoeg, dan hoort het paneel weer een rij te zijn.
	window.addEventListener(
		'resize',
		function () {
			if ( window.innerWidth > 900 ) {
				zet( false );
			}
		},
		{ passive: true }
	);
}() );
