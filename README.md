# 🗓️ TASK VAULT – Moderný študentský kalendár a manažment úloh

Vitajte v mojom záverečnom študentskom projekte!
**TASK VAULT** je webová aplikácia, ktorá pomáha študentom efektívne plánovať úlohy, organizovať čas a mať prehľad o všetkých dôležitých termínoch na jednom mieste. Koniec s chaotickými poznámkami a zabudnutými termínmi!

---

## 🚀 Čo aplikácia dokáže?

- **Prehľadný kalendár** – správa a zobrazenie udalostí s rôznymi farebnými označeniami
- **Pridávanie, úprava a mazanie udalostí** – komplexná správa kalendára
- **Projektový manažment** – vytváranie a sledovanie projektov s ich opisom a termínmi
- **Kanban systém pre úlohy** – vizuálne sledovanie postupu v štýle To Do, In Progress, Done
- **Denný prehľad** – rýchly pohľad na všetky dnešné úlohy, projekty a udalosti
- **Bezpečné prihlásenie a registrácia** – s ochranou hesiel a správou používateľských relácií
- **Responzívny dizajn** – vďaka Bootstrapu použiteľné na počítači aj mobile

---

## 🛠️ Použité technológie

![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white)
![MVC](https://img.shields.io/badge/MVC-Architecture-lightgrey)
![Composer](https://img.shields.io/badge/Composer-885630?style=flat&logo=composer&logoColor=white)
![PDO](https://img.shields.io/badge/PDO-Database-blue)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=flat&logo=bootstrap&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white)
![FontAwesome](https://img.shields.io/badge/FontAwesome-528DD7?style=flat&logo=fontawesome&logoColor=white)

---

## 🖥️ Ukážka aplikácie

> ![Ukážka kalendára](public/img/ukazka.jpg)

---

## 📝 Inštalácia a spustenie

1. **Klonujte repozitár:**

   ```bash
   git clone https://github.com/Sokoliz/TASK-VAULT-uloharen.git
   ```

2. **Nainštalujte závislosti pomocou Composeru:**

   ```bash
   composer install
   ```

3. **Importujte databázu:**  
   Nájdite súbor `app/Core/kanban.sql` a importujte ho do vašej MySQL databázy.

4. **Nakonfigurujte pripojenie k databáze:**  
   Otvorte `config/config.php` a upravte prihlasovacie údaje pre vašu databázu:

   ```php
   return [
       'db' => [
           'host' => 'localhost',
           'dbname' => 'kanban',
           'user' => 'root', // Zmeňte podľa vašich údajov
           'pass' => '' // Zmeňte podľa vašich údajov
       ]
   ];
   ```

5. **Umiestnenie aplikácie:**
   **DÔLEŽITÉ:** Aplikácia musí byť umiestnená priamo v hlavnom adresári "htdocs" vášho webového servera (XAMPP, WAMP), nie v podpriečinku. Toto je potrebné pre správne fungovanie URL adries a smerovania.

6. **Spustite aplikáciu:**

   a) **Pomocou lokálneho servera** (napr. XAMPP, WAMP).

   b) **Pomocou vstavaného PHP servera cez CMD:**

   ```bash
   cd cesta/k/projektu
   php -S localhost:1234
   ```

   Potom otvorte prehliadač a prejdite na adresu `http://localhost:1234`

7. **Otvorte aplikáciu v prehliadači** a prihláste sa alebo si vytvorte nový účet.

---

## 🐞 Riešenie problémov

- **Problém s prihlásením:** Skontrolujte, či ste správne importovali databázu a nastavili prístupové údaje v config/config.php.
- **Chyba pri vytváraní úlohy:** Uistite sa, že všetky zadané hodnoty spĺňajú požadované formáty.
- **Nezobrazia sa projekty:** Skontrolujte tabuľku 'PROJECTS' v databáze, či obsahuje správne dáta.
- **Problémy s URL smerovaním:** Skontrolujte, či súbory .htaccess sú správne nakonfigurované.

---

## 📁 Štruktúra projektu (MVC architektúra)

- `app/` – Hlavný adresár aplikačného kódu
  - `Controllers/` – Kontroléry pre spracovanie požiadaviek
  - `Models/` – Modely pre prácu s databázou
  - `Views/` – Pohľady a šablóny
  - `Core/` – Základné triedy a funkcie aplikácie
- `config/` – Konfiguračné súbory
- `public/` – Verejne dostupné súbory
  - `css/` – Kaskádové štýly
  - `js/` – JavaScript súbory
  - `img/` – Obrázky a médiá
- `vendor/` – Knižnice spravované Composerom

---

## 👨‍💻 Autor

Projekt vytvoril: **Marek Sokol**  
Kontakt: marek.sokol@student.ukf.sk

---

## 🙏 Poďakovanie

Ďakujem všetkým, ktorí ma podporovali počas vývoja tohto projektu, a komunite open-source za skvelé knižnice a nástroje.

---

> **💡 Vtipná poznámka**: Tento projektový manažment systém bol vytvorený počas nespočetných bezsenných nocí, poháňaných kávou a motivovaný blížiacimi sa termínmi. Ak náhodou nájdete chybu, nebude to bug - iba neočakávaná funkcionalita vzniknutá o 3 ráno!
>
> _"TASK VAULT - pretože termíny prichádzajú rýchlejšie než výhovorky a prokrastinácia už nie je vaša najsilnejšia stránka. Jediný systém, kde deadliny nie sú len odporúčaním, ale skutočne sa dodržiavajú!"_
