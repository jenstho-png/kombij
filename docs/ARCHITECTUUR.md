# Hoe het thema in elkaar zit

Niet alleen wat waar staat, maar waarom. Dat scheelt de volgende keer opnieuw
uitzoeken.

## De hoofdgedachte

**De vormgeving zit in het thema, de inhoud in WordPress.** De klant kan teksten
en beelden veranderen en kan de vormgeving niet slopen. Daarom staat bijna alles
in `theme.json` en in blokpatronen, en is de blokkenlijst in de editor beperkt
tot wat past.

## De mappen

```
thema/creajt-starter/
  style.css          alleen de themakop, verder niets
  theme.json         kleuren, letters, maten, afstanden. Eén plek.
  functions.php      laadt de rest, verder niets
  inc/               de PHP, per onderwerp een bestand
  blocks/            per blok een block.json met de velden erin
  patterns/          de secties, als PHP-bestanden met blokopmaak
  parts/             header en footer
  templates/         welke onderdelen op welk soort pagina staan
  assets/css/        wat theme.json niet kan
  assets/js/         vier kleine scripts, allemaal op defer
  assets/fonts/      woff2, zelf gehost
  assets/beeld/      logo, icoon, deelbeeld
```

## De bestanden in `inc/`

| bestand | wat het doet |
|---|---|
| `helpers.php` | kleine hulpjes: video-links uitlezen, een afbeelding netjes neerzetten, een tekst inkorten |
| `setup.php` | wat het thema ondersteunt, de stylesheet, de scripts, de blokkenlijst, het opruimen |
| `fonts.php` | de `@font-face`-regels, alleen voor bestanden die er echt staan |
| `opties.php` | het instellingenscherm met de bedrijfsgegevens |
| `menu.php` | de header: menu links, logo in het midden, menu rechts |
| `blocks.php` | de blokken die het thema zelf tekent |
| `paginas.php` | welke pagina's de site heeft, en het menu dat erbij hoort |
| `installatie.php` | wat er één keer moet gebeuren bij het activeren |
| `formulier.php` | het contactformulier, zonder plugin |
| `seo.php` | titel, omschrijving, Open Graph, schema.org, sitemap, llms.txt |

## Keuzes die uitleg verdienen

### De stylesheet gaat in de pagina zelf

Een los CSS-bestand betekent dat de browser eerst de pagina ophaalt, daarin de
verwijzing vindt, en dan nog een keer op pad moet voordat hij iets mag tekenen.
Op een telefoon met een matige verbinding is dat een halve seconde leeg scherm.
Nu staat de opmaak er bij de eerste byte.

Het kost wat: de opmaak zit in elke pagina in plaats van één keer in de cache van
de browser. Ingepakt is dat ongeveer twintig kilobyte, minder dan één foto.

### Naast elk bronbestand een kleine kopie

`gereedschap/verklein.py` maakt naast `thema.css` een `thema.min.css` zonder
commentaar en zonder overbodige witruimte. Het thema gebruikt die kopie alleen
als hij bestaat **én nieuwer is dan de bron**. Vergeet je het verkleinen na een
wijziging, dan valt de site vanzelf terug op het bronbestand en zie je je
wijziging gewoon. Zo kan er nooit een oude opmaak blijven hangen.

### Patronen worden bij het aanmaken uitgeschreven

Een pagina die alleen `<!-- wp:pattern -->` bevat is in de editor één blok.
WordPress zet dat blok op de gewone tekstbreedte, en dan staan secties die op de
site van rand tot rand lopen in de editor in een kolom van 780 pixels, met
kolommen die niet naast elkaar passen en koppen die over vier regels breken.

`cjt_patroon_naar_blokken()` vervangt de verwijzing daarom door de blokken zelf
op het moment dat de pagina wordt aangemaakt. Elke sectie is dan een eigen blok in
de editor, loopt daar net zo breed als op de site, en je kunt erop klikken.

### Het menu wordt zelf getekend

Het navigatieblok van WordPress kan niet om een logo heen gesplitst worden. Door
het menu in `inc/menu.php` zelf te tekenen kunnen de items in tweeën en hangt het
logo er precies tussen. De klant beheert het menu gewoon in de site-editor onder
"Hoofdmenu"; het blok leest datzelfde menu uit.

