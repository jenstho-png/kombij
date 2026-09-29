/*
 * Een KomBij-paneel in de zijbalk van de editor, onder het tabblad Pagina.
 *
 * Zegt in gewone woorden hoe u deze pagina aanpast, wat er juist niet op deze
 * pagina staat maar onder KomBij, en geeft knoppen ernaartoe. De teksten komen
 * van de server (window.kbjHulp), dus per pagina precies wat er speelt.
 */
( function ( wp ) {
	'use strict';

	var hulp = window.kbjHulp;

	if ( ! wp || ! wp.plugins || ! hulp ) {
		return;
	}

	var Paneel = ( wp.editor && wp.editor.PluginDocumentSettingPanel ) || ( wp.editPost && wp.editPost.PluginDocumentSettingPanel );

	if ( ! Paneel ) {
		return;
	}

	var el = wp.element.createElement;
	var Button = wp.components.Button;

	function knop( link, hoofd ) {
		return el( Button, {
			key: link[ 1 ],
			href: link[ 1 ],
			variant: hoofd ? 'primary' : 'secondary',
			target: link[ 2 ] ? '_blank' : undefined,
			style: { width: '100%', justifyContent: 'center', marginTop: '8px' }
		}, link[ 0 ] );
	}

	function Hulp() {
		return el( Paneel, { name: 'kbj-hulp', title: 'KomBij: deze pagina aanpassen', className: 'kbj-hulp', initialOpen: true },
			hulp.tip ? el( 'p', { style: { marginTop: 0 } }, hulp.tip ) : null,
			el( 'ul', { style: { listStyle: 'disc', paddingLeft: '18px', margin: '0 0 12px' } },
				hulp.stappen.map( function ( stap ) {
					return el( 'li', { key: stap, style: { marginBottom: '6px' } }, stap );
				} )
			),
			hulp.elders.length ? el( 'p', { style: { margin: '14px 0 0', fontWeight: 600 } }, 'Staat niet op deze pagina, maar onder KomBij:' ) : null,
			hulp.elders.map( function ( link ) {
				return knop( link, false );
			} ),
			el( 'hr', { style: { margin: '16px 0 4px' } } ),
			knop( hulp.bekijk, false ),
			knop( hulp.overzicht, false )
		);
	}

	wp.plugins.registerPlugin( 'kbj-hulp', { render: Hulp, icon: 'heart' } );

	// WordPress onthoudt welke panelen dicht zijn. Dit paneel staat bij het
	// openen van een pagina altijd open, anders ziet niemand het.
	wp.domReady( function () {
		var editor = wp.data.select( 'core/editor' );
		var naam = 'kbj-hulp/kbj-hulp';

		if ( editor && editor.isEditorPanelOpened && ! editor.isEditorPanelOpened( naam ) ) {
			wp.data.dispatch( 'core/editor' ).toggleEditorPanelOpened( naam );
		}
	} );
}( window.wp ) );
