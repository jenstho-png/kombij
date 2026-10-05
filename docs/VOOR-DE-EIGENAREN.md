# KomBij: punten voor de eigenaren

Voor het overleg op dinsdag 29 september 2026. Eerst wat KomBij zelf moet
regelen, daarna wat we van KomBij nodig hebben, en als laatste ideeën voor
later.

---

## 1. Zorginstellingen die de site niet kunnen openen

Laptops van zorginstellingen hebben een webfilter. Een nieuwe of kleine site
staat daarin vaak op "onbekend" en wordt dan geblokkeerd. Een certificaat lost
dat niet op. Wat wel helpt, is de site bij de filterbedrijven aanmelden als
**Gezondheid / Health and Medicine**. Dat is gratis en kost per bedrijf een paar
minuten: domein invullen, categorie kiezen, korte uitleg ("Kleinschalige
ouderenzorg in Maasbommel: wonen, logeren en dagbesteding met zorg").

Doe dit **zodra de nieuwe site live staat**, want de filters beoordelen wat er
op dat moment te zien is.

| Filterbedrijf | Waar aanmelden |
|---|---|
| Fortinet (FortiGuard) | https://www.fortiguard.com/faq/wfratingsubmit |
| Palo Alto Networks | https://urlfiltering.paloaltonetworks.com/ (zoeken, dan "Request a Change") |
| Cisco (Talos en Umbrella) | https://talosintelligence.com/reputation_center |
| Zscaler | https://sitereview.zscaler.com/ |
| Broadcom (Symantec) | https://sitereview.bluecoat.com/ |
| Forcepoint | https://csi.forcepoint.com/ |
| Check Point | https://urlcat.checkpoint.com/urlcat/ |
| Sophos | via de Sophos-supportpagina "Submit a sample", optie "Web Address (URL)" |
| Microsoft (SmartScreen) | https://www.microsoft.com/wdsi/filesubmission, kies "URL" |
| Google (controle, geen aanmelding) | https://transparencyreport.google.com/safe-browsing/search |

De adressen van Fortinet en Palo Alto zijn op 28 september gecontroleerd. De
andere zijn de bekende adressen van die bedrijven; kijk bij het aanmelden even
of de pagina nog zo heet.

**Nog sneller:**

- Vraag bij één zorginstelling welke melding hun medewerkers zien, liefst met
  een foto van het scherm. Daarop staat meestal de naam van het filter. Dan
  weten we precies waar we moeten zijn.
- Vraag de ICT-afdeling van die instelling om `kombijmaasbommel.nl` op hun
  lijst met toegestane sites te zetten.
- **Hosting:** de huidige site draait bij GoDaddy op een gedeelde server. Daar
  staan honderden sites op hetzelfde adres, en als er één verdacht is, gaat de
  rest mee in de blokkade. Advies: verhuizen naar een Nederlandse hostingpartij
  met een eigen IP-adres.

## 2. Zorgkaart Nederland

KomBij staat nog niet op Zorgkaart Nederland. Juist daar kijken families, en
de beste sites in de zorg laten hun cijfer zien.

1. Aanmelden als zorgaanbieder: https://www.zorgkaartnederland.nl/content/aanmelden-zorgaanbieder
2. Een gratis account is genoeg om te reageren op waarderingen en
   openingstijden in te vullen.
3. Vraag tevreden families en mantelzorgers om een waardering. Tien is een
   mooi begin.
4. Daarna zetten wij het cijfer en een paar citaten op de site.

## 3. Wat we van KomBij nodig hebben

- [ ] **Prijzen.** Op Zorgvilla Expert staat "woonkosten vanaf € 985 per
      maand, exclusief servicekosten en zorg". Klopt dat nog? Dat bedrag staat
      nu op de site. Wat kost logeren en dagbesteding particulier? Ter
      vergelijking: Martha Flora rekent voor logeren € 195 per dag met een
      WLZ-indicatie en € 245 zonder.
- [ ] **Aantallen.** Kloppen 6 woonplekken en 2 logeerkamers, dus 8 in totaal?
- [ ] **Logo.** Er is geen versie met "Maasbommel" als ondertitel. Nu staat
      onder het logo in de voet nog "Zoutkamer".
- [ ] **Het café.** Openingstijden en wat er te krijgen is.
- [ ] **Toestemming** van de bewoners en gasten die herkenbaar op de foto's
      van Lynn Brands staan.
- [ ] **Tikfout** in de pdf van de nachtdienstvacature: `werkbij@` moet
      `werkenbij@` zijn.
- [ ] **Keurmerken of lidmaatschappen**, bijvoorbeeld van een
      brancheorganisatie. Die zetten we als logo's in de huisstijlkleuren op de
      site. Op de oude site stonden er geen.

## 4. Zelf bijwerken in WordPress

- **Vrije plek melden:** ga naar *Gegevens*, dan *Plek vrij: wonen, logeren of
  dagbesteding*, en kies "Plek vrij" of "Wachtlijst". Op de site komt een label
  bij de kaart en een regel bovenaan de pagina. **Na 45 dagen zonder
  bijwerken verdwijnt het vanzelf**, zodat er nooit oude informatie blijft
  staan. Eén keer per maand opslaan is genoeg.
- **Melding rechtsonder**, bijvoorbeeld "nog plekken vrij in de zoutkamer" of
  "het café is open": ga naar *Gegevens* en vul de melding in.
- **Vacatures:** ga naar *Vacatures* en kies *Nieuwe vacature*. Het is een
  formulier. Na de sluitingsdatum verdwijnt een vacature vanzelf, en hij komt
  ook in Google Jobs.
- **Openingstijden van de dagbesteding:** ga naar *Gegevens*. Het bordje onder
  de menubalk leest die tijden automatisch.

## Nog invullen voor de livegang

Dit staat ook als "Nog invullen" bovenaan het KomBij-overzicht, tot het is
ingevuld.

- [ ] **KvK-nummer en btw-nummer:** ga naar *KomBij*, *Contact en bedrijf*.
      Ze staan onderaan de site en in de gegevens voor Google.
- [ ] **Link voor een Google-review:** zelfde scherm, bij *Sociale media en
      reviews*. De link vindt u in uw Google-bedrijfsprofiel, bij "Vraag om
      reviews". Dan kunnen tevreden families en bewoners makkelijk een review
      achterlaten.

## 5. Ideeën voor later

1. **Een checklist om te downloaden:** "Is het tijd voor meer zorg? Waar let u
   op?" Dat is een goede eerste stap voor twijfelende families.
2. **Een pagina voor casemanagers en huisartsen.** Zij verwijzen veel mensen
   door.
3. **Een vast koffiemoment zonder afspraak**, bijvoorbeeld elke woensdag van
   14:00 tot 16:00. Dat drempelloze moment zetten we op de site.
4. **Een contactpersoon met naam en foto** bij het contactformulier.
5. **Ervaringen van families** met een korte kop, zodra Zorgkaart loopt.
6. **Een korte film** van het gebouw en de verbouwing. De oude site had al
   foto's van de verbouwing.
