/*
 * Een video die pas laadt als iemand hem aanzet.
 *
 * Een ingesloten YouTube- of Vimeo-speler is bijna een megabyte aan script en
 * een handvol cookies, ook als niemand op play drukt. Daarom staat er eerst
 * alleen de posterfoto met een knop erop; bij de klik komt de echte speler
 * erin, meteen spelend.
 *
 * Het staat in een schaduw-DOM, zodat de opmaak van de speler nooit met de
 * rest van de pagina kan botsen.
 */
( function () {
	'use strict';

	if ( ! ( 'customElements' in window ) || customElements.get( 'cjt-video-facade' ) ) {
		return;
	}

	var BRONNEN = {
		youtube: [ 'https://www.youtube-nocookie.com', 'https://i.ytimg.com' ],
		vimeo: [ 'https://player.vimeo.com', 'https://i.vimeocdn.com' ]
	};

	/** Zet alvast een verbinding op, zodat de klik niet op DNS hoeft te wachten. */
	function verbind( soort ) {
		( BRONNEN[ soort ] || [] ).forEach( function ( adres ) {
			if ( document.querySelector( 'link[rel="preconnect"][href="' + adres + '"]' ) ) {
				return;
			}

			var link = document.createElement( 'link' );
			link.rel = 'preconnect';
			link.href = adres;
			link.crossOrigin = '';
			document.head.appendChild( link );
		} );
	}

	/** Het adres van de speler, met autoplay aan. */
	function spelerAdres( soort, id, sleutel ) {
		if ( 'vimeo' === soort ) {
			return 'https://player.vimeo.com/video/' + encodeURIComponent( id ) +
				'?autoplay=1&byline=0&portrait=0&title=0&dnt=1' +
				( sleutel ? '&h=' + encodeURIComponent( sleutel ) : '' );
		}

		return 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent( id ) +
			'?autoplay=1&rel=0&modestbranding=1&playsinline=1';
	}

	var STIJL = [
		':host{display:block;position:relative;width:100%;aspect-ratio:16/9;background:#111;overflow:hidden}',
		'button{position:absolute;inset:0;width:100%;height:100%;padding:0;border:0;background:none;cursor:pointer;color:#fff}',
		'img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;display:block}',
		'.knop{position:absolute;left:50%;top:50%;transform:translate(-50%,-50%);width:76px;height:76px;border-radius:50%;',
		'background:rgba(0,0,0,.45);display:flex;align-items:center;justify-content:center;transition:background 300ms ease,transform 300ms ease}',
		'button:hover .knop,button:focus-visible .knop{background:rgba(0,0,0,.7);transform:translate(-50%,-50%) scale(1.06)}',
		'.knop svg{width:26px;height:26px;margin-left:4px;fill:currentColor}',
		'.titel{position:absolute;left:0;right:0;bottom:0;padding:2.5rem 1.25rem 1rem;text-align:left;font:500 .85rem/1.3 system-ui,sans-serif;',
		'background:linear-gradient(to top,rgba(0,0,0,.6),transparent)}',
		'iframe{position:absolute;inset:0;width:100%;height:100%;border:0}'
	].join( '' );

	var Facade = function () {
		return Reflect.construct( HTMLElement, [], Facade );
	};

	Facade.prototype = Object.create( HTMLElement.prototype );
	Facade.prototype.constructor = Facade;
	Object.setPrototypeOf( Facade, HTMLElement );

	Facade.prototype.connectedCallback = function () {
		if ( this.shadowRoot ) {
			return;
		}

		var soort = this.getAttribute( 'soort' ) || 'youtube';
		var id = this.getAttribute( 'video' ) || '';
		var sleutel = this.getAttribute( 'sleutel' ) || '';
		var poster = this.getAttribute( 'poster' ) || '';
		var titel = this.getAttribute( 'titel' ) || 'Bekijk de video';

		if ( ! id ) {
			return;
		}

		var schaduw = this.attachShadow( { mode: 'open' } );

		var stijl = document.createElement( 'style' );
		stijl.textContent = STIJL;

		var knop = document.createElement( 'button' );
		knop.type = 'button';
		knop.setAttribute( 'aria-label', titel );

		if ( poster ) {
			var beeld = document.createElement( 'img' );
			beeld.src = poster;
			beeld.alt = '';
			beeld.loading = 'lazy';
			beeld.decoding = 'async';
			knop.appendChild( beeld );
		}

		var cirkel = document.createElement( 'span' );
		cirkel.className = 'knop';
		cirkel.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>';
		knop.appendChild( cirkel );

		if ( titel ) {
			var regel = document.createElement( 'span' );
			regel.className = 'titel';
			regel.textContent = titel;
			knop.appendChild( regel );
		}

		schaduw.appendChild( stijl );
		schaduw.appendChild( knop );

		var self = this;

		// Bij het naderen van de muis alvast verbinden; dat scheelt bij de klik.
		knop.addEventListener( 'pointerenter', function () {
			verbind( soort );
		}, { once: true } );

		knop.addEventListener( 'click', function () {
			var speler = document.createElement( 'iframe' );
			speler.src = spelerAdres( soort, id, sleutel );
			speler.title = titel;
			speler.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture; fullscreen';
			speler.setAttribute( 'allowfullscreen', '' );
			speler.loading = 'eager';

			schaduw.replaceChild( speler, knop );
			self.dataset.speelt = 'ja';
		} );
	};

	customElements.define( 'cjt-video-facade', Facade );
}() );
