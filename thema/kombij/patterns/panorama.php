<?php
/**
 * Title: Panorama van de kerk
 * Slug: kombij/panorama
 * Categories: kombij
 * Description: Het schip van de kerk over de volle breedte, dat tijdens het scrollen opengaat als een raam.
 */

$bestand = 'kerk-interieur.webp';
$maat    = wp_getimagesize( KBJ_DIR . '/assets/foto/' . $bestand );
?>
<!-- wp:group {"tagName":"section","align":"full","className":"kbj-panorama","layout":{"type":"default"}} -->
<section class="wp-block-group alignfull kbj-panorama"><!-- wp:html -->
<div class="kbj-panorama__raam"><img src="<?php echo esc_url( KBJ_URI . '/assets/foto/' . $bestand ); ?>" alt="Het lichte schip van de Lambertuskerk, met witte gewelven en gekleurde ramen" width="<?php echo (int) $maat[0]; ?>" height="<?php echo (int) $maat[1]; ?>" loading="lazy" decoding="async" style="object-position:65% 50%"><div class="kbj-panorama__tekst"><p>Waar het dorp vroeger samenkwam, is nu plek voor zorg.<span>De H. Lambertuskerk in Maasbommel, gebouwd in 1868 en 1869.</span></p></div></div>
<!-- /wp:html --></section>
<!-- /wp:group -->
