# CREAjt Starter

Een blokthema als vertrekpunt voor een klantsite. Geen plugins nodig: de
vindbaarheid, het contactformulier en het beheerscherm zitten in het thema.

## Installeren

1. De map in `wp-content/themes/` zetten.
2. Het thema activeren.

Het thema zet daarna zelf de startpagina neer, maakt de pagina's aan, vult het
menu en zet de permalinks goed. Bestaat er al iets, dan blijft het thema eraf.

## Eerst hernoemen

Dit thema heet `creajt-starter` en gebruikt overal het voorvoegsel `cjt`. Voor
een echte klant hernoem je dat eerst, anders krijg je bij de tweede klant twee
thema's die elkaar in de weg zitten:

```bash
grep -rl 'cjt\|creajt-starter' . | xargs sed -i \
  -e 's/creajt-starter/naam-van-de-klant/g' \
  -e 's/CJT_/XXX_/g' \
  -e 's/cjt_/xxx_/g' \
  -e 's|cjt/|xxx/|g' \
  -e 's/cjt-/xxx-/g'
```

`cjt-` zit ook in de CSS-klassen en in de JavaScript, dus vervang het overal
tegelijk. Daarna in `style.css` de naam, de omschrijving en de auteur goedzetten.

## Wat je nog moet aanleveren

In `assets/beeld/`:

| bestand | waarvoor |
|---|---|
| `logo.svg` | het logo in de header en de voet. Zet de kleuren op `currentColor`, dan kleurt hij mee met de tekst eromheen. |
| `logo.png` | gaat één keer de mediabibliotheek in als sitelogo |
| `icoon.svg`, `icoon-32.png`, `icoon-180.png` | het icoontje in het tabblad |
| `delen.jpg` | het beeld dat meegaat als iemand een pagina deelt (1200 bij 630) |

In `assets/fonts/`, als woff2, met de namen uit `inc/fonts.php`:
`kop-light.woff2`, `kop-regular.woff2`, `kop-italic.woff2`,
`tekst-regular.woff2`, `tekst-medium.woff2`.

Staat een bestand er niet, dan wordt het niet ingeladen en valt de site netjes
terug op de reserveletters uit `theme.json`. Geen 404, geen kapotte pagina.

## De vormgeving aanpassen

Bijna alles staat in `theme.json`: kleuren, lettertypes, lettermaten en
afstanden. Dat is ook wat de blok-editor leest, dus de klant ziet in de editor
wat er op de site komt.

Wat `theme.json` niet kan staat in `assets/css/thema.css`. Draai na elke
wijziging:

```bash
python3 gereedschap/verklein.py
```

Vergeet je dat, dan valt de site vanzelf terug op het bronbestand. Er gaat dus
niets kapot, het is alleen groter.

## Voor de klant

Onder **Gegevens** in de zijbalk staan de bedrijfsgegevens: e-mail, telefoon,
adres, werkgebied, KvK en de socials. Die komen terug in de voet, op de
contactpagina en in de informatie die Google uitleest.

Op datzelfde scherm staat een waarschuwing als "zoekmachines ontmoedigen" nog
aanstaat of als de taal van WordPress niet op Nederlands staat. Dat zijn de twee
instellingen die het vaakst vergeten worden.

## Wat er in zit

- Header met het menu links, het logo in het midden en het menu rechts
- Contactformulier zonder plugin, met honeypot, nonce en een slot tegen spam
- Video's van YouTube en Vimeo die pas laden als iemand erop klikt
- Titel, omschrijving, Open Graph, canonieke url en schema.org
- `/llms.txt` voor taalmodellen
- Veelgestelde vragen als `details`-blokken, die het thema als FAQ meegeeft
- Beweging bij het scrollen, die uitgaat voor wie dat in zijn systeem heeft gezet
- Acht patronen om pagina's mee te bouwen

Meer over waarom het zo in elkaar zit: `ARCHITECTUUR.md` in het startpakket.
