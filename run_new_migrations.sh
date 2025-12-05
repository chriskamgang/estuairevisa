#!/bin/bash

# Script pour exécuter uniquement les nouvelles migrations (favorites et notifications)
# Usage: bash run_new_migrations.sh

echo "🚀 Exécution des nouvelles migrations..."

cd /var/www/estuairevisa.com/web/estuairevisa/core

# Option 1: Exécuter uniquement les migrations spécifiques
php artisan migrate --path=/database/migrations/2025_12_05_063210_create_favorites_table.php --force

php artisan migrate --path=/database/migrations/2025_12_05_063245_create_notifications_table.php --force

echo "✅ Migrations terminées!"

# Vérifier les tables créées
echo ""
echo "📊 Vérification des tables..."
php artisan db:table favorites --connection=mysql 2>/dev/null && echo "✓ Table 'favorites' créée" || echo "✗ Erreur table 'favorites'"
php artisan db:table user_notifications --connection=mysql 2>/dev/null && echo "✓ Table 'user_notifications' créée" || echo "✗ Erreur table 'user_notifications'"

echo ""
echo "🎉 Installation terminée!"
