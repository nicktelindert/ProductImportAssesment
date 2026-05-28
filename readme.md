# Product import 

Deze applicatie is een technische demo voor het importeren van productdata vanuit een externe API naar een lokale database, met een presentatielaag gebouwd volgens het MVC-patroon.

## 🚀 Quick Start

1. **Containers opstarten**:
   ```bash
   docker compose up -d
   ```

2. **Dependencies installeren**:
   ```bash
   docker compose exec webserver composer install
   ```

3. **Producten importeren**:
   ```bash
   docker compose exec webserver php bin/import.php
   ```

4. **Bekijken**:
   Ga naar http://localhost:8080 in je browser.

## 🛠 Technische Specificaties

### Architectuur
De applicatie maakt gebruik van een handmatige **MVC (Model-View-Controller)** implementatie onder de namespace `ProductImporter`.
- **Router**: Een custom router handelt URL-patronen af (`/{controller}/{method}/{id}/{slug}`).
- **Controllers**: Verwerken requests en communiceren met Repositories.
- **Repositories**: Beheren de data-access laag met PDO en prepared statements (SQL-injection safe).
- **Services**: De `Importer` service handelt de communicatie met de DummyJSON API af middels Guzzle.
- **ErrorHandler**: Gecentraliseerde foutafhandeling die nette HTTP-statuscodes (404/500) en views teruggeeft.

### Gebruikte technieken
- PHP 8.5 (Apache)
- MariaDB 11.4
- Composer (PSR-4 Autoloading)
- GuzzleHttp (API Client)
- PHPUnit (Unit & Integration tests)
- Xdebug (Code Coverage)

## 📂 Mappenstructuur
```text
├── bin/            # CLI scripts (o.a. import.php)
├── docker/         # Docker configuratie en SQL init
├── public/         # Document root (index.php, .htaccess)
├── src/            # Core applicatie code
│   ├── Controllers/
│   ├── Models/
│   ├── Repositories/
│   └── Services/
├── tests/          # Unit & Integration tests
└── views/          # HTML templates
```

## 🧪 Testen & Kwaliteit

### Tests uitvoeren
De suite bevat zowel Unit tests (Mapping, Logic) als Integration tests (Database, Routing).
```bash
docker compose exec webserver ./vendor/bin/phpunit
```

### Code Coverage
Om een gedetailleerd HTML-rapport van de testdekking te genereren:
```bash
docker compose exec -e XDEBUG_MODE=coverage webserver ./vendor/bin/phpunit --coverage-html coverage-report
```

## 🌐 Routing
De applicatie gebruikt "Pretty URL's". Zorg ervoor dat de webserver alle requests die niet naar bestaande bestanden wijzen, doorstuurt naar `public/index.php`. 

Voorbeeld URL's:
- `/` : Productoverzicht (default route naar ProductController).
- `/product/show/1/essence-mascara` : Detailpagina met SEO-vriendelijke slug.

### .htaccess (Public map)
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^ index.php [QSA,L]
```