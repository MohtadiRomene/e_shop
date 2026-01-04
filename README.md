# E-Shop - Application E-commerce Symfony

Application e-commerce professionnelle développée avec Symfony 7.3.

## 🚀 Installation

### Prérequis
- PHP 8.2 ou supérieur
- Composer
- Symfony CLI (recommandé)
- Base de données (MySQL, PostgreSQL ou SQLite)

### Configuration

1. **Installer les dépendances**
   ```bash
   composer install
   ```

2. **Configurer l'environnement**
   - Créez un fichier `.env` à la racine du projet
   - Configurez les variables suivantes :
   ```env
   APP_ENV=dev
   APP_SECRET=your-random-secret-key-here
   
   # Base de données (exemple avec MySQL)
   DATABASE_URL="mysql://root:password@127.0.0.1:3306/e_shop?serverVersion=8.0.32&charset=utf8mb4"
   
   # Mailer (pour les tests, utilisez Mailtrap)
   MAILER_DSN=smtp://127.0.0.1:1025?verify_peer=0
   ```

3. **Générer la clé secrète**
   ```bash
   php -r "echo bin2hex(random_bytes(32));"
   ```
   Copiez le résultat dans `APP_SECRET`

4. **Créer la base de données et exécuter les migrations**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

5. **Charger les données de test (optionnel)**
   ```bash
   php bin/console doctrine:fixtures:load
   ```

6. **Créer un utilisateur admin**
   ```bash
   php bin/console app:create-admin email@example.com password
   ```

7. **Lancer le serveur**
   ```bash
   symfony server:start
   ```

## 📋 Fonctionnalités

- ✅ Gestion des produits (Vêtements, Chaussures, Accessoires)
- ✅ Panier d'achat
- ✅ Système de commande
- ✅ Génération de factures
- ✅ Système de promotions
- ✅ Avis clients
- ✅ Interface d'administration
- ✅ Authentification sécurisée
- ✅ Recherche de produits

## 🛠️ Structure du projet

```
src/
├── Controller/        # Contrôleurs de l'application
├── Entity/           # Entités Doctrine
├── Form/             # Formulaires Symfony
├── Repository/       # Repositories Doctrine
├── Service/          # Services métier
└── Security/         # Configuration de sécurité
```

## 🔒 Sécurité

- Protection CSRF activée
- Validation des entrées utilisateur
- Protection XSS via Twig
- Hachage sécurisé des mots de passe
- Contrôle d'accès basé sur les rôles

## 📝 Développement

### Exécuter les tests
```bash
php bin/phpunit
```

### Vérifier le code avec PHPStan
```bash
vendor/bin/phpstan analyse src
```

### Créer une migration
```bash
php bin/console make:migration
```

### Créer une entité
```bash
php bin/console make:entity
```

## 📧 Configuration Email

Pour le développement, configurez Mailtrap ou un service similaire.

Pour la production, utilisez SendGrid, Mailgun, ou un autre service professionnel.

## 🚀 Déploiement

1. Configurez les variables d'environnement sur votre serveur
2. Exécutez `composer install --no-dev --optimize-autoloader`
3. Exécutez les migrations : `php bin/console doctrine:migrations:migrate`
4. Configurez votre serveur web (Apache/Nginx) pour pointer vers `/public`

## 📄 Licence

Proprietary
