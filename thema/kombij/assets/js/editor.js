/*
 * De eigen blokken van het thema in de blok-editor.
 *
 * De blokken worden op de server opgebouwd. Zonder dit script kent de editor
 * ze niet en zegt hij "Your site doesn't include support for…". Hier krijgt
 * elk blok een voorbeeld zoals op de site, en in het zijpaneel de instellingen:
 * teksten, keuzes en bij een foto een knop om zelf een foto te kiezen.
 *
 * Bewust zonder bouwstap: gewone JavaScript met de onderdelen van WordPress.
 */
( function ( wp ) {
	'use strict';

	if ( ! wp || ! wp.blocks || ! wp.serverSideRender ) {
		return;
	}

	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var be = wp.blockEditor;
	var c = wp.components;
	var ServerSideRender = wp.serverSideRender;

	// Nette namen voor de velden in het zijpaneel.
	var NAMEN = {
		kop: 'Kop',
		glans: 'Laatste woorden van de kop (in kleur)',
		tekst: 'Tekst',
		titel: 'Titel',
		intro: 'Inleiding',
		boven: 'Regel boven de kop',
		alt: 'Beschrijving van de foto (voor blinden en Google)',
		bijschrift: 'Bijschrift',
		positie: 'Brandpunt van de foto (bijv. 50% 50%)',
		verhouding: 'Verhouding (bijv. 4/3)',
		vorm: 'Vorm',
		soort: 'Soort',
		naam: 'Tekening',
		plek: 'Plek-vrij label',
		illustratie: 'Tekening in de kop',
		knop: 'Knoppen tonen',
		eerst: 'Bovenaan de pagina (sneller laden)',
		bestand: 'Foto uit het thema (bestandsnaam)',
		breedte: 'Breedte',
		licht: 'Witte versie',
		video: 'Video-adres',
		poster: 'Voorbeeldfoto',
		hoogte: 'Hoogte'
	};

	// Deze velden regelen we zelf of horen niet in het paneel.
	var OVERSLAAN = [ 'align', 'url', 'className', 'lock', 'metadata', 'style' ];

	function veld( naam, schema, waarde, zet ) {
		var label = NAMEN[ naam ] || naam;

		if ( schema.enum ) {
			return el( c.SelectControl, {
				key: naam,
				label: label,
				value: waarde,
				options: schema.enum.map( function ( w ) {
					return { label: w || '(geen)', value: w };
				} ),
				onChange: zet
			} );
		}

		if ( 'boolean' === schema.type ) {
			return el( c.ToggleControl, {
				key: naam,
				label: label,
				checked: !! waarde,
				onChange: zet
			} );
		}

		if ( 'number' === schema.type || 'integer' === schema.type ) {
			return el( c.TextControl, {
				key: naam,
				label: label,
				type: 'number',
				value: waarde === undefined ? '' : waarde,
				onChange: function ( w ) {
					zet( '' === w ? undefined : Number( w ) );
				}
			} );
		}

		var lang = [ 'tekst', 'intro' ].indexOf( naam ) !== -1;

		return el( lang ? c.TextareaControl : c.TextControl, {
			key: naam,
			label: label,
			value: waarde || '',
			onChange: zet
		} );
	}

	function fotoKiezer( props ) {
		if ( ! be.MediaUpload ) {
			return null;
		}

		var a = props.attributes;

		return el( be.MediaUploadCheck, {},
			el( be.MediaUpload, {
				allowedTypes: [ 'image' ],
				onSelect: function ( media ) {
					props.setAttributes( {
						url: media.url,
						alt: media.alt || a.alt || ''
					} );
				},
				render: function ( o ) {
					return el( 'div', { style: { marginBottom: '16px' } },
						el( c.Button, { variant: 'primary', onClick: o.open }, a.url ? 'Andere foto kiezen' : 'Foto kiezen' ),
						a.url ? el( c.Button, {
							variant: 'link',
							isDestructive: true,
							style: { marginLeft: '12px' },
							onClick: function () {
								props.setAttributes( { url: '' } );
							}
						}, 'Terug naar de themafoto' ) : null
					);
				}
			} )
		);
	}

	function maakEdit( naam ) {
		return function ( props ) {
			var type = wp.blocks.getBlockType( naam );
			var schema = ( type && type.attributes ) || {};
			var velden = [];

			Object.keys( schema ).forEach( function ( sleutel ) {
				if ( OVERSLAAN.indexOf( sleutel ) !== -1 ) {
					return;
				}

				velden.push( veld( sleutel, schema[ sleutel ], props.attributes[ sleutel ], function ( w ) {
					var nieuw = {};
					nieuw[ sleutel ] = w;
					props.setAttributes( nieuw );
				} ) );
			} );

			var heeftFoto = Object.prototype.hasOwnProperty.call( schema, 'url' );
			var blockProps = be.useBlockProps ? be.useBlockProps() : {};

			return el( Fragment, {},
				( velden.length || heeftFoto ) ? el( be.InspectorControls, {},
					el( c.PanelBody, { title: 'Instellingen', initialOpen: true },
						heeftFoto ? fotoKiezer( props ) : null,
						velden
					)
				) : null,
				el( 'div', blockProps,
					el( c.Disabled, {},
						el( ServerSideRender, {
							block: naam,
							attributes: props.attributes,
							skipBlockSupportAttributes: true
						} )
					)
				)
			);
		};
	}

	( window.kbjBlokken || [] ).forEach( function ( naam ) {
		if ( ! wp.blocks.getBlockType( naam ) ) {
			// De server kent het blok al; hier krijgt het een gezicht in de editor.
			wp.blocks.registerBlockType( naam, {
				apiVersion: 3,
				edit: maakEdit( naam ),
				save: function () {
					return null;
				}
			} );
		}
	} );
}( window.wp ) );
