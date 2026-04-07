# OPS Checklist avant hebergement cloud

Cette checklist couvre les points minimum pour passer en production.

## 1) Configuration et secrets

- [ ] Ne jamais versionner de secrets applicatifs.
- [ ] Definir `APP_ENV=prod` et `APP_DEBUG=0`.
- [ ] Generer un `APP_SECRET` fort (64+ caracteres).
- [ ] Configurer `DATABASE_URL` avec un utilisateur dedie (pas `root`).
- [ ] Configurer `MAILER_DSN` de production (SendGrid, Mailgun, etc.).
- [ ] Definir `TRUSTED_PROXIES` et `TRUSTED_HOSTS`.

Exemple de base: `env.prod.example`.

## 2) Base de donnees

- [ ] Activer les sauvegardes automatiques quotidiennes.
- [ ] Tester une restauration de backup sur un environnement de test.
- [ ] Executer les migrations sur staging avant production.
- [ ] Mettre en place un compte DB avec privileges minimaux.

## 3) Build et release

- [ ] Le pipeline CI GitHub passe au vert.
- [ ] Installer les dependances prod uniquement:
  `composer install --no-dev --optimize-autoloader --classmap-authoritative`.
- [ ] Nettoyer/chauffer le cache Symfony pour prod:
  `php bin/console cache:clear --env=prod`.
- [ ] Executer les migrations:
  `php bin/console doctrine:migrations:migrate --no-interaction --env=prod`.

## 4) Serveur web et runtime

- [ ] Le DocumentRoot pointe vers `public/`.
- [ ] HTTPS force et certificat valide.
- [ ] Activer les headers de securite (HSTS, X-Frame-Options, etc.).
- [ ] Configurer un reverse proxy (Nginx/Traefik/Cloud LB) si necessaire.

## 5) Observabilite

- [ ] Logs centralises (stdout, ELK, Datadog, etc.).
- [ ] Alertes sur erreurs 5xx et latence.
- [ ] Monitoring DB (connexions, lenteurs, stockage).
- [ ] Metriques de sante applicative (temps de reponse, taux d'erreur).

## 6) Verification finale avant go-live

- [ ] Tests fonctionnels sur environnement de preproduction.
- [ ] Verification email, checkout, paiement, facturation.
- [ ] Verification roles admin/utilisateur.
- [ ] Plan de rollback valide (version N-1 deployable).
