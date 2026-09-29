# Aanpassingen meenemen bij opnieuw opbouwen

Uitleg om deze functie in een andere WordPress-site te laten bouwen. Onderaan staat een opdracht die u zo in Claude kunt plakken. Het voorbeeldbestand `aanpassingen-meenemen.php` in deze map werkt al en mag als voorbeeld worden meegegeven.

---

## Het probleem

Een blokthema zet pagina's klaar met blokken, bijvoorbeeld uit patronen. Daarna gebeuren er twee dingen:

1. De klant past pagina's aan in de editor: een tekst, een foto, een blok weg, een eigen alinea erbij.
2. U maakt een nieuwe versie van het thema met een betere opmaak.

WordPress verandert pagina's die al bestaan niet vanzelf. Bouwt u ze opnieuw op, dan is alles weg wat de klant heeft gedaan. De functie lost dat op: **nieuwe opmaak erin, en het werk van de klant blijft staan.**

## Wat het moet kunnen

| Wat de klant deed | Wat er gebeurt bij opnieuw opbouwen |
|---|---|
| Tekst, knop, link of foto veranderd | Komt op dezelfde plek terug in de nieuwe opmaak |
| Blok weggehaald | Blijft weg |
| Eigen blok toegevoegd | Komt achter hetzelfde blok als waar het eerst achter stond |
| Blok gedupliceerd | De kopie telt als eigen blok en blijft staan |
| Niets veranderd | Pagina krijgt gewoon de nieuwe opmaak |
| Iets wat nergens meer past | Wordt gemeld; staat nog in de revisies |

Verder moet het:
- altijd eerst de huidige versie in de revisies zetten;
- per pagina laten zien of de klant iets heeft aangepast;
- na afloop per pagina een verslag geven ("2 aanpassingen meegenomen, 1 eigen blok");
- twee keer achter elkaar opbouwen zonder dat er iets dubbel komt.

## Hoe het werkt, in gewone woorden

**1. Elk blok krijgt een vast kenmerk.**
Bij het opbouwen krijgt elk los blok (kop, alinea, knop, foto) een onzichtbaar kenmerk. Dat staat in de blokinstellingen, onder `metadata`. WordPress bewaart dat gewoon als de klant in de editor opslaat. Groepen (secties, kolommen) krijgen een apart merkje, zodat het thema weet dat ze van het thema zijn.

Het kenmerk wordt gemaakt uit:
- de naam van de pagina;
- het soort blok;
- de standaardtekst van het blok;
- een volgnummer als dezelfde tekst vaker voorkomt.

Blijft de standaardtekst in een nieuwe versie gelijk, dan blijft het kenmerk gelijk. Het blok is dan in de nieuwe opmaak terug te vinden, ook als het ergens anders staat.

**2. De pagina onthoudt hoe elk blok eruitzag.**
Per kenmerk wordt een vingerafdruk opgeslagen in de post meta. Die vingerafdruk bestaat uit de tekst, de links, de foto en de instellingen van het blok.

**3. Bij opnieuw opbouwen wordt vergeleken.**
Het thema loopt door de huidige pagina:
- **Blok met kenmerk en een andere vingerafdruk:** door de klant veranderd.
- **Kenmerk dat niet meer in de pagina staat:** door de klant weggehaald.
- **Blok zonder kenmerk:** door de klant toegevoegd. Het thema onthoudt achter welk blok het stond.

Dan maakt het de nieuwe opmaak, met nieuwe kenmerken, en loopt die door:
- veranderd blok: vervangen door de versie van de klant;
- weggehaald blok: overslaan;
- eigen blok: direct achter zijn oude buurblok zetten.

**4. De vingerafdrukken blijven die van het thema.**
Na het opbouwen slaat het thema de vingerafdrukken van zijn eigen standaardversie op, niet die van de klant. Zo telt een aanpassing de volgende keer weer als aanpassing en gaat hij opnieuw mee.

## Valkuilen

Deze zijn allemaal in de praktijk tegengekomen. Ze moeten in de bouwopdracht staan, anders gaat het mis.

1. **`wp_slash()` gebruiken bij opslaan.** `wp_insert_post` en `wp_update_post` halen backslashes weg. Blokinstellingen bevatten JSON met `"` en dergelijke, en die breken dan. Geef dus altijd `wp_slash( $inhoud )` mee.
2. **De editor laat standaardwaarden weg.** Staat een instelling op de standaardwaarde van het blok (bijvoorbeeld `"alt":""`), dan schrijft de editor die niet weg. Zonder correctie denkt de functie dat de klant iets heeft veranderd. Oplossing: laat instellingen die gelijk zijn aan de `default` uit de blokregistratie, of leeg zijn, buiten de vingerafdruk.
3. **Opmaak telt niet mee.** Laat `class` en `style` in de HTML, en `className`, `style`, `lock` en `metadata` in de instellingen, buiten de vingerafdruk, en ook verschillen in witruimte en in hoe tekens geschreven zijn (`&amp;` tegenover `&`). Anders telt elke keer opslaan in de editor als aanpassing.
4. **Groepen merken.** Haalt de klant alle blokken uit een sectie, dan lijkt de lege sectie zonder merkje een eigen blok. Hij komt dan dubbel terug.
5. **`innerContent` bijwerken.** Een groep bewaart zijn binnenblokken als `null` op de plekken in `innerContent`. Komen er blokken bij of gaan er af, dan moet het aantal nulls meeveranderen. Anders valt er een blok weg of breekt de HTML.
6. **Dubbele kenmerken.** Dupliceert de klant een blok, dan heeft de kopie hetzelfde kenmerk. Laat alleen de eerste tellen; de rest is een eigen blok.
7. **Pagina's van vóór de functie.** Die hebben nog geen kenmerken. Daar valt niets te vergelijken. Meld dat, en laat ze standaard uit staan in de keuzelijst: de eerste keer opbouwen gaat hun eigen werk verloren (wel in de revisies).
8. **Zelf standaardteksten veranderen.** Verandert u in een nieuwe versie de standaardtekst van een blok dat de klant ook heeft aangepast, dan verandert het kenmerk. De aanpassing kan dan niet terug op zijn plek en moet als "paste niet meer" gemeld worden.

