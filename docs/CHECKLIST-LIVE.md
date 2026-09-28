# Checklist voor de livegang

Afvinken voordat het domein omgaat. Alles wat hier staat is ooit een keer
vergeten.

## In WordPress

- [ ] **Zoekmachines ontmoedigen staat UIT.** Instellingen, Lezen. Dit is de
      meest gemaakte fout. Zolang dit aanstaat komt de site niet in Google.
- [ ] Taal van WordPress op Nederlands.
- [ ] Tijdzone op Amsterdam.
- [ ] Permalinks op de naam van het bericht, niet op `?p=123`.
- [ ] Site-icoon ingesteld (of het icoon van het thema staat er).
- [ ] De voorbeeldpagina en het voorbeeldbericht weg.
- [ ] Dubbele privacyverklaring weg, en de echte aangewezen onder Instellingen.
- [ ] Plugins die niet actief zijn: verwijderen, niet uitzetten.
- [ ] Thema's die niet gebruikt worden: verwijderen, op één standaardthema na.
- [ ] Een beheerder die geen "admin" heet, met een echt wachtwoord.
- [ ] De e-mail van de site klopt, en de mail komt echt aan.

## Gegevens

- [ ] Alle velden onder Gegevens ingevuld: e-mail, telefoon, plaats, werkgebied,
      KvK, socials.
- [ ] Het adres klopt met het Google-bedrijfsprofiel, tot op de spatie.

## Inhoud

- [ ] Nergens meer "hier komt", "lorem ipsum" of een grijs vlak.
- [ ] Elke pagina heeft één `h1` en dat is de titel.
- [ ] Elke foto heeft een alt-tekst.
- [ ] Alle links werken, ook die in de voet en in de teksten.
- [ ] De 404 bekijken: staat er een weg terug?
- [ ] Algemene voorwaarden en privacyverklaring staan erop en kloppen.

## Formulier

- [ ] Één keer echt versturen en kijken of de mail aankomt.
- [ ] Ook kijken of hij niet in de spam belandt.
- [ ] Na het versturen kom je op de bedanktpagina.
- [ ] Een leeg formulier versturen geeft een nette melding.

## Vindbaarheid

- [ ] `/wp-sitemap.xml` opent en bevat de pagina's.
- [ ] `/llms.txt` geeft status 200 en de inhoud klopt.
- [ ] Elke pagina heeft een eigen titel en een eigen omschrijving.
- [ ] Een pagina delen in WhatsApp laat het goede kaartje zien.
- [ ] De schema.org-gegevens kloppen. Controleer op
      `search.google.com/test/rich-results`.

## Snelheid en techniek

- [ ] `python3 gereedschap/verklein.py` gedraaid, en de `.min`-bestanden staan
      op de server.
- [ ] Foto's als webp, in de maat waarin ze getoond worden.
- [ ] Lighthouse gedraaid: SEO en toegankelijkheid op 100, snelheid boven de 90.
- [ ] HTTPS staat aan en `http` stuurt door naar `https`.
- [ ] `www` en zonder `www` komen op dezelfde plek uit.

## Na de livegang

- [ ] Google Search Console koppelen en de sitemap indienen.
- [ ] Statistieken aanzetten, als de klant dat wil.
- [ ] Automatische back-up instellen.
- [ ] Updates: kernupdates automatisch, thema en plugins met de hand.
- [ ] De oude site doorsturen naar de nieuwe, pagina voor pagina. Niet alles naar
      de startpagina: dan verlies je de posities die die pagina's hadden.
- [ ] Korte handleiding naar de klant.
- [ ] Factuur.
