# Productivity Hub

Webová aplikácia na správu produktivity a projektov vytvorená v PHP.

## Funkcie

- **Správa Projektov**: Organizácia a sledovanie projektov
- **Kalendár**: Plánovanie a správa udalostí
- **Denné Úlohy**: Sledovanie denných úloh a aktivít
- **Autentifikácia**: Bezpečné prihlásenie a registrácia užívateľov

## Technológie

- PHP
- MySQL
- HTML/CSS
- JavaScript
- jQuery
- FullCalendar.js

## Inštalácia

1. Naklonujte repozitár:
```bash
git clone [URL repozitára]
```

2. Importujte databázu:
- Nájdite súbor `db/kanban.sql`
- Importujte ho do vašej MySQL databázy

3. Nakonfigurujte pripojenie k databáze:
- Otvorte `events/actions/connection.php`
- Upravte prihlasovacie údaje k databáze

4. Spustite lokálny server (napr. XAMPP, WAMP)

5. Otvorte aplikáciu v prehliadači

## Štruktúra Projektu

- `css/` - Štýly a vzhľad aplikácie
- `js/` - JavaScript súbory
- `db/` - Databázové skripty
- `events/` - Správa udalostí a akcií
- `views/` - PHP šablóny
- `img/` - Obrázky a média

## Vývojové Vetvy

- `main` - Produkčná vetva
- `development` - Hlavná vývojová vetva
- Feature vetvy:
  - `feature/auth` - Autentifikácia
  - `feature/projects` - Správa projektov
  - `feature/calendar` - Kalendár
  - `feature/ui-components` - UI komponenty
  - `feature/database` - Databázové funkcie
  - `feature/daily-tasks` - Denné úlohy 