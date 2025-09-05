# Strato Server vanaf 0 opbouwen en beveiligen

### 1. Server opnieuw installeren

1. Ga naar **Pakket** → **Overzicht** in je Strato controlepaneel.  
   - Scroll naar beneden en klik op **Opnieuw installeren**.  
   - Er verschijnt een modal venster met de titel **VM-herinstallatie**.

2. Besturingssysteem kiezen  
   - Selecteer **Ubuntu 22.04** (gebruik **niet** Ubuntu 24).  
   - Stel een sterk wachtwoord in voor de root-gebruiker.  
   - Maak een SSH-sleutel aan (uitleg hieronder).

3. SSH-sleutel aanmaken met PuTTYgen  
   1. Download en open **PuTTYgen.exe** als je dit nog niet hebt.  
   2. Klik op **Generate** en beweeg je muis willekeurig binnen het venster om de sleutel te genereren.  
   3. Zodra de sleutel is aangemaakt, voer je een sterke **passphrase** in voor extra beveiliging van je private key.  
   4. Sla de private key op een veilige plek op.  
   5. Kopieer de publieke sleutel uit het veld bovenin (onder **Key**, niet de menubalk).  
   6. Plak deze publieke sleutel in het Strato-installatiescherm bij de SSH-sleutel.  
   7. Start de installatie.

### 2. Firewall instellen

1. Zodra de installatie is voltooid, log je in op je server en activeer je direct de firewall:  
   - Open de firewallconfiguratie.  
   - Voeg de standaardregels toe voor **HTTP** (poort 80) en **HTTPS** (poort 443).  
   - Voeg een regel toe voor SSH-toegang:  
     - Protocol: TCP  
     - IPv4: leeg laten  
     - Poort van: `22`  
     - Poort tot: `22`  

### 3. SSH key converteren voor gebruik

1. Open **PuTTYgen.exe** opnieuw.  
2. Ga naar **Conversions** → **Import key** en selecteer je eerder opgeslagen `.ppk` bestand.  
3. Exporteer de sleutel als **OpenSSH key** via **Conversions** → **Export OpenSSH key**.  
4. Voer de passphrase in en sla het bestand op met een herkenbare naam, bijvoorbeeld `yourname_openssh`.

### 4. Inloggen op je server via SSH

Gebruik nu de OpenSSH-key om in te loggen:

```
ssh -i "[pad/naar/jouw/ssh-sleutel]" root@[jouw-server-ip]
```

---

### 5. Standaard beveiliging en gebruikersbeheer

1. We willen niet via **root** inloggen, dus maken we een nieuwe gebruiker aan. Voortaan log je in met deze gebruiker.  
   - Maak een nieuwe gebruiker aan:  
     ```
     adduser jouwgebruikersnaam
     ```  
   - Voeg deze gebruiker toe aan de sudo-groep:  
     ```
     usermod -aG sudo jouwgebruikersnaam
     ```  

