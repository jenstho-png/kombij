# Start hier

Dit bestand plak je als **eerste bericht** in de nieuwe chat, samen met de zip.
Alles wat hieronder staat is wat de assistent moet weten voordat hij één regel
schrijft. Vul de vier regels onder "De klant" in en stuur het door.

---

## Kopieer dit blok in de nieuwe chat

> Ik ga een WordPress-website bouwen voor een klant. In de zip zit een startpakket:
> een werkend blokthema (`thema/creajt-starter`), het gereedschap om de CSS en JS
> te verkleinen, en documentatie. Lees eerst `WERKWIJZE.md`, `ARCHITECTUUR.md` en
> `BOUWPLAN-DAG1.md` uit de zip.
>
> **De klant**
> - Naam en bedrijf: ...
> - Wat ze doen, in één zin: ...
> - Werkgebied en vestigingsplaats: ...
> - Huisstijl: logo, kleuren en lettertypes zitten wel / niet in de zip
>
> **Wat ik vandaag wil hebben**
> Versie 1 die ik kan laten zien: alle pagina's staan, de startpagina is af,
> de vormgeving klopt met de huisstijl, en het werkt op de telefoon.
>
> **Hoe we werken**
> Hernoem het thema eerst van `creajt-starter` naar de klant, daarna bouwen.
> Test lokaal voordat je zegt dat iets werkt. Houd je aan de regels in
> `WERKWIJZE.md`, vooral: Nederlands in de stem van de klant, geen AI-teksten,
> geen gedachtestreepjes, elke pagina werkt op zichzelf, en op de telefoon
> stapelen beelden nooit zomaar onder elkaar.
>
> Begin met het hernoemen en het omzetten van de huisstijl in `theme.json`.
> Laat me daarna de startpagina zien voordat je verder bouwt.

---

## Wat er in de zip zit

```
startpakket/
  START-HIER.md          dit bestand
  WERKWIJZE.md           de regels waar alles aan moet voldoen
  ARCHITECTUUR.md        hoe het thema in elkaar zit en waarom
  BOUWPLAN-DAG1.md       de volgorde van vandaag, met tijden
  KLANTBRIEFING.md       de vragen die je aan de klant stelt
  LOKAAL-TESTEN.md       hoe je het zonder gedoe lokaal draait en test
  CHECKLIST-LIVE.md      wat er af moet zijn voordat het live gaat
  gereedschap/
    verklein.py          maakt de kleine kopieën van de CSS en de JS
  thema/creajt-starter/  het thema zelf, werkend en getest
```

## Het thema in het kort

Het is een blokthema dat na activeren meteen een site neerzet: startpagina,
Diensten, Werkwijze, Over ons, Contact, Bedankt en Algemene voorwaarden, met het
menu erbij. Er zit geen enkele plugin in. Wat een SEO-plugin en een
formulierplugin normaal doen zit in het thema zelf, in een paar honderd regels
in plaats van een paar megabyte.

Wat er al in zit en werkt:

- **theme.json** met kleuren, lettermaten en afstanden. Eén plek voor de hele
  vormgeving, en de blok-editor leest hem ook, dus de klant ziet in de editor
  wat er op de site komt.
- **Header** met het menu links, het logo in het midden en het menu rechts. Op de
  telefoon vouwt hij open.
- **Contactformulier** zonder plugin, met een honeypot, een nonce en een slot van
  drie berichten per kwartier.
- **SEO en GEO**: titel, omschrijving, Open Graph, canonieke url, schema.org
  (LocalBusiness, WebSite, BreadcrumbList en FAQ) en een `/llms.txt`.
- **Video's** van YouTube en Vimeo die pas laden als iemand erop klikt.
- **Eén instellingenscherm** waar de klant de bedrijfsgegevens beheert, met een
  waarschuwing als "zoekmachines ontmoedigen" nog aanstaat.
- **Snelheid**: de stylesheet gaat in de pagina zelf, de scripts staan op defer,
  emoji en het insluitscript zijn eruit.

Getest op een echte WordPress: alle pagina's geven een 200, geen PHP-meldingen,
geen horizontaal scrollen op 390, 768 en 1280 pixels, geen tekst onder 12,5px,
tapdoelen een vingerbreedte, en alle kleurcombinaties halen 4,5:1.

## Het eerste wat de nieuwe chat moet doen

Hernoemen. Zoek en vervang in de hele themamap:

| zoek | vervang door |
|---|---|
| `creajt-starter` | `naam-van-de-klant` |
| `CJT_` | `XXX_` |
| `cjt_` | `xxx_` |
| `cjt/` | `xxx/` |
| `cjt-` | `xxx-` |

Let op: `cjt-` zit ook in de CSS-klassen en in de JavaScript. Vervang het overal
tegelijk, anders werkt de header niet meer. In één opdracht:

```bash
cd thema/creajt-starter
grep -rl 'cjt\|creajt-starter' . | xargs sed -i \
  -e 's/creajt-starter/naam-van-de-klant/g' \
  -e 's/CJT_/XXX_/g' \
  -e 's/cjt_/xxx_/g' \
  -e 's|cjt/|xxx/|g' \
  -e 's/cjt-/xxx-/g'
cd .. && mv creajt-starter naam-van-de-klant
```

Daarna in `style.css` de themanaam, de omschrijving en de auteur goedzetten, en
in `theme.json` de kleuren en de lettertypes van de klant invullen.
