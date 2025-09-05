# Bestandsrechten en Groepen beheren voor je Webproject

Wanneer je een webproject beheert op een Linux-server, zoals een Laravel-applicatie in `/var/www`, is het belangrijk om de juiste bestandsrechten en gebruikersgroepen in te stellen. Dit zorgt voor veiligheid, samenwerking en correcte werking van je applicatie.

---

## 1. Waarom groepen gebruiken?

- Groepen maken het mogelijk om meerdere gebruikers dezelfde rechten te geven op bepaalde mappen en bestanden.
- Bijvoorbeeld: de webserver (`www-data`) en jouw eigen gebruiker moeten beide schrijfrechten hebben in de projectmap.
- Door een groep toe te voegen en beide gebruikers lid te maken, voorkom je problemen met rechten.

---

## 2. Groep aanmaken

Maak eerst een nieuwe groep aan, bijvoorbeeld `webgroup`:

    sudo groupadd webgroup

---

## 3. Gebruikers toevoegen aan de groep

Voeg de webservergebruiker (`www-data`) en jouw eigen gebruiker toe aan deze groep:

    sudo usermod -aG webgroup www-data
    sudo usermod -aG webgroup jouwgebruikersnaam

> **Tip:** Vervang `jouwgebruikersnaam` door je eigen gebruikersnaam op de server.

---

## 4. Rechten toewijzen aan mappen en bestanden

Stel nu de juiste rechten in op je projectmap (bijvoorbeeld `/var/www/yourproject`):

- Geef lees-, schrijf- en uitvoerrechten aan de eigenaar en groep (775) voor mappen.  
- Geef lees- en schrijf-rechten (664) aan bestanden voor eigenaar en groep.

Voer de volgende commando's uit:

    sudo chmod -R 775 /var/www/yourproject
    sudo find /var/www/yourproject -type f -exec chmod 664 {} \;
    sudo find /var/www/yourproject -type d -exec chmod 775 {} \;

---

## 5. Groep instellen als standaard voor nieuwe bestanden (setgid)

Door de `setgid` bit op mappen te zetten, erven nieuwe bestanden en mappen automatisch de groep van de bovenliggende map:

    sudo chmod g+s /var/www/yourproject

---

## 6. Eigenaar en groep van de bestanden aanpassen

Zorg dat de eigenaar de juiste gebruiker is (bijvoorbeeld `yourusername`) en de groep `webgroup`:

    sudo chown -R yourusername:webgroup /var/www/yourproject

---

## 7. Groep lees- en schrijfrechten geven

Geef de groep lees- en schrijfrechten op alle bestanden en mappen:

    sudo chmod -R g+rw /var/www/yourproject

---

## 8. Controleer of het is gelukt

Log in als de gebruiker (`yourusername`) en controleer de rechten:

    ls -l /var/www/yourproject/composer.json

Je zou iets moeten zien als:

    -rw-rw-r-- 1 yourusername webgroup 12345 Sep 5 12:00 composer.json

Dit betekent dat eigenaar en groep lees- en schrijfrechten hebben.

---

## 9. Herhaal indien nodig

Bij wijzigingen of nieuwe bestanden is het soms nodig om deze stappen te herhalen om rechten consistent te houden.

---

## Samenvatting van commando's

    sudo groupadd webgroup
    sudo usermod -aG webgroup www-data
    sudo usermod -aG webgroup jouwgebruikersnaam

    sudo chmod -R 775 /var/www/yourproject
    sudo find /var/www/yourproject -type f -exec chmod 664 {} \;
    sudo find /var/www/yourproject -type d -exec chmod 775 {} \;
    sudo chmod g+s /var/www/yourproject
    sudo chown -R yourusername:webgroup /var/www/yourproject
    sudo chmod -R g+rw /var/www/yourproject

---

Met deze instellingen zorg je dat de webserver en jijzelf zonder problemen samen kunnen werken aan de projectbestanden, zonder veiligheidsproblemen of conflicten met bestandsrechten.

---

**Succes met het beheren van je serverbestanden!**
