# Configuration Email Réel - Envoi par SMTP

## 📧 Configuration pour envoyer de vrais emails

Pour que les emails de réinitialisation de mot de passe soient envoyés à la vraie boîte mail, vous devez configurer `MAILER_DSN` avec un serveur SMTP.

## Option 1 : Gmail (Recommandé)

### Étapes :

1. **Activez la validation en 2 étapes** sur votre compte Google :
   - Allez sur https://myaccount.google.com/security
   - Activez "Validation en deux étapes"

2. **Générez un mot de passe d'application** :
   - Allez sur https://myaccount.google.com/apppasswords
   - Sélectionnez "Mail" et "Autre (nom personnalisé)"
   - Entrez "Kaira E-Shop" comme nom
   - Cliquez sur "Générer"
   - **Copiez le mot de passe généré** (16 caractères)

3. **Configurez dans `.env`** :
   ```env
   ###> symfony/mailer ###
   MAILER_DSN=smtp://votre-email@gmail.com:VOTRE-MOT-DE-PASSE-APP@smtp.gmail.com:587
   ###< symfony/mailer ###
   ```

   **Exemple :**
   ```env
   MAILER_DSN=smtp://mohtadiromene00@gmail.com:abcd-efgh-ijkl-mnop@smtp.gmail.com:587
   ```

4. **Videz le cache** :
   ```bash
   php bin/console cache:clear
   ```

## Option 2 : Mailtrap (Pour les tests)

Mailtrap est un service gratuit qui capture les emails pour les tests.

1. **Créez un compte** sur https://mailtrap.io
2. **Créez une "Inbox"**
3. **Copiez les identifiants SMTP**

**Configuration dans `.env`** :
```env
###> symfony/mailer ###
MAILER_DSN=smtp://username:password@smtp.mailtrap.io:2525
###< symfony/mailer ###
```

## Option 3 : Autre service SMTP

**Format général :**
```env
MAILER_DSN=smtp://user:password@smtp.example.com:587
```

**Exemples :**
- **Outlook/Hotmail** : `smtp://email@outlook.com:password@smtp-mail.outlook.com:587`
- **Yahoo** : `smtp://email@yahoo.com:password@smtp.mail.yahoo.com:587`
- **OVH** : `smtp://email@domain.com:password@ssl0.ovh.net:465`

## 🔒 Sécurité

⚠️ **Important** : Ne commitez JAMAIS le fichier `.env` avec vos mots de passe dans Git !

Le fichier `.env` devrait être dans `.gitignore`.

## ✅ Test

Après configuration, testez avec :

```bash
php bin/console app:test-reset-password --email=votre-email@example.com
```

L'email devrait arriver dans votre vraie boîte mail !

## 🐛 Dépannage

### Erreur : "Connection refused"
- Vérifiez que le port n'est pas bloqué par un firewall
- Vérifiez les identifiants

### Erreur : "Authentication failed"
- Pour Gmail : Utilisez un mot de passe d'application, pas votre mot de passe normal
- Vérifiez que la validation en 2 étapes est activée

### Les emails n'arrivent pas
- Vérifiez les spams
- Vérifiez les logs : `var/log/dev.log`
- Testez avec la commande de test
