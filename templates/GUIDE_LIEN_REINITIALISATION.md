# Guide : Où trouver le lien de réinitialisation ?

## 📍 Emplacement du lien

**En mode développement, l'email n'est PAS envoyé à votre boîte mail réelle.** Il est sauvegardé dans un fichier sur votre ordinateur.

### Chemin du dossier :
```
C:\Users\Mohtadi\Downloads\e_shop-main\e_shop-main\var\mail\
```

## 📋 Étapes pour trouver le lien

### Méthode 1 : Via l'Explorateur Windows

1. **Ouvrez l'Explorateur de fichiers Windows**
2. **Naviguez vers votre projet** :
   ```
   C:\Users\Mohtadi\Downloads\e_shop-main\e_shop-main
   ```
3. **Ouvrez le dossier `var`**
4. **Ouvrez le dossier `mail`**
5. **Trouvez le fichier HTML le plus récent** (trié par date de modification)
6. **Double-cliquez sur le fichier** pour l'ouvrir dans votre navigateur
7. **Cliquez sur le lien de réinitialisation** dans l'email

### Méthode 2 : Via la commande

```bash
php bin/console app:test-reset-password --email=votre-email@example.com
```

Cette commande affiche directement l'URL de réinitialisation dans le terminal.

### Méthode 3 : Ouvrir directement le dossier

Dans PowerShell ou CMD, exécutez :
```powershell
explorer var\mail
```

## 📧 Format des fichiers email

Les fichiers sont nommés ainsi :
```
2025-01-05_15-30-45_abc123def456.html
```

Où :
- `2025-01-05_15-30-45` = Date et heure de création
- `abc123def456` = Identifiant unique
- `.html` = Format HTML (ouvrable dans un navigateur)

## 🔗 Contenu de l'email

L'email contient :
- Un bouton "Réinitialiser mon mot de passe" (cliquable)
- Un lien texte complet (copiable)
- Le token de réinitialisation

## ⚠️ Important

- **Le lien est valide pendant 1 heure**
- **Un seul lien est valide à la fois** (un nouveau lien invalide l'ancien)
- **En production**, les emails seront envoyés à votre vraie boîte mail

## 🚀 Pour la production

Quand vous déployez en production, configurez `MAILER_DSN` avec un vrai serveur SMTP dans le fichier `.env` :

```env
MAILER_DSN=smtp://user:password@smtp.example.com:587
```

Les emails seront alors envoyés à la vraie boîte mail des utilisateurs.
