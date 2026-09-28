# Werkwijze

De regels waar elke site aan moet voldoen. Ze zijn niet onderhandelbaar en ze
zijn er allemaal omdat er ooit iets misging zonder.

## Teksten

- **Alles in het Nederlands**, in de stem van de klant. Schrijf zoals zij het aan
  de telefoon zouden zeggen.
- **Geen AI-teksten.** Geen "in het hart van", geen "naadloze ervaring", geen
  "wij ontzorgen". Als een zin in elke folder kan staan, hoort hij hier niet.
- **Geen gedachtestreepjes.** Nergens, ook niet in een tussenzin. Gebruik een
  komma, een dubbele punt of een nieuwe zin.
- **Het woord "me" niet gebruiken** in de teksten op de site.
- Korte zinnen. Een alinea van drie regels leest op een telefoon prettiger dan
  een blok van acht.
- Elke kop is een echte kop (`h1`, `h2`, `h3`) in de goede volgorde. Eén `h1`
  per pagina, en dat is de titel.
- Geef echte antwoorden. "Neem contact op voor een offerte" is het antwoord dat
  mensen wegklikken. Noem een richtbedrag, een termijn, een werkgebied.

## Pagina's

- **Elke pagina werkt op zichzelf.** Iemand die via Google op "Diensten" binnenkomt
  moet daar alles vinden, zonder eerst naar de startpagina te hoeven. Dus: een
  eigen kop, een eigen intro, en onderaan de stap naar contact.
- Elke pagina eindigt met één duidelijke vraag en één knop.
- De startpagina is het verhaal, de losse pagina's zijn de hoofdstukken.

## Beeld en video

- **Veel ruimte voor beeld en video.** Een sectie met alleen tekst is de
  uitzondering, niet de regel.
- Foto's als webp, in de maat waarin ze getoond worden, met een alt-tekst die
  beschrijft wat er te zien is.
- Video's nooit rechtstreeks insluiten. Gebruik het video-blok uit het thema: dat
  laadt de speler pas als iemand erop klikt. Een ingesloten YouTube-speler is
  bijna een megabyte, ook als niemand hem aanzet.
- Vimeo als er ondertiteling op moet, YouTube als het vooral om vindbaarheid gaat.

## Telefoon

- **Beelden stapelen nooit zomaar onder elkaar.** Wordt een rij te smal, dan wordt
  het een strook die je opzij veegt, of een raster van twee, of een compositie
  waarin het beeld iets doet. Niet vier foto's onder elkaar.
- Testen op 320, 360, 390 en 430 pixels breed, en op de maten ertussen tot 1280.
  De fouten zitten bijna altijd op een tussenmaat, niet op de bekende maten.
- Nooit horizontaal scrollen op de pagina zelf.
- Geen tekst onder 12,5 pixels.
- Tapdoelen minstens 44 pixels hoog. Een link in de voet is anders zestien pixels
  en die raak je met een duim niet.
- Formuliervelden op 16 pixels, anders zoomt Safari op de iPhone het hele scherm
  in zodra je erin tikt.

## Vindbaarheid

- Zonder SEO-plugin. Het thema doet het zelf: titel, omschrijving, Open Graph,
  canonieke url en schema.org.
- Plaatsnamen in de tekst, niet alleen in de voet. Daar leunt de lokale
  vindbaarheid op.
- Veelgestelde vragen op de pagina zelf, als `details`-blokken. Het thema geeft
  ze dan ook als FAQ aan Google mee, en het antwoord staat er echt en niet alleen
  in de opmaak.
- `/llms.txt` laten staan. Steeds meer mensen vragen aan een taalmodel wat ze
  vroeger googelden, en zo'n model leest liever één overzichtelijk tekstbestand.

## Snelheid

- Zo min mogelijk plugins. Elke plugin is code die je niet geschreven hebt en
  niet kunt opruimen.
- Lettertypes zelf hosten, nooit via Google Fonts. Dat is een verzoek naar een
  server in de VS bij elke bezoeker, en het kost een extra verbinding voordat er
  één letter staat.
- Na elke wijziging in de CSS of de JS `python3 gereedschap/verklein.py` draaien.
  Vergeet je het, dan valt de site vanzelf terug op het bronbestand, dus er gaat
  niets kapot. Het is alleen groter.

## Toegankelijkheid

- Kleurcontrast minstens 4,5:1 voor gewone tekst. Controleer het, schat het niet.
- Wie met het toetsenbord navigeert moet zien waar hij is.
- Beweging uit voor wie dat in zijn systeem heeft aangezet.
- Zonder JavaScript is de site zichtbaar. De beweging bij het scrollen mag nooit
  betekenen dat iemand naar een lege pagina kijkt.

## Voor de klant

- Eén instellingenscherm met de bedrijfsgegevens. Verander je ze daar, dan
  kloppen ze overal.
- De blokkenlijst in de editor is beperkt tot wat past. Minder keuze is hier een
  functie: de vormgeving blijft staan.
- Lever een korte handleiding mee. Wat kan de klant zelf, en wat niet.
