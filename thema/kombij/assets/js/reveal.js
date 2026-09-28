/*
 * Alles komt rustig in beeld als je erlangs scrollt.
 *
 * De beginstand (onzichtbaar, iets naar beneden) staat in de CSS achter
 * html.kbj-js. Die klasse zet dit script als eerste, nog voordat er iets
 * getekend is. Draait het script niet, dan is alles gewoon zichtbaar: beter een
 * site zonder beweging dan een lege pagina.
 */
( function () {
	'use strict';

	var wortel = document.documentElement;

	// Wie beweging heeft uitgezet in zijn systeem krijgt hem ook niet.
	if ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
		return;
	}

	if ( ! ( 'IntersectionObserver' in window ) ) {
		return;
	}

	wortel.classList.add( 'kbj-js' );

	// Het vangnet in de kop van de pagina weet zo dat dit script gedraaid heeft.
	window.kbjReveal = true;

	function kijk() {
		var wachters = new IntersectionObserver(
			function ( items ) {
				items.forEach( function ( item ) {
					if ( ! item.isIntersecting ) {
						return;
					}

					var element = item.target;

					/*
					 * Staan er meerdere naast elkaar, dan komen ze kort na
					 * elkaar. Niet langer dan een halve seconde in totaal:
					 * daarna gaat het van rustig naar traag.
					 */
					var beurt = Number( element.dataset.revealBeurt || 0 );

					window.setTimeout( function () {
						element.dataset.reveal = 'klaar';
					}, Math.min( beurt * 90, 500 ) );

					wachters.unobserve( element );
				} );
			},
			{
				// Iets voordat het echt in beeld staat, zodat het al onderweg is.
				rootMargin: '0px 0px -12% 0px',
				threshold: 0.08
			}
		);

		var elementen = document.querySelectorAll( '.kbj-reveal' );

		elementen.forEach( function ( element, i ) {
			/*
			 * Wat al in beeld staat als de pagina opent, hoeft niet te wachten
			 * op een scroll die misschien nooit komt.
			 */
			var plek = element.getBoundingClientRect();

			if ( plek.top < window.innerHeight * 0.9 ) {
				element.dataset.revealBeurt = String( i );
			}

			wachters.observe( element );
		} );
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', kijk );
	} else {
		kijk();
	}
}() );
