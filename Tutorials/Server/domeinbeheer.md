# Domein Management op je Strato Server

In deze tutorial leggen we uit hoe je een nieuwe website toevoegt aan je Strato-server, hoe je deze configureert in Apache, en hoe je HTTPS instelt met Certbot. Deze handleiding gaat ervan uit dat je een werkende server hebt met Apache en Certbot geïnstalleerd.

---

## Inhoud

1. [Nieuwe website toevoegen in Apache](#nieuwe-website-toevoegen-in-apache)  
2. [Website activeren en beheren](#website-activeren-en-beheren)  
3. [HTTPS instellen met Certbot](#https-instellen-met-certbot)  
4. [Website permissies beheren](#website-permissies-beheren)  
5. [Belangrijke statuscommando's](#belangrijke-statuscommando-s)

---

## Nieuwe website toevoegen in Apache

1. Maak een nieuwe configuratie aan voor je website:

   ```bash
   sudo nano /etc/apache2/sites-available/websitedomain.conf
   ```

2. Voeg de volgende configuratie toe (vervang `websitedomain` door je eigen domeinnaam):

       ```<VirtualHost *:80>
           ServerAdmin webmaster@websitedomain
           ServerName websitedomain
           ServerAlias www.websitedomain

           DocumentRoot /var/www/websitedomain/public

           <Directory /var/www/websitedomain/public>
               Options Indexes FollowSymLinks
               AllowOverride All
               Require all granted
           </Directory>

           ErrorLog ${APACHE_LOG_DIR}/websitedomain_error.log
           CustomLog ${APACHE_LOG_DIR}/websitedomain_access.log combined
       </VirtualHost>
   ```

4. Sla het bestand op en sluit de editor.

---

## Website activeren en beheren

1. Activeer de nieuwe site in Apache:

   ```
   sudo a2ensite websitedomain.conf
   ```

2. Herlaad Apache om de wijzigingen door te voeren:

   ```
   sudo systemctl reload apache2
   ```

3. Om een website tijdelijk uit te schakelen:

   ```
   sudo a2dissite websitedomain.conf  
   sudo systemctl reload apache2
   ```

4. Bekijk de status van Apache:

   ```
   sudo systemctl status apache2
   ```

---

## HTTPS instellen met Certbot

Als Certbot en het Certbot Apache plugin al geïnstalleerd zijn, kun je HTTPS eenvoudig activeren:

1. Voer Certbot uit voor je domein:

   ```
   sudo certbot --apache -d websitedomain -d www.websitedomain
   ```

2. Volg de instructies op het scherm, bijvoorbeeld:  
   - Vul je e-mailadres in voor belangrijke meldingen  
   - Accepteer de gebruiksvoorwaarden  
   - Kies of je HTTP-verkeer automatisch wilt omleiden naar HTTPS

### Certbot installeren (indien nog niet geïnstalleerd)

Als Certbot nog niet is geïnstalleerd, kun je het als volgt toevoegen:

   ```
   sudo apt update  
   sudo apt install certbot python3-certbot-apache
   ```

---

## Website permissies beheren

Zorg dat Apache toegang heeft tot de juiste mappen, vooral bij Laravel-projecten:

   ```
   sudo chown -R www-data:www-data /var/www/websitedomain/storage  
   sudo chown -R www-data:www-data /var/www/websitedomain/bootstrap/cache  
   sudo chmod -R 775 /var/www/websitedomain/storage  
   sudo chmod -R 775 /var/www/websitedomain/bootstrap/cache
   ```

---

## Belangrijke statuscommando's

- Apache status controleren:

   ```
   sudo systemctl status apache2
   ```

- Certbot certificaten bekijken:

   ```
   sudo certbot certificates
   ```

- Firewall status controleren (optioneel):

   ```
   sudo ufw status
   ```

---

## Tips

- Zorg dat je DNS records correct naar je server IP verwijzen.  
- Na het toevoegen van een nieuwe site, test je configuratie altijd met:

   ```
   sudo apache2ctl configtest
   ```

- Herstart Apache bij twijfel:

   ```
   sudo systemctl restart apache2
   ```

---

Je website zou nu volledig online en beveiligd via HTTPS moeten draaien. Voor verdere beheer kun je altijd de Apache en Certbot documentatie raadplegen.

---

## Handige commando's

Hieronder een lijst met veelgebruikte commando's om je websites en Apache-configuraties snel te beheren:

- **Apache configuratie openen voor een specifieke site:**  
  ```
  sudo nano /etc/apache2/sites-available/websitedomain.conf
  ```

- **HTTPS-configuratie voor een site bekijken:**  
  ```
  sudo nano /etc/apache2/sites-available/websitedomain-le-ssl.conf
  ```

- **Alle beschikbare websites (sites-available) bekijken:**  
  ```
  ls -l /etc/apache2/sites-available/
  ```

- **Alle geactiveerde websites (sites-enabled) bekijken:**  
  ```
  ls -l /etc/apache2/sites-enabled/
  ```

- **Apache configuratie testen op fouten:**  
  ```
  sudo apache2ctl configtest
  ```

- **Apache herstarten (verplicht na config wijzigingen):**  
  ```
  sudo systemctl restart apache2
  ```

- **Apache herladen (voor minder ingrijpende wijzigingen):**  
  ```
  sudo systemctl reload apache2
  ```

- **Status van Apache controleren:**  
  ```
  sudo systemctl status apache2
  ```

- **Certbot certificaten tonen:**  
  ```
  sudo certbot certificates
  ```

- **Firewall status bekijken:**  
  ```
  sudo ufw status
  ```

- **Firewall poorten toevoegen voor HTTP en HTTPS (indien nodig):**  
  ```
  sudo ufw allow 80/tcp  
  sudo ufw allow 443/tcp
  ```

- **Logs bekijken van een specifieke website:**  
  ```
  tail -f /var/log/apache2/websitedomain_access.log  
  tail -f /var/log/apache2/websitedomain_error.log
  ```

---

Met deze commando's beheer je snel en effectief je websites en Apache-configuraties.


**Succes met je websitebeheer!**
