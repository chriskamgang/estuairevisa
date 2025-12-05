<?php
/**
 * Script d'installation des nouvelles fonctionnalités
 * (Favoris et Notifications)
 *
 * Usage: php install_new_features.php
 */

echo "🚀 Installation des nouvelles fonctionnalités...\n\n";

// Configuration de la base de données
// IMPORTANT: Modifiez ces valeurs selon votre configuration
$host = 'localhost';
$dbname = 'immigo'; // Nom de votre base de données
$username = 'root'; // Votre utilisateur MySQL
$password = ''; // Votre mot de passe MySQL

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "✓ Connexion à la base de données réussie\n\n";

    // ========================================
    // 1. Créer la table 'favorites'
    // ========================================
    echo "📦 Création de la table 'favorites'...\n";

    $sqlFavorites = "
    CREATE TABLE IF NOT EXISTS `favorites` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` bigint(20) UNSIGNED NOT NULL,
        `favorable_type` varchar(255) NOT NULL,
        `favorable_id` bigint(20) UNSIGNED NOT NULL,
        `collection` varchar(255) DEFAULT NULL,
        `notes` text DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `favorites_user_id_favorable_type_favorable_id_index` (`user_id`, `favorable_type`, `favorable_id`),
        UNIQUE KEY `favorites_user_id_favorable_type_favorable_id_unique` (`user_id`, `favorable_type`, `favorable_id`),
        CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $pdo->exec($sqlFavorites);
    echo "✓ Table 'favorites' créée avec succès\n\n";

    // ========================================
    // 2. Créer la table 'user_notifications'
    // ========================================
    echo "📦 Création de la table 'user_notifications'...\n";

    $sqlNotifications = "
    CREATE TABLE IF NOT EXISTS `user_notifications` (
        `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` bigint(20) UNSIGNED NOT NULL,
        `type` varchar(255) NOT NULL,
        `title` varchar(255) NOT NULL,
        `message` text NOT NULL,
        `data` text DEFAULT NULL,
        `url` varchar(255) DEFAULT NULL,
        `read` tinyint(1) NOT NULL DEFAULT 0,
        `read_at` timestamp NULL DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT NULL,
        `updated_at` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`),
        KEY `user_notifications_user_id_read_index` (`user_id`, `read`),
        KEY `user_notifications_user_id_type_index` (`user_id`, `type`),
        CONSTRAINT `user_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ";

    $pdo->exec($sqlNotifications);
    echo "✓ Table 'user_notifications' créée avec succès\n\n";

    // ========================================
    // 3. Enregistrer les migrations
    // ========================================
    echo "📝 Enregistrement des migrations...\n";

    // Vérifier si la table migrations existe
    $checkMigrations = $pdo->query("SHOW TABLES LIKE 'migrations'")->rowCount();

    if ($checkMigrations > 0) {
        // Insérer les enregistrements de migration
        $migrations = [
            '2025_12_05_063210_create_favorites_table',
            '2025_12_05_063245_create_notifications_table'
        ];

        foreach ($migrations as $migration) {
            // Vérifier si la migration n'existe pas déjà
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM migrations WHERE migration = ?");
            $stmt->execute([$migration]);

            if ($stmt->fetchColumn() == 0) {
                $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, 1)")
                    ->execute([$migration]);
                echo "✓ Migration '{$migration}' enregistrée\n";
            } else {
                echo "- Migration '{$migration}' déjà enregistrée\n";
            }
        }
    } else {
        echo "⚠ Table 'migrations' introuvable, les migrations ne seront pas enregistrées\n";
    }

    echo "\n";

    // ========================================
    // 4. Vérifications finales
    // ========================================
    echo "🔍 Vérifications finales...\n";

    $tables = ['favorites', 'user_notifications'];
    foreach ($tables as $table) {
        $result = $pdo->query("SHOW TABLES LIKE '{$table}'")->rowCount();
        if ($result > 0) {
            $count = $pdo->query("SELECT COUNT(*) FROM {$table}")->fetchColumn();
            echo "✓ Table '{$table}' existe ({$count} enregistrements)\n";
        } else {
            echo "✗ Table '{$table}' introuvable\n";
        }
    }

    echo "\n";
    echo "🎉 Installation terminée avec succès!\n";
    echo "\n";
    echo "📚 Prochaines étapes:\n";
    echo "  1. Consultez IMPLEMENTATION_SUMMARY.md pour les détails techniques\n";
    echo "  2. Consultez GUIDE_UTILISATION.md pour les exemples d'utilisation\n";
    echo "  3. Intégrez les vues dans votre application\n";
    echo "  4. Testez les nouvelles fonctionnalités\n";
    echo "\n";

} catch (PDOException $e) {
    echo "\n❌ Erreur: " . $e->getMessage() . "\n";
    echo "\n💡 Vérifiez:\n";
    echo "  - Les credentials de la base de données (lignes 10-13 de ce fichier)\n";
    echo "  - Que MySQL est bien démarré\n";
    echo "  - Que la base de données '{$dbname}' existe\n";
    echo "\n";
    exit(1);
}