2. (Optioneel) Root login via SSH uitschakelen:  
   - Open het SSH configuratiebestand:  
     ```
     sudo nano /etc/ssh/sshd_config
     ```  
   - Zoek de regel `PermitRootLogin` en haal het commentaar (#) weg, zodat het zo staat:  
     ```
     PermitRootLogin prohibit-password
     ```  
   - Herstart de SSH service:  
     ```
     sudo systemctl restart ssh
     ```  

3. Firewall instellen en activeren:  
   - Update pakketlijst en installeer UFW:  
     ```
     sudo apt update  
     sudo apt install ufw -y
     ```  
   - Stel standaard regels in:  
     ```
     sudo ufw default deny incoming  
     sudo ufw default allow outgoing  
     sudo ufw allow OpenSSH
     ```  
   - Activeer de firewall:  
     ```
     sudo ufw enable
     ```  
   - Controleer de status:  
     ```
     sudo ufw status verbose
     ```  

4. Bescherming tegen brute force aanvallen:  
   - Installeer fail2ban:  
     ```
     sudo apt install fail2ban -y
     ```  

5. Automatische updates inschakelen:  
   - Installeer unattended-upgrades:  
     ```
     sudo apt install unattended-upgrades -y
     ```  
   - Configureer unattended-upgrades:  
     ```
     sudo dpkg-reconfigure --priority=low unattended-upgrades
     ```  

6. Firewallregels blijvend maken:  
   - Installeer iptables-persistent:  
     ```
     sudo apt install iptables-persistent -y
     ```  

7. Beveiliging tegen malware en rootkits:  
   - Installeer ClamAV (virus scanner):  
     ```
     sudo apt install clamav  
     sudo freshclam                 # Update virusdefinities  
     sudo clamscan -r -i /path     # Recursief scannen van een pad
     ```  
   - Installeer rkhunter (rootkit hunter):  
     ```
     sudo apt install rkhunter  
     sudo rkhunter --update  
     sudo rkhunter -c              # Systeemcontrole
     ```  
   - Bij het eerste gebruik van rkhunter krijg je een scherm te zien met “no configuration or internet site”, kies hier **internet site** en voer je domeinnaam in.

### 6. Applicaties werkend maken

1. Update je pakketlijst en upgrade alle pakketten:  
   ```
   sudo apt update && sudo apt upgrade -y
   ```  

2. Installeer PHP (eventueel eerst nieuwe repository toevoegen):  
   ```
   sudo add-apt-repository ppa:ondrej/php  
   sudo apt update  
   sudo apt install apache2 libapache2-mod-php8.3 php8.3 php8.3-mysql php8.3-mbstring php8.3-curl php8.3-xml php8.3-bcmath php8.3-zip php8.3-cli unzip curl git -y
   ```  

3. Extra PHP modules installeren:  
   ```
   sudo apt install php-{xml,mbstring,curl,zip,gd,intl,bcmath,mysql,tokenizer,imagick,intl}
   ```  

4. Controleer PHP versie en Apache status:  
   ```
   php -v  
   sudo systemctl restart apache2  
   sudo systemctl status apache2
   ```  

5. Zorg dat MySQL draait:  
   ```
   sudo systemctl status mysql
   ```  
   - Als MySQL niet draait, start het dan met:  
     ```
     sudo systemctl start mysql
     ```  
   - Als het commando niet gevonden wordt, installeer MySQL:  
     ```
     sudo apt update  
     sudo apt install mysql-server  
     sudo systemctl status mysql
     ```  

6. PhpMyAdmin installeren en configureren:  
   ```
   sudo apt install phpmyadmin
   ```  
   - Bij het scherm “Yes or No” kies je **No**.  
   - Controleer of phpMyAdmin actief is:  
     ```
     sudo nano /etc/apache2/conf-available/phpmyadmin.conf
     ```  
   - Als het bestand leeg is, voeg dan het volgende toe:

        ```
        Alias /phpmyadmin /usr/share/phpmyadmin

        <Directory /usr/share/phpmyadmin>
            Options FollowSymLinks
            DirectoryIndex index.php

            <IfModule mod_php7.c>
                AddType application/x-httpd-php .php

                php_flag magic_quotes_gpc Off
                php_flag track_vars On
                php_flag register_globals Off
                php_admin_flag allow_url_fopen Off

                php_value include_path .
            </IfModule>

        </Directory>
        ```

   - Activeer de configuratie en herlaad Apache:  
     ```
     sudo a2enconf phpmyadmin  
     sudo systemctl reload apache2
     ```  

7. .bat script maken voor lokale toegang tot database via SSH tunnel:  

    ```
    @echo off
    REM Pas deze variabelen aan naar jouw situatie
    set PRIVATE_KEY=""
    set REMOTE_USER=root
    set REMOTE_HOST=
    set LOCAL_PORT=8080
    set REMOTE_PORT=80

    echo Starting SSH tunnel...
    ssh -i %PRIVATE_KEY% -L %LOCAL_PORT%:127.0.0.1:%REMOTE_PORT% %REMOTE_USER%@%REMOTE_HOST%

    pause
    ```

   - Open vervolgens in je browser: `http://127.0.0.1:8080/`

8. Als inloggen in MySQL niet lukt, kun je het wachtwoord resetten:  
   ```
   sudo systemctl stop mysql  
   sudo mysqld_safe --skip-grant-tables &  
   mysql -u root
   ```  

   - Als je een socket error krijgt:  
     ```
     sudo mkdir -p /var/run/mysqld  
     sudo chown mysql:mysql /var/run/mysqld  
     sudo mysqld_safe --skip-grant-tables &  
     mysql -u root
     ```  

   - Binnen MySQL console voer je uit:  
     ```
     USE mysql;  
     ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'nieuw_wachtwoord';  
     FLUSH PRIVILEGES;
     ```  

   - Daarna:  
     ```
     sudo killall mysqld  
     sudo systemctl start mysql
     ```  

   - Test het met:  
     ```
     mysql -u root -p
     ```  

9. Project in `/var/www` zetten:  
   - Open PuTTYgen en converteer je `.ppk` bestand om de publieke key te kopiëren.  
   - Voeg deze toe aan GitHub onder `https://github.com/settings/keys`  
   - Log in op je server en test:  
     ```
     ssh -T git@github.com
     ```  
     - Bij rejection, login als je gebruiker en voer uit:  
       ```
       ssh-keygen -t rsa -b 4096 -C "email@example.com"  
       cat ~/.ssh/id_rsa.pub  
       ssh -T git@github.com
       ```  

10. Project clonen:  
    - Maak de map aan en clone de repository via de gebruiker:  
      ```
      mkdir -p /var/www/websitedomain  
      cd /var/www/websitedomain  
      sudo git clone git@github.com:X-WMS/yourname.git /var/www/websitedomain
      ```  

    - Gebruik altijd de gebruiker om `git pull` te doen.

11. Groep toevoegen voor bestandsrechten:  
    ```
    sudo groupadd [groupname]  
    sudo usermod -aG yournamegroup www-data  
    sudo usermod -aG yournamegroup [username]
    ```
    
12. Bestandsrechten instellen (herhaal indien nodig):  
    ```
    sudo chmod -R 775 /var/www/websitedomain  
    sudo find /var/www/websitedomain -type f -exec chmod 664 {} \;  
    sudo find /var/www/websitedomain -type d -exec chmod 775 {} \;  
    sudo chmod g+s /var/www/websitedomain  
    sudo chown -R yourname:yournamegroup /var/www/websitedomain  
    sudo chmod -R g+rw /var/www/websitedomain  
    sudo chmod -R g+s /var/www/websitedomain
    ```  

13. Controleer of het gelukt is via de gebruiker:  
    ```
    ls -l composer.json
    ```
### 7. Composer en overige tools installeren en website online zetten

1. Composer installeren:  
   ```
   sudo apt install composer
   ```  

2. Schakel over naar de gebruiker:  
   ```
   su - gebruikersnaam
   ```  

3. Voer in de projectmap uit:  
   ```
   composer install
   ```  

4. Node.js installeren (voor frontend builds):  
   ```
   curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -  
   sudo apt-get install -y nodejs
   ```  

5. Frontend dependencies installeren en build draaien:  
   ```
   npm install  
   npm run build
   ```  

6. Laravel setup uitvoeren:  
   ```
   php artisan key:generate  
   php artisan migrate --seed  
   php artisan storage:link
   ```  

7. Website configureren in Apache:  
   ```
   sudo nano /etc/apache2/sites-available/websitedomain.conf  

   Voeg de volgende configuratie toe (pas `websitedomain` aan naar jouw domein):

       <VirtualHost *:80>
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

8. Site activeren en Apache herladen:  
   ```
   sudo a2ensite websitedomain.conf  
   sudo systemctl reload apache2
   ```  

9. HTTPS instellen met Certbot:  
   - Installeer Certbot als je dat nog niet hebt:  
     ```
     sudo apt install certbot python3-certbot-apache
     ```  

   - Controleer of firewall HTTP en HTTPS toestaat:  
     ```
     sudo ufw status
     ```  
     - Als niet toegestaan, voeg toe:  
       ```
       sudo ufw allow 80/tcp  
       sudo ufw allow 443/tcp  
       sudo ufw reload
       ```  

   - Certbot runnen:  
     ```
     sudo certbot --apache -d domainname -d www.domainname
     ```  
     - Vul je e-mailadres in (bijv. info@xwms.nl)  
     - Kies **Yes** voor voorwaarden accepteren  
     - Kies **No** voor aanmelden nieuwsbrief  

10. Permissions goed zetten voor Laravel cache en storage:  
    ```
    sudo chown -R www-data:www-data /var/www/websitedomain/storage  
    sudo chown -R www-data:www-data /var/www/websitedomain/bootstrap/cache  
    sudo chmod -R 775 /var/www/websitedomain/storage  
    sudo chmod -R 775 /var/www/websitedomain/bootstrap/cache
    ```  

11. Apache herstarten:  
    ```
    sudo systemctl restart apache2
    ```  

Nu is je website online en bereikbaar via HTTPS. Controleer in je browser of alles correct werkt.
