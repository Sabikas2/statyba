# Statyba Pro Platforma

Pilnai perrašyta (nuo nulio) platformos versija su:
- klientų, rangovų ir administratorių rolėmis,
- automatinės rangovų paieškos filtrais,
- užklausų siuntimu rangovams,
- automatizuotu el. laiškų queue mechanizmu,
- reklamos kampanijų kūrimu ir admin patvirtinimu (monetizacija).
- CSRF apsauga visoms POST formoms.
- bazinio URL adaptacija (veikia ir kai app paleista per /public/).
- reklamos paspaudimų / parodymų sekimas su baziniais KPI (CTR, estimated spend).

## Greitas paleidimas (lokaliai)

```bash
php -S localhost:8080 -t public
```

Atidarykite `http://localhost:8080`.

Numatytas admin:
- Email: `admin@statyba.lt`
- Slaptažodis: `Admin123!`

## Architektūra

- `public/index.php` – entrypoint, migracijos + email queue processing.
- `src/Controllers` – klientų/rangovų/admin veiksmų valdikliai.
- `src/Repositories` – DB prieiga.
- `src/Services/EmailService.php` – el. laiškų queue.
- `src/Views` – UI šablonai.
- `storage/app.sqlite` – SQLite DB (sukuriama automatiškai).

## Hostinger (Business Web Hosting) diegimas

### Variantas A: PHP + SQLite
1. Įkelkite projekto failus į `public_html`.
2. Nukreipkite dokumento root į `public/` (jei Hostinger panelėje galima) arba perkelkite `public/index.php` turinį į `public_html/index.php` ir pataisykite kelius.
3. Užtikrinkite `storage/` rašymo teises.

### Variantas B: PHP + MySQL (rekomenduojama production)
Sukurkite `.env`:

```env
DB_DRIVER=mysql
DB_HOST=localhost
DB_PORT=3306
DB_NAME=your_db
DB_USER=your_user
DB_PASS=your_pass
```

Pirmas atidarymas paleis migracijas automatiškai.

## Pastabos
- `mail()` funkcija Hostinger aplinkoje dažniausiai veikia. Jei ne – laiškai saugomi `storage/emails.log`.
- Rangovo paskyra prisijungti galės tik po admin patvirtinimo.