`cjt_menu_gelijkhouden()` houdt de volgorde gelijk aan die in `cjt_paginas()`,
maar alleen zolang niemand het menu met de hand heeft aangepast. Daarom slaan we
bij het schrijven een afdruk op: wijkt de inhoud daarvan af, dan blijven we eraf.

### De hoogte van de balk wordt gemeten, niet geschat

Een anker onder een vaste balk landt anders precies eronder, en dan lijkt de kop
te ontbreken. `koptekst.js` meet de echte hoogte en zet hem in
`--cjt-kophoogte`. Schatten met `vw` gaat mis zodra het menu over twee regels
valt.

### De beweging staat achter een klasse die JavaScript zet

De beginstand van elke `.cjt-reveal` (onzichtbaar, iets naar beneden) staat in de
CSS achter `html.cjt-js`. Die klasse zet `reveal.js` als eerste. Draait het
script niet, dan is alles gewoon zichtbaar. Zonder die truc kijkt iemand met
JavaScript uit naar een lege pagina.

In de editor staat alles altijd zichtbaar; dat regelt `assets/css/editor.css`.

### Video's laden pas bij de klik

Een ingesloten YouTube- of Vimeo-speler is bijna een megabyte aan script en een
handvol cookies, ook als niemand op play drukt. `cjt-video-facade` zet eerst
alleen de posterfoto neer met een knop erop. Bij het naderen van de muis legt hij
alvast de verbinding, en bij de klik komt de echte speler erin, meteen spelend.

Het staat in een schaduw-DOM, zodat de opmaak van de speler nooit met de rest van
de pagina kan botsen.

### Geen SEO-plugin

Een SEO-plugin doet drie dingen: een titel en een omschrijving meegeven, de
gegevens voor het deelkaartje zetten, en de informatie over het bedrijf in
schema.org-vorm neerzetten. Dat staat nu in `inc/seo.php`, in een paar honderd
regels in plaats van een paar megabyte.

Draait er tóch een SEO-plugin, dan stapt `cjt_seo_plugin_actief()` opzij voor de
koppen en de omschrijving. Twee titels in één pagina is erger dan geen.

### Geen formulierplugin

Een formulier is hier één ding: een bericht dat in de mail moet komen. De
beveiliging zit in drie lagen: een nonce, een onzichtbaar veld dat alleen bots
invullen, en een slot van drie berichten per kwartier per IP-adres. Wie boven dat
slot komt krijgt hetzelfde bedankje te zien als iemand die wel doorkomt, want een
bot die een foutmelding krijgt probeert het opnieuw.

Het formulier is een blok en geen HTML in een pagina, omdat WordPress bij het
opslaan formulieren uit de inhoud filtert.

### Het instellingenscherm staat in de zijbalk

Bewust niet weggestopt onder Instellingen. Dit is het enige scherm dat de klant
echt nodig heeft, dus mag het gevonden worden. Er staan twee waarschuwingen op
voor instellingen die ergens anders in WordPress zitten en juist daarom vergeten
worden: "zoekmachines ontmoedigen" en de taal van WordPress.

### Let op bij het opschonen van opties

WordPress roept de `sanitize_callback` van een optie soms **twee keer** aan met
dezelfde invoer. Alles in `cjt_optie_schoonmaken()` moet daartegen kunnen. Geef
nooit iets terug dat bij een tweede ronde iets anders oplevert, want dan staat er
na het opslaan ineens `["Array"]` in je database.

## Iets nieuws toevoegen

**Een sectie**: een bestand in `/patterns` met een koptekst erboven (`Title`,
`Slug`, `Categories`). Zet hem daarna in `cjt_paginas()` of in
`patterns/homepage.php`.

**Een blok met velden**: een map in `/blocks` met een `block.json` en een
render-functie in `inc/blocks.php`. Zet hem in `cjt_register_blocks()` en in
`cjt_allowed_blocks()`. Geef elk veld dat de klant mag vullen
`"role": "content"`, dan kan hij het in de editor aanpassen zonder de opmaak te
raken.

**Een gegeven dat de klant beheert**: een veld in `cjt_optie_velden()`. Het scherm
en de opschoning lopen vanzelf mee.
