/*
 * De effecten met tekst en lijnen.
 *
 * Een kop met de klasse kbj-schuif komt woord voor woord omhoog zodra hij in
 * beeld komt. De lijntekening van het raam zet zich op dezelfde manier. Zonder
 * dit script, of voor wie beweging uit heeft staan, staat alles er meteen.
 */
( function () {
	'use strict';

	if ( ! document.documentElement.classList.contains( 'kbj-js' ) ) {
		return;
	}

	if ( ! ( 'IntersectionObserver' in window ) ) {
		return;
	}

	// Het vangnet in de kop van de pagina weet zo dat ook dit script er is.
	window.kbjEffecten = true;

	// Elk woord in een eigen raampje, zodat het van onder de rand kan komen.
	function splits( kop ) {
		var teller = 0;

		function loop( knoop ) {
			Array.prototype.slice.call( knoop.childNodes ).forEach( function ( kind ) {
				if ( 3 === kind.nodeType ) {
					var stukken = kind.textContent.split( /(\s+)/ );
					var fragment = document.createDocumentFragment();

					stukken.forEach( function ( stuk ) {
						if ( ! stuk ) {
							return;
						}

						if ( /^\s+$/.test( stuk ) ) {
							fragment.appendChild( document.createTextNode( stuk ) );
							return;
						}

						var buiten = document.createElement( 'span' );
						var binnen = document.createElement( 'span' );

						buiten.className = 'kbj-woord';
						binnen.textContent = stuk;
						binnen.style.setProperty( '--i', teller++ );
						buiten.appendChild( binnen );
						fragment.appendChild( buiten );
					} );

					kind.parentNode.replaceChild( fragment, kind );
				} else if ( 1 === kind.nodeType ) {
					loop( kind );
				}
			} );
		}

		loop( kop );
	}

	var wachter = new IntersectionObserver(
		function ( items ) {
			items.forEach( function ( item ) {
				if ( ! item.isIntersecting ) {
					return;
				}

				item.target.classList.add( item.target.classList.contains( 'kbj-schuif' ) ? 'is-in' : 'is-getekend' );
				wachter.unobserve( item.target );
			} );
		},
		{ rootMargin: '0px 0px -10% 0px', threshold: 0.15 }
	);

	document.querySelectorAll( '.kbj-schuif' ).forEach( function ( kop ) {
		splits( kop );
		wachter.observe( kop );
	} );

	document.querySelectorAll( '.kbj-raamlijn' ).forEach( function ( lijn ) {
		wachter.observe( lijn );
	} );
}() );
