# Lokaal Toegang Krijgen tot Je Server Database via SSH Tunnel

Met deze handleiding leer je hoe je via een SSH-tunnel lokaal toegang krijgt tot de database van je server. Hiermee kun je je database lokaal openen via een browser of een client, zonder dat je de database openbaar hoeft te maken.

---

## Inhoud

- Wat is een SSH-tunnel?
- Benodigdheden
- Stap 1: Maak een script om een tunnel op te zetten
- Stap 2: Open de tunnel via je browser
- Stap 3: Reset het root-wachtwoord van MySQL (optioneel)
- Veelgestelde vragen
- Contact

---

## Wat is een SSH-tunnel?

Een SSH-tunnel zorgt voor een veilige verbinding tussen jouw computer en je server. Je verbindt een lokale poort op je computer met een poort op de server, zodat je lokale toegang krijgt tot bijvoorbeeld een database of webapplicatie die op de server draait.

---

## Benodigdheden

- Een server met SSH-toegang
- De SSH private key (of wachtwoord)
- Toegang tot een MySQL-database op de server
- Een lokale SSH-client (zoals OpenSSH of Git Bash op Windows)

---

## Stap 1: Maak een script om een tunnel op te zetten

Maak een nieuw tekstbestand aan met de extensie `.bat` (bijvoorbeeld `start-tunnel.bat`) en voeg de volgende regels toe. Pas de variabelen aan jouw situatie aan:

```
@echo off  
REM === Configuratie ===  
set PRIVATE_KEY="C:\pad\naar\jouw\private_key.pem"  
set REMOTE_USER=root  
set REMOTE_HOST=example.com  
set LOCAL_PORT=8080  
set REMOTE_PORT=80  

echo Starting SSH tunnel...  
ssh -i %PRIVATE_KEY% -L %LOCAL_PORT%:127.0.0.1:%REMOTE_PORT% %REMOTE_USER%@%REMOTE_HOST%
```  

pause

Sla het bestand op en dubbelklik erop om de tunnel te starten. Als alles goed is, kun je via je browser of client verbinding maken met de service op je server.

---

## Stap 2: Open de tunnel via je browser

Zodra de tunnel actief is, kun je via je browser naar deze URL gaan:

```
http://127.0.0.1:8080/
```

Vervang het poortnummer 8080 als je een andere poort hebt ingesteld.

---

## Stap 3: Reset het root-wachtwoord van MySQL (optioneel)

Als je niet kunt inloggen op de MySQL-database, kun je het wachtwoord opnieuw instellen. Volg deze stappen op de server:

1. Stop MySQL met het volgende commando:  
```
sudo systemctl stop mysql
```

2. Start MySQL in veilige modus (zonder wachtwoordcontrole):  
```
sudo mysqld_safe --skip-grant-tables &
```

3. Log in op MySQL zonder wachtwoord:  
```
mysql -u root
```

4. Als je een foutmelding krijgt over een socket, voer dan eerst het volgende uit:  
```
sudo mkdir -p /var/run/mysqld  
sudo chown mysql:mysql /var/run/mysqld  
sudo mysqld_safe --skip-grant-tables &  
mysql -u root
```

5. In MySQL, voer het volgende uit om het wachtwoord te wijzigen:

```
USE mysql;  
ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY 'nieuw_wachtwoord';  
FLUSH PRIVILEGES;
```

6. Stop MySQL en start het opnieuw:  
```
sudo killall mysqld  
sudo systemctl start mysql
```

7. Test of het wachtwoord werkt:  
```
mysql -u root -p
```

---

## Veelgestelde vragen

**Wat als ik geen private key heb?**  
Je kunt ook verbinden met je gebruikerswachtwoord, maar SSH keys zijn veiliger en aanbevolen.

**Kan ik een andere poort gebruiken dan 8080?**  
Ja, zolang de poort vrij is op je computer.

**Werkt dit ook met andere databases zoals PostgreSQL?**  
Ja, zolang je weet op welke poort de database luistert.

---

## Contact

Heb je vragen of feedback? Open een issue of stuur een bericht via dit repository.
