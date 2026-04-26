# BuildMatch AI (PHP 8.2 + MySQL, be framework)

Marketplace platforma statybų/renovacijos projektams:
- klientas sukuria projektą,
- sistema parenka rangovus,
- siunčiami kvietimai tik approved + consent rangovams,
- rangovai pateikia bid'us,
- klientas lygina kainas (min/max/avg/median),
- admin valdo vartotojus, rangovus, projektus, CSV importą ir logus.

## Reikalavimai
- PHP 8.2
- MySQL/MariaDB
- `pdo_mysql`, `fileinfo`, `curl` (optional)

## Diegimas Hostinger Business
1. Hostinger panelėje sukurkite MySQL DB.
2. Įkelkite visą repo turinį į `public_html`.
3. Atidarykite `https://jusu-domenas.lt/install.php`.
4. Suveskite DB duomenis, pasirinktinai pažymėkite `Seed demo data`.
5. Prisijunkite su `admin@example.com / admin123`.
6. Nustatykite SMTP per Admin -> Settings.
7. Po diegimo **ištrinkite arba užblokuokite `install.php`**.

## CSV importas
Admin -> Import contractors. CSV antraštė:
`company_name,email,phone,city,region,categories,website,consent_to_contact`

- Deduplikacija: email/phone/company_name.
- Be `consent_to_contact=1` rangovas importuojamas `pending`.

## Demo paskyros (jei seed įjungtas)
- admin@example.com / admin123
- client@example.com / client123
- contractor@example.com / contractor123

## Srautų testas
1. Prisijungti kaip klientas ir sukurti projektą.
2. Prisijungti kaip admin, atidaryti projektą, spausti „Auto parinkti rangovus...“.
3. Rangovas gauna invite token nuorodą, pateikia pasiūlymą.
4. Klientas mato kainų palyginimą ir gali pasirinkti rangovą.

## Pastabos
- `config.php` nėra repo failas. Generuojamas `install.php` metu.
- OpenAI key nenurodžius naudojama fallback analizė.
