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
5. Utilisez `env.prod.example` comme base de variables cloud
6. Suivez `OPS_CLOUD_CHECKLIST.md` avant le go-live

## 🔧 DevOps / CI

- Pipeline CI GitHub Actions: `.github/workflows/ci.yml`
- Variables d'environnement de reference:
  - `env.example` (developpement)
  - `env.prod.example` (production/cloud)

## 📦 Conteneurisation (Docker)

Fichiers ajoutes:
- `Dockerfile`
- `compose.container.yaml`
- `.dockerignore`
- `env.container.example`
- `docker/nginx/default.conf`
- `docker/nginx/Dockerfile`
- `docker/php/entrypoint.sh`

### Lancer en local avec Docker

1. Copier les variables:
   ```bash
   cp env.container.example .env.container
   ```
   PowerShell:
   ```powershell
   Copy-Item env.container.example .env.container
   ```
2. Adapter les secrets dans `.env.container`.
3. Construire et lancer:
   ```bash
   docker compose --env-file .env.container -f compose.container.yaml up -d --build
   ```
4. Ouvrir l'application:
   - http://localhost:8080

### Commandes utiles

- Voir les logs:
  ```bash
  docker compose --env-file .env.container -f compose.container.yaml logs -f
  ```
- Arreter:
  ```bash
  docker compose --env-file .env.container -f compose.container.yaml down
  ```

### Mode AWS (ECS/Fargate ou EC2)

La stack Docker est prete pour le cloud:
- pas de bind mount obligatoire
- image `app` (PHP-FPM) + image `web` (Nginx)
- service `db` optionnel (profil `local` uniquement)

Pour AWS, utilisez de preference RDS PostgreSQL et des secrets
AWS Secrets Manager/SSM.

1. Dans `.env.container`, desactiver la base locale:
   ```env
   COMPOSE_PROFILES=
   DATABASE_URL=postgresql://app:strong-password@your-rds-endpoint:5432/e_shop?serverVersion=16&charset=utf8
   TRUSTED_HOSTS='^votre-domaine\\.com$'
   TRUSTED_PROXIES=REMOTE_ADDR
   RUN_MIGRATIONS=0
   ```
2. Builder les images:
   ```bash
   docker compose --env-file .env.container -f compose.container.yaml build
   ```
3. Pousser les images vers ECR.
4. Deployer sur ECS (2 conteneurs dans la meme task: `web` et `app`).
5. Exposer `web:80` derriere un ALB (HTTPS termine sur ALB).

Note:
- `RUN_MIGRATIONS=0` est recommande par defaut en production.
- Lancez les migrations via un job one-shot (CI/CD ou ECS RunTask).
- Si vous activez `RUN_MIGRATIONS=1`, vous pouvez definir
  `MIGRATIONS_STRICT=1` pour faire echouer le conteneur en cas d'erreur.

## 📄 Licence

Proprietary

