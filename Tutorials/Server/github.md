# GitHub Project Klonen en Plaatsen in `/var/www`

In deze tutorial leggen we uit hoe je een project van GitHub op je server zet, zodat het in `/var/www` staat en klaar is om gebruikt te worden.

---

## 1. SSH Key Genereren en Toevoegen aan GitHub

### 1.1 SSH Key genereren

Als je nog geen SSH key hebt, maak er dan één aan (zorg dat je ingelogd bent als de gebruiker waarmee je werkt):

    ssh-keygen -t rsa -b 4096 -C "email@example.com"

Volg de stappen en laat de passphrase leeg of vul deze in als je dat wil.

### 1.2 Public key kopiëren

Kopieer de publieke SSH key naar je klembord:

    cat ~/.ssh/id_rsa.pub

### 1.3 Voeg de SSH key toe aan GitHub

- Ga naar: https://github.com/settings/keys  
- Klik op **New SSH key**  
- Plak je publieke key in het veld  
- Geef het een herkenbare naam  
- Klik op **Add SSH key**

---

## 2. SSH Verbinding Testen met GitHub

Test of de verbinding werkt:

    ssh -T git@github.com

Je krijgt een melding zoals:

    Hi username! You've successfully authenticated, but GitHub does not provide shell access.

Als je een "Permission denied" krijgt, controleer dan of je de juiste key hebt toegevoegd en probeer opnieuw.

---

## 3. Project Klonen naar `/var/www`

### 3.1 Maak de projectmap aan

Maak een nieuwe directory aan (pas `yourproject` aan naar jouw projectnaam):

    mkdir -p /var/www/yourproject
    cd /var/www/yourproject

### 3.2 Clone het GitHub repository

Gebruik SSH om het project te klonen (pas de URL aan naar jouw repository):

    git clone git@github.com:USERNAME/REPOSITORY.git .

> Let op de punt `.` aan het einde; hiermee clone je de inhoud direct in de huidige map.

---

## 4. Toegangsrechten instellen

Zorg dat de juiste gebruiker eigenaar is van de map:

    sudo chown -R yourusername:www-data /var/www/yourproject

En geef de juiste bestandsrechten:

    sudo chmod -R 775 /var/www/yourproject

---

## 5. Verder gebruik

Vanaf nu kun je met je gebruiker gewoon `git pull` en `git push` uitvoeren binnen `/var/www/yourproject` zonder telkens wachtwoorden in te voeren.

---

## Veelvoorkomende problemen

- **SSH key niet herkend?**  
  Zorg dat je `ssh-agent` draait en je private key geladen is.

- **Verkeerde permissies?**  
  Controleer de eigenaar en groep van bestanden.

- **Verbinding geweigerd?**  
  Controleer of je internet hebt en GitHub bereikbaar is.

---

Met deze stappen staat je project in `/var/www` en ben je klaar om verder te werken.

---

**Succes!**
