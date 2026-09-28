/*
 * De kerncijfers tellen op zodra ze in beeld komen: 24 uur, 6 plekken, 1869.
 *
 * Alleen het getal aan het begin telt mee, de rest van de tekst blijft staan.
 * De echte waarde staat vanaf het begin in de HTML: zonder script, of voor wie
 * beweging uit heeft staan, staat er gewoon het goede getal.
 */
( function () {
	'use strict';

	if ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		return;
	}

	if ( ! ( 'IntersectionObserver' in window ) ) {
		return;
	}

	var cijfers = document.querySelectorAll( '.kbj-cijfers strong' );

	if ( ! cijfers.length ) {
		return;
	}

	function tel( element ) {
		var tekst = element.textContent;
		var treffer = tekst.match( /^(\d+)(.*)$/ );

		if ( ! treffer ) {
			return;
		}

		var doel = parseInt( treffer[ 1 ], 10 );
		var rest = treffer[ 2 ];

		// Een jaartal telt vanaf honderd jaar eerder, niet vanaf nul.
		var start = doel > 1000 ? doel - 150 : 0;
		var duur = 1400;
		var begin = null;

		function stap( tijd ) {
			if ( null === begin ) {
				begin = tijd;
			}

			var deel = Math.min( 1, ( tijd - begin ) / duur );
			var zacht = 1 - Math.pow( 1 - deel, 3 );

			element.textContent = Math.round( start + ( doel - start ) * zacht ) + rest;

			if ( deel < 1 ) {
				window.requestAnimationFrame( stap );
			} else {
				element.textContent = tekst;
			}
		}

		window.requestAnimationFrame( stap );
	}

	var wachter = new IntersectionObserver(
		function ( items ) {
			items.forEach( function ( item ) {
				if ( item.isIntersecting ) {
					wachter.unobserve( item.target );
					tel( item.target );
				}
			} );
		},
		{ threshold: 0.6 }
	);

	cijfers.forEach( function ( element ) {
		wachter.observe( element );
	} );
}() );
