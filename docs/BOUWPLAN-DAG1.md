# Bouwplan voor dag 1

Doel: aan het eind van de dag staat er een versie 1 die je kunt laten zien. Niet
af, wel echt. Alle pagina's staan, de startpagina is klaar, de huisstijl klopt en
het werkt op een telefoon.

De tijden zijn richtlijnen bij een dag van ongeveer acht uur. Loop je uit, laat
dan altijd het laatste blok staan: testen is wat het verschil maakt tussen iets
laten zien en je ergens voor schamen.

---

## 1. Opzetten (30 minuten)

- Thema hernoemen van `creajt-starter` naar de klant, met de zoek-en-vervang uit
  `START-HIER.md`.
- Lokale WordPress klaarzetten, zie `LOKAAL-TESTEN.md`.
- Thema activeren. Er staat nu al een site met alle pagina's en het menu.
- Git: een map, `git init`, eerste commit. Dan kun je altijd terug.

**Klaar als:** je de site lokaal in de browser ziet, met alle pagina's in het menu.

## 2. Huisstijl erin (60 minuten)

- Logo als SVG in `assets/beeld/logo.svg`. Haal de vaste kleuren eruit of zet ze
  op `currentColor`, dan neemt het logo de kleur van de tekst eromheen over en
  klopt hij zowel over een donkere hero als op een lichte balk.
- Ook `logo.png` erbij voor de mediabibliotheek, en `icoon.svg`, `icoon-32.png`
  en `icoon-180.png` voor het tabblad.
- Lettertypes als woff2 in `assets/fonts`, met de namen die in `inc/fonts.php`
  staan. Geen woff2? Zet ze om, dat is een minuut werk.
- Kleuren in `theme.json` vervangen. Controleer het contrast, schat het niet.
- Lettermaten nalopen: past de kop van de klant in de `hero`-maat?

**Klaar als:** de site voelt als de klant en niet meer als het startpakket.

## 3. Structuur en teksten (90 minuten)

- `inc/paginas.php` aanpassen: welke pagina's heeft deze klant echt nodig, hoe
  heten ze, en welke secties staan erop.
- `patterns/homepage.php` op volgorde zetten.
- Teksten schrijven. Niet later, nu. Een site met lorem ipsum laat je niet zien
  en een site met AI-teksten ook niet.
- Per pagina: een kop, een intro, de secties, en onderaan de stap naar contact.

**Klaar als:** je elke pagina kunt lezen zonder ergens "hier komt" tegen te komen.

## 4. Beeld (60 minuten)

- Foto's van de klant erin, als webp, in de juiste maat, met alt-teksten.
- Heb je ze nog niet? Gebruik tijdelijke beelden die qua sfeer kloppen en zeg
  erbij dat ze tijdelijk zijn. Grijze vlakken maken een goede site lelijk.
- Video's: plak de link in het videoblok, het thema haalt de posterfoto zelf op.

**Klaar als:** er geen lege plekken meer staan.

## 5. Secties die deze klant nodig heeft (90 minuten)

Alles wat het startpakket niet heeft en deze klant wel. Denk aan prijzen, een
portfolio, een team, openingstijden, een kaart. Bouw ze als patroon in
`/patterns`, niet als los stuk HTML in een pagina: dan kun je ze hergebruiken en
blijven ze meelopen als je het thema bijwerkt.

Is het iets wat de klant zelf moet kunnen aanpassen, maak er dan een blok van met
velden, of zet het als veld op het instellingenscherm in `inc/opties.php`.

**Klaar als:** de site het verhaal van deze klant vertelt en niet dat van een
willekeurig bedrijf.

## 6. Telefoon (45 minuten)

- 320, 360, 390 en 430 pixels, en de maten daartussen tot 1280.
- Nergens horizontaal scrollen.
- Beelden stapelen niet zomaar onder elkaar.
- Tapdoelen een vingerbreedte.
- Het menu opent en sluit, en sluit ook als je op een link tikt.

**Klaar als:** je met je telefoon door de hele site kunt zonder ergens te
schrikken.

## 7. Nakijken en opleveren (45 minuten)

- `python3 gereedschap/verklein.py` draaien.
- Contactformulier één keer echt versturen en kijken of de mail aankomt.
- Alle links nalopen, ook die in de voet.
- De 404 bekijken.
- `/llms.txt` opvragen: staat er 200 boven en klopt de inhoud?
- Bedrijfsgegevens invullen onder Gegevens.
- Lighthouse draaien. Onder de 90 op snelheid is een signaal, geen ramp, maar
  kijk dan wel waar het aan ligt.

**Klaar als:** je de link kunt sturen zonder er iets bij uit te hoeven leggen.

---

## Wat je vandaag níet doet

Dit hoort bij versie 2 en verder, en het kost je anders je deadline:

- De algemene voorwaarden en de privacyverklaring uitschrijven. Zet de pagina's
  neer, vul ze later.
- Analytics, cookiemelding, Search Console.
- Een eigen beheerscherm voor iets wat de klant één keer per jaar aanpast.
- Animaties verfijnen. De beweging die erin zit is goed genoeg om te laten zien.
- Zoeken naar de perfecte foto. Kies er een en ga door.

## Als het krap wordt

Laat in deze volgorde vallen:

1. De extra secties uit stap 5 (laat de standaardsecties staan).
2. De pagina Werkwijze (zet hem op concept).
3. Video's (zet er een foto neer).

Laat nooit vallen: de teksten, de telefoon, en het contactformulier. Dat zijn de
drie dingen waar een klant meteen naar kijkt.
