# Lokaal draaien en testen

## De snelste weg: WordPress Studio

Nieuwe site aanmaken, de themamap in `wp-content/themes/` zetten, thema
activeren. Klaar. Voor dagelijks werk is dit het handigst, want je kunt de site
meteen in een browser openen en delen.

## Zonder Studio: de ingebouwde server van PHP

Werkt overal waar PHP staat, ook op een server zonder database.

```bash
# WordPress ophalen en uitpakken in ~/wpsite
# Daarna: SQLite-integratie erbij, zodat je geen MySQL nodig hebt
# (wp-content/db.php uit de plugin sqlite-database-integration)

cd ~/wpsite
nohup php -S 127.0.0.1:8088 router.php > server.log 2>&1 &
```

Zet in `wp-config.php`:

```php
define( 'WP_HOME', 'http://127.0.0.1:8088' );
define( 'WP_SITEURL', 'http://127.0.0.1:8088' );
```

En maak `router.php` naast `index.php`:

```php
<?php
// Alleen voor de lokale testserver: bestaat het bestand, dan serveren, anders WordPress.
$pad = parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH );
$bestand = __DIR__ . $pad;
if ( $pad !== '/' && file_exists( $bestand ) && ! is_dir( $bestand ) ) {
	return false;
}
$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/index.php';
```

Het thema erin met een symlink, dan hoef je niets te kopiëren:

```bash
ln -s ~/project/thema/naam-van-de-klant ~/wpsite/wp-content/themes/naam-van-de-klant
```

## Het thema activeren zonder in te loggen

```bash
cat > ~/wpsite/zetthema.php <<'EOF'
<?php
define( 'WP_USE_THEMES', false );
require __DIR__ . '/wp-load.php';
switch_theme( 'naam-van-de-klant' );
do_action( 'after_switch_theme' );
echo wp_get_theme()->get( 'Name' ) . "\n";
EOF
php ~/wpsite/zetthema.php
```

Let op: de patronen en de blokken worden op `init` geregistreerd, en dat is in
deze ene aanroep al gebeurd voordat het thema wisselde. Draai het script dus twee
keer, of roep de installatie in een tweede aanroep aan:

```php
delete_option( 'cjt_installatie_gedaan' );
cjt_installatie();
```

## Controleren dat er geen PHP-fouten zijn

```bash
# Alle PHP-bestanden nalopen
find thema -name '*.php' -exec php -l {} \; | grep -v "No syntax errors"

# Elke pagina opvragen en kijken naar de statuscode
for p in "" "diensten/" "contact/" "bedankt/" "llms.txt" "bestaatniet/"; do
  echo -n "/$p -> "
  curl -s -o /tmp/uit.html -w "%{http_code}" "http://127.0.0.1:8088/$p"
  echo -n " meldingen: "
  grep -ci "Fatal error\|Warning:\|Deprecated:" /tmp/uit.html
done
```

Zet tijdens het bouwen `display_errors` aan, anders zie je een witte pagina in
plaats van de fout:

```bash
php -d error_reporting=E_ALL -d display_errors=1 -S 127.0.0.1:8088 router.php
```

## De telefoon nakijken met Playwright

Chromium staat al klaar op `/opt/pw-browsers/chromium`. Niet `playwright install`
draaien, dat haalt onnodig een tweede browser op.

```js
import { chromium } from 'playwright';

const maten = [320, 360, 390, 430, 768, 1024, 1280];
const browser = await chromium.launch({ executablePath: '/opt/pw-browsers/chromium' });

for (const breedte of maten) {
  const context = await browser.newContext({ viewport: { width: breedte, height: 900 } });
  const page = await context.newPage();
  await page.goto('http://127.0.0.1:8088/', { waitUntil: 'networkidle' });

  const meting = await page.evaluate(() => {
    const klein = [];
    document.querySelectorAll('a, button, input, select, textarea, summary').forEach(el => {
      const r = el.getBoundingClientRect();
      if (r.width && r.height && r.height < 44) klein.push(el.tagName + ' ' + Math.round(r.height) + 'px');
    });
    return {
      scrollt: document.documentElement.scrollWidth > window.innerWidth + 1,
      tapdoelen: klein.length
    };
  });

  console.log(breedte, meting);
  await context.close();
}
await browser.close();
```

Waar je op let:

- `scrollt` moet overal `false` zijn.
- Tapdoelen minstens 44 pixels hoog.
- Geen tekst onder 12,5 pixels.
- De maten **tussen** de bekende maten zijn waar het misgaat. Test ook 480, 560,
  640, 820, 900 en 1100.

## Toegankelijkheid

```bash
npx @axe-core/cli http://127.0.0.1:8088/ --exit
```

Kleurcontrast controleer je met een rekensom, niet met je ogen. Alles boven 4,5:1
is goed voor gewone tekst, boven 3:1 voor grote tekst.

## Snelheid

```bash
npx lighthouse http://127.0.0.1:8088/ --only-categories=performance,seo,accessibility \
  --chrome-flags="--headless --no-sandbox" --output=json --output-path=/tmp/lh.json
```

Lokaal meet je geen netwerk, dus de snelheidsscore is optimistisch. SEO en
toegankelijkheid zijn wel betrouwbaar; die horen op 100 te staan.
