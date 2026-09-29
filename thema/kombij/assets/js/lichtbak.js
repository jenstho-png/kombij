/*
 * Een foto groot bekijken.
 *
 * Klik op een foto in een galerij of op een sfeerbeeld en hij komt over de
 * pagina heen te staan. Escape of een klik ernaast sluit hem weer.
 *
 * Bewust klein gehouden: geen bibliotheek, geen slepen, geen zoomen. Wie meer
 * wil, is bij een fotosite beter af dan hier.
 */
( function () {
	'use strict';

	var KIES = '.wp-block-gallery img, .kbj-sfeerbeeld__beeld, .kbj-album img';

	var doos = null;
	var vorige = null;

	function maak() {
		if ( doos ) {
			return doos;
		}

		doos = document.createElement( 'div' );
		doos.className = 'kbj-lichtbak';
		doos.setAttribute( 'role', 'dialog' );
		doos.setAttribute( 'aria-modal', 'true' );
		doos.hidden = true;
		doos.innerHTML =
			'<button type="button" class="kbj-lichtbak__weg" aria-label="Sluiten">' +
				'<svg viewBox="0 0 16 16" aria-hidden="true" focusable="false">' +
					'<path d="M4 4l8 8M12 4l-8 8" stroke="currentColor" stroke-width="1.2" fill="none"/>' +
				'</svg>' +
			'</button>' +
			'<img alt="">';

		var stijl = document.createElement( 'style' );
		stijl.textContent = [
			'.kbj-lichtbak{position:fixed;inset:0;z-index:100;display:flex;align-items:center;justify-content:center;',
			'background:rgba(15,18,16,.92);padding:clamp(1rem,4vw,3rem)}',
			'.kbj-lichtbak[hidden]{display:none}',
			'.kbj-lichtbak img{max-width:100%;max-height:100%;object-fit:contain;display:block}',
			'.kbj-lichtbak__weg{position:absolute;top:1rem;right:1rem;width:44px;height:44px;border:0;background:none;',
			'color:#fff;cursor:pointer}',
			'.kbj-lichtbak__weg svg{width:20px;height:20px}'
		].join( '' );

		document.head.appendChild( stijl );
		document.body.appendChild( doos );

		doos.addEventListener( 'click', function ( e ) {
			if ( e.target === doos || e.target.closest( '.kbj-lichtbak__weg' ) ) {
				sluit();
			}
		} );

		return doos;
	}

	function open( bron, beschrijving ) {
		var d = maak();
		var beeld = d.querySelector( 'img' );

		beeld.src = bron;
		beeld.alt = beschrijving || '';

		d.hidden = false;
		document.documentElement.style.overflow = 'hidden';

		d.querySelector( '.kbj-lichtbak__weg' ).focus();
	}

	function sluit() {
		if ( ! doos || doos.hidden ) {
			return;
		}

		doos.hidden = true;
		document.documentElement.style.overflow = '';

		if ( vorige && vorige.focus ) {
			vorige.focus();
		}
	}

	document.addEventListener( 'click', function ( e ) {
		var beeld = e.target.closest( KIES );

		if ( ! beeld ) {
			return;
		}

		// Zit de foto in een link, dan wint die link. Dat is een keuze van de redactie.
		if ( beeld.closest( 'a' ) ) {
			return;
		}

		vorige = beeld;

		// De grootste versie die we kennen, anders wordt hij wazig op een groot scherm.
		var groot = beeld.dataset.groot || beeld.currentSrc || beeld.src;

		open( groot, beeld.alt );
	} );

	document.addEventListener( 'keydown', function ( e ) {
		if ( 'Escape' === e.key ) {
			sluit();
		}
	} );
}() );
