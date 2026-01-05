# 📧 Configuration Gmail pour l'envoi d'emails réels

## Étapes rapides

### 1. Activer la validation en 2 étapes
1. Allez sur : https://myaccount.google.com/security
2. Activez "Validation en deux étapes" si ce n'est pas déjà fait

### 2. Générer un mot de passe d'application
1. Allez sur : https://myaccount.google.com/apppasswords
2. Sélectionnez "Mail" → "Autre (nom personnalisé)"
3. Entrez "Kaira E-Shop" comme nom
4. Cliquez sur "Générer"
5. **Copiez le mot de passe** (16 caractères, format : `abcd-efgh-ijkl-mnop`)

### 3. Configurer dans `.env`

Ouvrez le fichier `.env` et modifiez cette ligne :

```env
###> symfony/mailer ###
MAILER_DSN=smtp://votre-email@gmail.com:VOTRE-MOT-DE-PASSE-APP@smtp.gmail.com:587
###< symfony/mailer ###
```

**Exemple avec votre email :**
```env
###> symfony/mailer ###
MAILER_DSN=smtp://mohtadiromene00@gmail.com:abcd-efgh-ijkl-mnop@smtp.gmail.com:587
###< symfony/mailer ###
```

⚠️ **Remplacez** :
- `mohtadiromene00@gmail.com` par votre email Gmail
- `abcd-efgh-ijkl-mnop` par votre mot de passe d'application

### 4. Vider le cache

```bash
php bin/console cache:clear
```

### 5. Tester

```bash
php bin/console app:test-reset-password --email=mohtadiromene00@gmail.com
```

L'email devrait arriver dans votre boîte Gmail !

## 🔒 Sécurité

- ⚠️ Ne partagez JAMAIS votre mot de passe d'application
- ⚠️ Ne commitez JAMAIS le fichier `.env` dans Git
- ✅ Le fichier `.env` devrait être dans `.gitignore`

## ✅ Vérification

Après configuration, quand vous cliquez sur "ENVOYER LE LIEN DE RÉINITIALISATION" :
1. L'email sera envoyé à la vraie boîte mail
2. Vous recevrez l'email dans Gmail
3. Cliquez sur le lien dans l'email pour réinitialiser le mot de passe
