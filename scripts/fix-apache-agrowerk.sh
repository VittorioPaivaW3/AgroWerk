#!/usr/bin/env bash
set -euo pipefail

SITE_FILE="/etc/apache2/sites-available/000-default.conf"
PROJECT_PUBLIC="/var/www/html/AgroWerk/public"

# Enable rewrite module (required for Laravel routes)
a2enmod rewrite

# Backup current site config
cp "$SITE_FILE" "${SITE_FILE}.bak_codex_$(date +%Y%m%d_%H%M%S)"

# Replace site config with AgroWerk alias + override
cat > "$SITE_FILE" <<'APACHE'
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html

    Alias /AgroWerk /var/www/html/AgroWerk/public

    <Directory /var/www/html/AgroWerk/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
APACHE

apache2ctl configtest
systemctl restart apache2

echo "Apache ajustado com sucesso."
