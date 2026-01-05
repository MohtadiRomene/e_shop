# 🧪 Guide de Test - E-commerce E-Shop

## 📋 Checklist de Test Complet

### ✅ Phase 1 : Configuration Initiale

1. **Vérifier la base de données**
   ```bash
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

2. **Créer un utilisateur admin**
   ```bash
   php bin/console app:create-admin admin@example.com password123
   ```

3. **Charger des données de test (optionnel)**
   ```bash
   php bin/console doctrine:fixtures:load
   ```

---

## 🛒 Phase 2 : Test du Flux de Commande

### Test 1 : Navigation et Recherche ✅
- [ ] Accéder à la page d'accueil
- [ ] Naviguer vers la boutique (SHOP)
- [ ] Filtrer par catégorie (Vêtements, Chaussures, Accessoires)
- [ ] Rechercher un produit via la barre de recherche
- [ ] Voir les détails d'un produit

### Test 2 : Ajout au Panier ✅
- [ ] Se connecter avec un compte client
- [ ] Voir un produit
- [ ] Vérifier l'affichage du stock
- [ ] Ajouter un produit au panier
- [ ] Ajouter plusieurs quantités
- [ ] Vérifier que le compteur dans le header s'incrémente
- [ ] Tester avec un produit en rupture de stock (doit être désactivé)

### Test 3 : Gestion du Panier ✅
- [ ] Accéder au panier
- [ ] Vérifier l'affichage des produits
- [ ] Modifier la quantité d'un produit
- [ ] Vérifier que le stock limite la quantité
- [ ] Retirer un produit du panier
- [ ] Vider le panier
- [ ] Vérifier les erreurs s'il y a rupture de stock

### Test 4 : Passer une Commande ✅
- [ ] Ajouter des produits au panier
- [ ] Cliquer sur "Passer la commande"
- [ ] Vérifier que la commande est créée
- [ ] Vérifier que le numéro de commande est généré (format: CMD-YYYYMMDD-XXXXXX)
- [ ] Vérifier que le panier est vidé après commande
- [ ] Vérifier que le stock est réduit

### Test 5 : Suivi de Commande ✅
- [ ] Accéder à "Mes Commandes"
- [ ] Vérifier l'affichage de la liste des commandes
- [ ] Vérifier l'affichage du numéro de commande
- [ ] Vérifier l'affichage du statut avec badge coloré
- [ ] Voir les détails d'une commande
- [ ] Vérifier l'affichage des produits commandés
- [ ] Voir la facture
- [ ] Tester l'impression de la facture

### Test 6 : Gestion des Erreurs ✅
- [ ] Tester avec un panier vide (ne doit pas permettre de commander)
- [ ] Tester avec un produit en rupture de stock
- [ ] Tester avec une quantité supérieure au stock
- [ ] Vérifier les messages d'erreur affichés

---

## 👨‍💼 Phase 3 : Test Interface Admin

### Test 7 : Administration ✅
- [ ] Se connecter en tant qu'admin
- [ ] Accéder au dashboard admin
- [ ] Voir les statistiques
- [ ] Gérer les produits (ajouter, modifier, supprimer)
- [ ] Gérer les commandes (voir, changer le statut)
- [ ] Gérer les promotions

---

## 🎯 Scénarios de Test Complets

### Scénario 1 : Achat Complet ✅
1. Utilisateur non connecté → S'inscrire
2. Parcourir les produits
3. Ajouter plusieurs produits au panier
4. Modifier les quantités
5. Passer la commande
6. Vérifier la facture
7. Vérifier que la commande apparaît dans "Mes Commandes"

### Scénario 2 : Gestion du Stock ✅
1. Admin ajoute un produit avec stock limité
2. Client ajoute le produit au panier
3. Client passe une commande
4. Vérifier que le stock est réduit
5. Tenter d'ajouter plus que le stock disponible → doit échouer

### Scénario 3 : Promotions ✅
1. Admin crée une promotion
2. Client voit le produit avec prix barré et prix réduit
3. Client ajoute au panier avec le prix réduit
4. Client passe commande avec le prix promotionnel

---

## ⚠️ Points à Vérifier

### Fonctionnalités ✅
- [x] Ajout au panier fonctionne
- [x] Modification de quantité fonctionne
- [x] Validation du stock fonctionne
- [x] Création de commande fonctionne
- [x] Génération du numéro de commande fonctionne
- [x] Génération de facture fonctionne
- [x] Affichage des statuts fonctionne
- [x] Compteur de panier fonctionne

### Affichage ✅
- [x] Prix affichés correctement partout
- [x] Totaux calculés correctement
- [x] Badges de statut colorés
- [x] Messages d'erreur clairs
- [x] Design cohérent

### Performance ✅
- [x] Pas d'erreurs dans les logs
- [x] Temps de chargement acceptable
- [x] Pas de requêtes N+1

---

## 🚀 Pour Tester Maintenant

1. **Démarrer le serveur**
   ```bash
   symfony server:start
   ```

2. **Ouvrir le navigateur**
   - Aller sur http://127.0.0.1:8000

3. **Tester le flux complet**
   - S'inscrire ou se connecter
   - Ajouter des produits au panier
   - Passer une commande
   - Vérifier que tout fonctionne

---

## 📝 Notes

- Tous les templates ont été corrigés pour utiliser les nouveaux types de prix
- La génération du numéro de commande se fait automatiquement
- Le stock est géré automatiquement lors des commandes
- Les erreurs sont affichées clairement dans l'interface

**Le site est prêt pour les tests ! 🎉**

