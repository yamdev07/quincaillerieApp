# 🛒 Application de Gestion de Quincaillerie

Une application web professionnelle permettant de gérer efficacement les produits, ventes, achats, fournisseurs, clients et stocks d'une quincaillerie.  
Développée pour offrir une interface simple, rapide et intuitive, elle facilite le suivi des opérations quotidiennes et améliore la productivité.

---

## ✨ Fonctionnalités principales

### 🔹 Gestion des Produits
- Ajout, modification et suppression de produits  
- Catégorisation des articles  
- Gestion des prix d’achat et de vente  
- Suivi des niveaux de stock  
- Alertes de stock faible

### 🔹 Gestion des Ventes
- Enregistrement rapide des ventes  
- Calcul automatique du total  
- Gestion des paiements (comptant / crédit)  
- Historique complet des transactions  
- Impression de facture

### 🔹 Gestion des Achats
- Enregistrement des commandes auprès des fournisseurs  
- Suivi des réceptions  
- Mise à jour automatique des stocks

### 🔹 Gestion des Clients
- Ajout de clients  
- Suivi des dettes et paiements  
- Historique des achats par client

### 🔹 Gestion des Fournisseurs
- Base de données des fournisseurs  
- Historique des commandes

### 🔹 Tableau de Bord (Dashboard)
- Vue d’ensemble des ventes du jour  
- Recettes totales  
- Produits en rupture  
- Meilleures ventes  
- Activités récentes

---

## 🧰 Technologies utilisées

- **Backend :** Laravel  
- **Frontend :** Blade / Bootstrap / JavaScript  
- **Base de données :** MySQL  
- **Authentification :** Laravel Auth / Roles & Permissions  
- **Autres packages :**  
  - Spatie Laravel Permission (optionnel)  

---

## 🏗️ Installation & Configuration

1. Cloner le projet
```bash
git clone https://github.com/username/quincaillerie-app.git
cd quincaillerie-app
````
---
Installer les dépendances
````
composer install
npm install
npm run build
````
---
Configurer l’environnement
````
cp .env.example .env
php artisan key:generate
````
---
- Configurer la base de données et les paramètres SMTP dans .env.

- Lancer les migrations et seeders
````
php artisan migrate --seed
````

Lancer le serveur
````
php artisan serve
````
---
👥 Gestion des rôles et permissions

Admin : Accès total

Caissier : Gère les ventes

Gestionnaire de stock : Gère les produits et stocks

Comptes par défaut (si seed activé) :

Rôle	Email	Mot de passe
Admin	admin@example.com
	password
    
## 📂 Structure du projet
````
- app/
- resources/
  - views/
  - ...
- routes/
  - web.php
- database/
  - migrations/
  - seeders/
- public/
````
---
🎯 Objectif du projet

Offrir une solution complète et facile d'utilisation pour petites et moyennes quincailleries :

Meilleure gestion du stock

Rapidité à la caisse

Transparence des ventes

Aide à la décision via dashboard

---
🤝 Contributions
---
Fork & Pull Requests bienvenues.

Auteurs
---

Yoann yamd
