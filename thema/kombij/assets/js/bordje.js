/*
 * Het bordje onder de balk: open of gesloten.
 *
 * Rekent in de tijd van Amsterdam, niet in die van de bezoeker. Kijkt iemand
 * vanuit het buitenland mee, dan klopt "open tot 16:30" nog steeds. Elke minuut
 * kijkt het opnieuw.
 */
( function () {
	'use strict';

	var bordje = document.querySelector( '.kbj-bordje' );

	if ( ! bordje ) {
		return;
	}

	var stand = bordje.querySelector( '[data-stand]' );
	var wanneer = bordje.querySelector( '[data-wanneer]' );
	var open = bordje.dataset.open;
	var dicht = bordje.dataset.dicht;
	var dagen = ( bordje.dataset.dagen || '' ).split( ',' ).map( Number );
	var DAGNAMEN = [ 'zondag', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag' ];

	function minuten( tekst ) {
		var delen = tekst.split( ':' );

		return parseInt( delen[ 0 ], 10 ) * 60 + parseInt( delen[ 1 ], 10 );
	}

	function nuInAmsterdam() {
		try {
			var delen = new Intl.DateTimeFormat( 'en-US', {
				timeZone: 'Europe/Amsterdam',
				weekday: 'short',
				hour: 'numeric',
				minute: 'numeric',
				hour12: false
			} ).formatToParts( new Date() );
			var pak = function ( soort ) {
				for ( var i = 0; i < delen.length; i++ ) {
					if ( delen[ i ].type === soort ) {
						return delen[ i ].value;
					}
				}
				return '';
			};
			var dag = { Sun: 0, Mon: 1, Tue: 2, Wed: 3, Thu: 4, Fri: 5, Sat: 6 }[ pak( 'weekday' ) ];
			var uur = parseInt( pak( 'hour' ), 10 ) % 24;

			return { dag: dag, minuut: uur * 60 + parseInt( pak( 'minute' ), 10 ) };
		} catch ( e ) {
			var d = new Date();

			return { dag: d.getDay(), minuut: d.getHours() * 60 + d.getMinutes() };
		}
	}

	function ververs() {
		var nu = nuInAmsterdam();
		var vandaagOpen = dagen.indexOf( nu.dag ) !== -1;

		if ( vandaagOpen && nu.minuut >= minuten( open ) && nu.minuut < minuten( dicht ) ) {
			bordje.classList.add( 'is-open' );
			stand.textContent = 'Open';
			wanneer.textContent = 'tot ' + dicht;
			return;
		}

		bordje.classList.remove( 'is-open' );
		stand.textContent = 'Gesloten';

		if ( vandaagOpen && nu.minuut < minuten( open ) ) {
			wanneer.textContent = 'opent om ' + open;
			return;
		}

		for ( var i = 1; i <= 7; i++ ) {
			var volgende = ( nu.dag + i ) % 7;

			if ( dagen.indexOf( volgende ) !== -1 ) {
				wanneer.textContent = 'opent ' + ( 1 === i ? 'morgen' : DAGNAMEN[ volgende ] ) + ' om ' + open;
				return;
			}
		}
	}

	ververs();
	window.setInterval( ververs, 60000 );
}() );
