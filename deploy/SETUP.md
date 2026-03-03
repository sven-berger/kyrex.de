# Deployment-Setup für kyrex.de

## Ziel

- Git Push auf GitHub
- Auf dem Server `git pull`-ähnlicher Deploy per Bare-Repo-Hook
- Laravel läuft unter `/var/www/kyrex.de`
- Apache bedient `kyrex.de` über `/var/www/kyrex.de/public`

## 1) Einmalig auf dem Server vorbereiten

```bash
sudo mkdir -p /home/sven/kyrex.de.git /var/www/kyrex.de /home/sven/deploy-logs
sudo chown -R sven:sven /home/sven/kyrex.de.git /var/www/kyrex.de /home/sven/deploy-logs

cd /home/sven/kyrex.de.git
git init --bare
```

## 2) Hook installieren

Datei aus diesem Repo kopieren:

- `deploy/post-receive` -> `/home/sven/kyrex.de.git/hooks/post-receive`
- `deploy/deploy.sh` -> `/var/www/kyrex.de/deploy/deploy.sh`

Dann ausführbar machen:

```bash
chmod +x /home/sven/kyrex.de.git/hooks/post-receive
chmod +x /var/www/kyrex.de/deploy/deploy.sh
```

## 3) GitHub-Deploy-Key setzen

Du hast bereits `.ssh` vom Server. Stelle sicher:

- Public Key liegt als **Deploy Key** im GitHub-Repo `sven-berger/kyrex.de`
- Private Key gehört dem User `sven` und hat Rechte `600`

Test:

```bash
ssh -T git@github.com
```

## 4) Initiales Checkout aus Bare-Repo

```bash
git --work-tree=/var/www/kyrex.de --git-dir=/home/sven/kyrex.de.git checkout -f main
cd /var/www/kyrex.de

cp .env.example .env
composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
php artisan key:generate
php artisan migrate --force
php artisan storage:link || true
chmod -R ug+rwX storage bootstrap/cache
```

## 5) Apache vHost anlegen

Datei aus diesem Repo kopieren:

- `deploy/apache2-kyrex.de.conf` -> `/etc/apache2/sites-available/kyrex.de.conf`

Dann aktivieren:

```bash
sudo a2enmod rewrite
sudo a2ensite kyrex.de.conf
sudo a2dissite 000-default.conf || true
sudo systemctl reload apache2
```

## 6) Optional: HTTPS (empfohlen)

```bash
sudo apt update
sudo apt install -y certbot python3-certbot-apache
sudo certbot --apache -d kyrex.de -d www.kyrex.de
```

## 7) Automatisch bei GitHub-Push deployen

Im Repo liegt bereits der Workflow:

- `.github/workflows/deploy.yml`

Lege in GitHub unter **Settings -> Secrets and variables -> Actions** an:

- `SSH_HOST` (z. B. Server-IP oder Hostname)
- `SSH_USER` (z. B. `sven`)
- `SSH_PRIVATE_KEY` (private Schlüsselinhalt für den Serverzugang)

Danach gilt:

1. Push auf `main`
2. GitHub Action verbindet sich per SSH
3. Server zieht Stand von `main` nach `/var/www/kyrex.de`
4. `deploy.sh` läuft automatisch
