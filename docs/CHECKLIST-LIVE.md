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
- [ ] De stylesheet, scripts, fonts en foto's krijgen een lange cache-header.
      Controleer met `curl -I .../assets/css/thema.min.css`: er hoort
      `Cache-Control: max-age=31536000` (of een `Expires` ver in de toekomst)
      te staan. Veilig, want elk bestand krijgt een nieuwe `?ver=` als het
      verandert. Staat het er niet en draait de host Apache, zet dan in
      `.htaccess`:

      ```
      <IfModule mod_expires.c>
        ExpiresActive On
        ExpiresByType text/css "access plus 1 year"
        ExpiresByType application/javascript "access plus 1 year"
        ExpiresByType font/woff2 "access plus 1 year"
        ExpiresByType image/webp "access plus 1 year"
        ExpiresByType image/svg+xml "access plus 1 year"
      </IfModule>
      ```
- [ ] `www` en zonder `www` komen op dezelfde plek uit.

## Na de livegang

- [ ] Google Search Console koppelen en de sitemap indienen.
- [ ] Statistieken aanzetten, als de klant dat wil.
- [ ] Automatische back-up instellen.
- [ ] Updates: kernupdates automatisch, thema en plugins met de hand.
- [x] De oude site doorsturen naar de nieuwe, pagina voor pagina. Zit in het
      thema (`inc/doorsturen.php`): de vier oude pagina's en acht pdf's van
      kombijmaasbommel.nl sturen blijvend (301) door.
- [ ] Komt de nieuwe site in dezelfde WordPress als de oude? Zet dan de oude
      pagina's (Zorgeloos wonen, Zorgeloos logeren, Werken in de zorg,
      Openingstijden) op concept of verwijder ze. Het doorsturen werkt ook als
      ze blijven staan, maar dan staan ze dubbel in het beheer.
- [ ] Na de livegang de oude adressen één keer zelf openen en kijken of ze op de
      goede pagina uitkomen.
- [ ] Korte handleiding naar de klant.
- [ ] Factuur.