## Wat er in het beheer komt

Een blok op een beheerpagina, bijvoorbeeld "Na een nieuwe versie van het thema", met:
- een lijst met alle pagina's van het thema, elk met een vinkje en een label:
  - "niet aangepast";
  - "aangepast, gaat mee";
  - "aanpassingen gaan niet mee" (pagina zonder kenmerken);
- één knop "Gekozen pagina's opnieuw opbouwen" (met nonce en rechtencheck);
- na afloop een melding met het verslag per pagina, met een link naar die pagina.

## Hoe u test of het werkt

Test in de echte editor, niet alleen met PHP. Het gaat erom wat de editor wegschrijft.

1. Bouw een pagina op en controleer dat de site er precies hetzelfde uitziet als zonder kenmerken.
2. Open de pagina in de editor en doe dit:
   - verander een tekst;
   - haal een kop weg;
   - voeg een eigen alinea toe;
   - wissel een foto;
   - sla op.
3. Controleer dat de kenmerken er na opslaan nog in staan.
4. Doe alsof er een nieuwe versie is: zet een extra sectie bovenaan en verander klassen in de groepen. Bouw opnieuw op.
5. Controleer:
   - nieuwe opmaak erin;
   - alle vier de aanpassingen erin;
   - kop weg;
   - eigen alinea op de goede plek;
   - pagina geldig (`serialize_blocks( parse_blocks( $c ) ) === $c`).
6. Bouw nog een keer op en controleer dat er niets dubbel is.
7. Open een pagina alleen in de editor en sla op zonder iets te veranderen. Controleer dat hij op "niet aangepast" blijft staan.

---

## Opdracht om te plakken

> Bouw in dit WordPress-thema een functie "aanpassingen meenemen bij opnieuw opbouwen".
>
> **Situatie:** het thema maakt pagina's aan met blokken (uit patronen). Bij een nieuwe versie van het thema wil ik die pagina's opnieuw kunnen opbouwen met de nieuwe opmaak, zonder dat verloren gaat wat de klant in de editor heeft aangepast.
>
> **Wat het moet doen:**
> - Veranderde teksten, knoppen, links en foto's komen op dezelfde plek terug in de nieuwe opmaak.
> - Weggehaalde blokken blijven weg.
> - Eigen blokken komen achter hetzelfde blok als waar ze eerst achter stonden.
> - Wat niet meer past, wordt gemeld.
> - De huidige versie gaat altijd eerst de revisies in.
>
> **Aanpak:**
> - Geef bij het aanmaken en opbouwen elk los blok een vast kenmerk in `attrs.metadata`. Maak het uit: paginanaam + bloknaam + standaardtekst (of de instellingen als er geen tekst is) + volgnummer voor dubbele.
> - Geef groepen een apart merkje in `metadata`.
> - Sla per pagina in post meta een lijst op: kenmerk => vingerafdruk (md5 van bloknaam, HTML en instellingen).
> - Bij opnieuw opbouwen:
>   - loop de huidige pagina door en bepaal welke blokken veranderd zijn, welke kenmerken ontbreken (weggehaald), en welke blokken geen kenmerk hebben (eigen, met het kenmerk van het blok ervoor);
>   - maak de nieuwe opmaak met kenmerken en vul die in: veranderde blokken vervangen, weggehaalde overslaan, eigen blokken achter hun buurblok;
>   - sla daarna de vingerafdrukken van de standaardversie van het thema op, niet die van de klant.
>
> **Let op deze valkuilen:**
> - `wp_slash()` bij `wp_insert_post` en `wp_update_post`.
> - Laat buiten de vingerafdruk: `class`/`style` in de HTML, en `className`, `style`, `lock` en `metadata` in de instellingen.
> - Laat ook buiten de vingerafdruk: instellingen die gelijk zijn aan hun `default` in de blokregistratie of leeg zijn (de editor schrijft die niet weg).
> - Maak witruimte en HTML-entities gelijk voordat je vergelijkt.
> - Werk `innerContent` bij (de nulls) als het aantal binnenblokken verandert.
> - Laat bij dubbele kenmerken alleen de eerste tellen.
> - Pagina's zonder kenmerken: meld dat eigen werk de eerste keer niet mee kan.
>
> **In het beheer:** een lijst met alle pagina's van het thema met vinkje en label ("niet aangepast", "aangepast, gaat mee", "aanpassingen gaan niet mee"). Daaronder één knop om de gekozen pagina's op te bouwen, en daarna een verslag per pagina.
>
> **Test in de echte editor** (Playwright):
> - tekst veranderen, kop weghalen, eigen alinea toevoegen, foto wisselen, opslaan;
> - daarna een nieuwe opmaak nadoen en opnieuw opbouwen;
> - controleren dat alles klopt en dat twee keer opbouwen niets verdubbelt;
> - controleren dat alleen opslaan zonder wijziging als "niet aangepast" telt.
>
> Het bestand `aanpassingen-meenemen.php` is een werkend voorbeeld; gebruik het als basis en pas de namen aan dit thema aan.
