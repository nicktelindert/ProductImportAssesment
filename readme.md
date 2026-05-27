# Product import 

## De opdracht
De klant wil graag een overzicht van producten die
beschikbaar zijn via een externe API. Om niet afhankelijk te
zijn van de beschikbaarheid van de API moeten deze
producten periodiek geïmporteerd kunnen worden in een
eigen database waarbij de data vervolgens getoond moet
worden in een tabel.
De eis hierbij is dat dit een “Dockerized Application” is,
zonder gebruik te maken van externe frameworks. Het
gebruik van externe libraries is toegestaan.

Uiteraard! Hier zijn de MoSCoW-prioriteiten (Acceptatiecriteria) uit het document overzichtelijk op een rij:

### Must have

* De applicatie is gebouwd met als startpunt het zip-bestand dat is meegeleverd met deze opdracht.


* Het `composer.json` bestand bevat de benodigde dependencies en de autoloading-configuratie van de eigen code.


* De applicatie moet op te starten zijn met het commando `docker compose up`.


* De Apache document root moet verwijzen naar `/var/www/html/public`.


* De database bevat een tabel waarin productinformatie opgeslagen kan worden.


* Er is een manier beschikbaar, met PHP, om de producten te importeren vanuit de API `https://dummyjson.com/products`.


* Minimaal 100 producten vanuit de API moeten worden geïmporteerd.


* Via de browser moet een productoverzicht beschikbaar zijn waarvan de productinformatie uit de database komt (dus niet rechtstreeks live van de API).


* Het productoverzicht moet de kolommen/gegevens voor **titel**, **prijs**, **merk** en **categorie** bevatten.



### Should have

* De standaardprijs moet getoond worden met daarbij de kortingsprijs (berekening op basis van het kortingspercentage uit de API).


* De prijzen moeten netjes geformatteerd weergegeven worden als een bedrag in euro's.


* De thumbnail van het product wordt als afbeelding getoond in het overzicht.



### Could have

* In het overzicht is het mogelijk om te filteren op categorie en/of merk.


* In het overzicht is het mogelijk om te sorteren op alle kolommen.


* Via het overzicht is het mogelijk om door te klikken naar een detailpagina van het product.


* Op deze detailpagina wordt alle mogelijke informatie over het product weergegeven.

### Testen draaien

Je kunt de unit tests uitvoeren binnen de draaiende Docker container met het volgende commando:

```bash
docker compose exec webserver ./vendor/bin/phpunit
```
