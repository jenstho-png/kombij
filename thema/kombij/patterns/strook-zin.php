<?php
/**
 * Title: Eén zin die oplicht
 * Slug: kombij/strook-zin
 * Categories: kombij
 * Description: Een grote, rustige zin. Terwijl u scrolt, licht hij woord voor woord op.
 */

$zin     = 'Een eigen kamer. Vaste gezichten. Koffie als u wakker bent. En altijd iemand dichtbij.';
$woorden = '';

$lijst   = explode( ' ', $zin );
$nadruk  = count( $lijst ) - 3;

// De laatste drie woorden krijgen de lichte, blauwe letter, net als "KomBij ons".
foreach ( $lijst as $i => $woord ) {
	$woorden .= '<span' . ( $i >= $nadruk ? ' class="kbj-oplicht__nadruk"' : '' ) . ' style="--w:' . (int) $i . '">' . esc_html( $woord ) . '</span> ';
}

echo kbj_sectie( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	kbj_p( trim( $woorden ), 'kbj-oplicht' ),
	array(
		'klasse' => 'kbj-strook-zin',
		'boven'  => '50',
		'onder'  => '50',
	)
);
