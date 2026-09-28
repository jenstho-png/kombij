#!/usr/bin/env python3
"""
Maakt kleinere kopieën van de stylesheet en de scripts van het thema.

Waarom: de bronbestanden staan vol uitleg, en dat hoort ook zo. Maar die uitleg
hoeft niet over de lijn naar iedere bezoeker. Dit maakt naast elk bronbestand
een .min-versie zonder commentaar en zonder overbodige witruimte. Het thema
gebruikt die kopie alleen als hij bestaat én nieuwer is dan de bron; vergeet je
dit te draaien na een wijziging, dan valt de site vanzelf terug op de bron.

Draaien vanuit de hoofdmap van het project:

    python3 gereedschap/verklein.py

Voor de scripts is terser nodig; die haalt hij zelf op met npx.
"""

import pathlib
import re
import subprocess
import sys

WORTEL = pathlib.Path(__file__).resolve().parent.parent
THEMA = WORTEL / 'thema' / 'kombij'


def css_verkleinen(bron: str) -> str:
    """Haalt commentaar en overbodige witruimte weg, en laat de rest met rust."""
    uit = []
    i = 0
    n = len(bron)
    while i < n:
        teken = bron[i]

        # Een tekst tussen aanhalingstekens blijft precies zoals hij is.
        if teken in '"\'':
            j = i + 1
            while j < n:
                if bron[j] == '\\':
                    j += 2
                    continue
                if bron[j] == teken:
                    break
                j += 1
            uit.append(bron[i:j + 1])
            i = j + 1
            continue

        # Commentaar gaat eruit.
        if teken == '/' and bron.startswith('/*', i):
            eind = bron.find('*/', i + 2)
            i = n if eind == -1 else eind + 2
            continue

        uit.append(teken)
        i += 1

    tekst = ''.join(uit)

    # Witruimte inklappen, maar niet binnen aanhalingstekens; die zijn hierboven
    # al veilig weggezet als losse stukken, dus dit is een grove veeg over de rest.
    tekst = re.sub(r'\s+', ' ', tekst)
    tekst = re.sub(r'\s*([{};,])\s*', r'\1', tekst)
    tekst = re.sub(r';\}', '}', tekst)

    return tekst.strip()


def main() -> int:
    css = THEMA / 'assets' / 'css' / 'thema.css'
    doel = css.with_suffix('.min.css')
    klein = css_verkleinen(css.read_text(encoding='utf-8'))
    doel.write_text(klein, encoding='utf-8')
    print(f'{css.name}: {len(css.read_bytes()) // 1024} kB -> {len(klein.encode()) // 1024} kB')

    for js in sorted((THEMA / 'assets' / 'js').glob('*.js')):
        if js.name.endswith('.min.js'):
            continue
        uit = js.with_suffix('.min.js')
        gelukt = subprocess.run(
            ['npx', '--yes', 'terser@5', str(js), '--compress', '--mangle', '-o', str(uit)],
            capture_output=True, text=True, timeout=300
        )
        if gelukt.returncode != 0:
            print(f'{js.name}: mislukt\n{gelukt.stderr}', file=sys.stderr)
            return 1
        print(f'{js.name}: {js.stat().st_size // 1024} kB -> {uit.stat().st_size // 1024} kB')

    return 0


if __name__ == '__main__':
    raise SystemExit(main())
