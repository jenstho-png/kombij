/*
 * De melding rechtsonder: na een paar seconden rustig in beeld, en weg voor
 * altijd zodra iemand hem sluit of op de knop klikt.
 *
 * Onthouden gebeurt in de browser van de bezoeker, met een sleutel die hoort
 * bij de tekst. Verandert KomBij de melding, dan is het een nieuwe sleutel en
 * ziet iedereen hem opnieuw.
 */
( function () {
	'use strict';

	var melding = document.querySelector( '.kbj-melding' );

	if ( ! melding ) {
		return;
	}

	var sleutel = 'kbj-melding-' + melding.dataset.sleutel;
	var seconden = Number( melding.dataset.seconden || 12 );

	function gezien() {
		try {
			return '1' === window.localStorage.getItem( sleutel );
		} catch ( e ) {
			return false;
		}
	}

	function onthoud() {
		try {
			window.localStorage.setItem( sleutel, '1' );
		} catch ( e ) {}
	}

	if ( gezien() ) {
		return;
	}

	function sluit() {
		onthoud();
		melding.classList.remove( 'is-zichtbaar' );
		window.setTimeout( function () {
			melding.hidden = true;
		}, 400 );
	}

	window.setTimeout( function () {
		melding.hidden = false;

		// Een tel later de klasse, zodat de overgang ook echt te zien is.
		window.requestAnimationFrame( function () {
			melding.classList.add( 'is-zichtbaar' );
		} );
	}, seconden * 1000 );

	melding.querySelector( '.kbj-melding__sluit' ).addEventListener( 'click', sluit );

	var knop = melding.querySelector( '.kbj-melding__knop' );

	if ( knop ) {
		knop.addEventListener( 'click', onthoud );
	}

	document.addEventListener( 'keydown', function ( e ) {
		if ( 'Escape' === e.key && ! melding.hidden ) {
			sluit();
		}
	} );
}() );
