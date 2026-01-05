# Configuration Gmail - Guide Rapide

## ⚡ Configuration en 3 étapes

### Étape 1 : Générer un mot de passe d'application Gmail

1. Allez sur : **https://myaccount.google.com/apppasswords**
2. Si demandé, connectez-vous avec votre compte Google
3. Sélectionnez :
   - **Application** : Mail
   - **Appareil** : Autre (nom personnalisé) → Entrez "Kaira"
4. Cliquez sur **"Générer"**
5. **Copiez le mot de passe** (16 caractères, format : `abcd efgh ijkl mnop`)

### Étape 2 : Modifier le fichier `.env`

Ouvrez le fichier `.env` dans votre projet et trouvez cette section :

```env
###> symfony/mailer ###
MAILER_DSN=null://null
###< symfony/mailer ###
```

**Remplacez par** (avec VOTRE email et VOTRE mot de passe d'application) :

```env
###> symfony/mailer ###
MAILER_DSN=smtp://mohtadiromene00@gmail.com:VOTRE-MOT-DE-PASSE-APP@smtp.gmail.com:587
###< symfony/mailer ###
```

**Exemple concret** (remplacez le mot de passe par celui que vous avez généré) :
```env
###> symfony/mailer ###
MAILER_DSN=smtp://mohtadiromene00@gmail.com:abcd-efgh-ijkl-mnop@smtp.gmail.com:587
###< symfony/mailer ###
```

⚠️ **Important** : 
- Utilisez le mot de passe d'application (16 caractères), PAS votre mot de passe Gmail normal
- Supprimez les espaces du mot de passe (ex: `abcd-efgh-ijkl-mnop`)

### Étape 3 : Vider le cache

```bash
php bin/console cache:clear
```

## ✅ Test

Testez maintenant :

```bash
php bin/console app:test-reset-password --email=mohtadiromene00@gmail.com
```

L'email devrait arriver dans votre boîte Gmail !

## 🔍 Vérification

Après configuration, quand vous cliquez sur "ENVOYER LE LIEN DE RÉINITIALISATION" :
1. ✅ L'email sera envoyé à votre boîte Gmail
2. ✅ Vous recevrez l'email dans votre boîte de réception
3. ✅ Cliquez sur le lien dans l'email pour réinitialiser le mot de passe

## ❌ Si ça ne fonctionne pas

1. Vérifiez que la validation en 2 étapes est activée
2. Vérifiez que vous utilisez un mot de passe d'application, pas votre mot de passe normal
3. Vérifiez les logs : `var/log/dev.log`
4. Testez avec la commande de test pour voir les erreurs
