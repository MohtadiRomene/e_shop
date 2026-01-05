# Guide de Configuration Email - Réinitialisation de Mot de Passe

## Configuration MAILER_DSN

### ✅ Option 1 : Mode Fichier (CONFIGURÉ - Recommandé pour le développement)

**Cette option est maintenant automatiquement activée !** Un service personnalisé (`FileMailerService`) sauvegarde les emails dans des fichiers au lieu de les envoyer réellement. Parfait pour tester sans configurer un serveur SMTP.

**Configuration actuelle :**
- `MAILER_DSN=null://null` (le service FileMailerService gère l'envoi)
- Les emails sont sauvegardés dans `var/mail/` sous forme de fichiers `.html`

**Aucune configuration supplémentaire nécessaire !** Le système fonctionne automatiquement.

### Option 2 : Mailtrap (Service de test gratuit)

Mailtrap est un service gratuit qui capture les emails pour les tests.

1. Créez un compte sur [mailtrap.io](https://mailtrap.io)
2. Créez une "Inbox" de test
3. Copiez les identifiants SMTP

**Dans le fichier `.env` :**

```env
###> symfony/mailer ###
MAILER_DSN=smtp://username:password@smtp.mailtrap.io:2525
###< symfony/mailer ###
```

### Option 3 : Gmail (Pour la production)

**Dans le fichier `.env` :**

```env
###> symfony/mailer ###
MAILER_DSN=smtp://votre-email@gmail.com:votre-mot-de-passe-app@smtp.gmail.com:587
###< symfony/mailer ###
```

**Note :** Pour Gmail, vous devez utiliser un "Mot de passe d'application" :
1. Allez dans votre compte Google
2. Sécurité → Validation en 2 étapes
3. Mots de passe des applications
4. Générez un mot de passe pour "Mail"

### Option 4 : Autre service SMTP

```env
###> symfony/mailer ###
MAILER_DSN=smtp://user:password@smtp.example.com:587
###< symfony/mailer ###
```

## Test de la Fonctionnalité

### Méthode 1 : Commande de test

```bash
php bin/console app:test-reset-password
```

Cette commande va :
- Lister tous les utilisateurs
- Vous permettre de choisir un utilisateur
- Générer un token de réinitialisation
- Tester l'envoi d'email
- Afficher l'URL de réinitialisation

**Options :**
- `--email=email@example.com` : Spécifier directement l'email
- `--create-token` : Créer seulement le token sans envoyer d'email

**Exemple :**
```bash
php bin/console app:test-reset-password --email=admin@kaira.com
```

### Méthode 2 : Test manuel via l'interface web

1. Allez sur `/login`
2. Cliquez sur "Mot de passe oublié ?"
3. Entrez l'email d'un utilisateur existant
4. Vérifiez la réception de l'email (ou le fichier dans `var/mail/` si mode fichier)
5. Cliquez sur le lien dans l'email
6. Entrez un nouveau mot de passe
7. Connectez-vous avec le nouveau mot de passe

## Vérification

### Vérifier que MAILER_DSN est bien configuré

```bash
php bin/console debug:container mailer
```

### Vérifier les emails sauvegardés (mode fichier)

Les emails sont sauvegardés dans `var/mail/` avec :
- Un fichier `.txt` pour le texte brut
- Un fichier `.html` pour le HTML

## Dépannage

### Erreur : "Transport not available"

Vérifiez que MAILER_DSN est correctement configuré dans `.env`

### Erreur : "Connection refused"

Vérifiez :
- Que le serveur SMTP est accessible
- Que les identifiants sont corrects
- Que le port n'est pas bloqué par un firewall

### Les emails ne sont pas envoyés

1. Vérifiez les logs : `var/log/dev.log`
2. Testez avec la commande : `php bin/console app:test-reset-password`
3. Vérifiez la configuration dans `.env`
