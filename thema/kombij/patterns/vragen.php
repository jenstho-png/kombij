<?php
/**
 * Title: Veelgestelde vragen, startpagina
 * Slug: kombij/vragen
 * Categories: kombij
 * Description: De vragen die mensen het vaakst stellen. Het thema geeft ze ook als FAQ aan Google mee.
 */

echo kbj_vragenblok( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	'Vragen die we vaak horen',
	array(
		array( 'Wat is het verschil tussen wonen, logeren en dagbesteding?', 'Bij wonen verhuist u naar KomBij en krijgt u 24 uur per dag zorg. Bij logeren verblijft u een paar nachten bij ons, zodat uw mantelzorger even rust heeft. Bij dagbesteding komt u overdag en gaat u aan het eind van de middag weer naar huis.' ),
		array( 'Heb ik een indicatie nodig?', 'Voor wonen heeft u een WLZ-indicatie nodig. Voor logeren en dagbesteding kan dat een WLZ- of WMO-indicatie zijn. Zonder indicatie kunt u ook particulier bij ons terecht. We helpen u graag met de aanvraag.' ),
		array( 'Kan mijn partner mee logeren?', 'In overleg kan dat. Uw partner logeert dan mee in een tweepersoonskamer, tegen een vast bedrag per dag. Dat bedrag is voor het verblijf en de maaltijden. Zorg zit er niet in.' ),
	)
);
