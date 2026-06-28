# 📱 E-Wallet Mobile Money (CLI)

Une application console (CLI) en PHP simulant un système de portefeuille électronique (Mobile Money) inspiré des solutions utilisées au Sénégal (Wave, Orange Money). Ce projet a été développé en deux phases distinctes pour démontrer l'évolution d'une architecture logicielle.

## 🚀 Fonctionnalités Métier
- **Création de Wallet** : Enregistrement de clients avec contrôle d'unicité des numéros sénégalais (77, 78, 76, 70, 75).
- **Dépôts & Retraits** : Gestion des soldes en temps réel.
- **Calcul de Frais** : Application de frais de retrait de 1% plafonnés à 5 000 CFA.
- **Historique** : Journal de toutes les transactions effectuées.

---

## 🏗️ Architecture & Stratégie de Versionnage

Le projet est rigoureusement structuré en deux parties isolées grâce aux branches Git :

### 🔹 Partie A : Approche Procédurale Pure (Tag `v1.0.0`)
*Accessible sur la branche `develop-partA`*
- Code 100% procédural (fonctions globales et tableaux associatifs simples).
- Aucune programmation orientée objet, aucun espace de noms (Namespace).
- Inclusions directes via `require_once`.

### 🔹 Partie B : Modernisation & Refactorisation
*Accessible sur la branche `develop-partB`*
- **Encapsulation par Namespaces** : Isolation des responsabilités (`EWallet\Validator`, `EWallet\Repository`, `EWallet\Services`, `EWallet\Controller`).
- **Programmation Fonctionnelle** : Remplacement de toutes les boucles traditionnelles (`foreach`) par des fonctions de manipulation de tableaux natives de PHP (`array_filter`, `array_combine`, etc.).

---

## 🛠️ Structure des Fichiers
- `index.php` : Point d'entrée de l'application et boucle principale `do...while`.
- `controller.php` : Gestion des saisies utilisateurs (`readline`) et affichage des menus.
- `services.php` : Logique métier (création, exécution des transactions, calculs des frais).
- `repository.php` : Persistance des données en mémoire vive (simulation de base de données).
- `validator.php` : Fonctions de validation (formats de numéros, codes secrets, montants).

---

## 💻 Comment lancer le projet ?
1. Clonez le dépôt et placez-vous sur la branche de votre choix :
   ```bash
   git clone [https://github.com/M2129/ewallet.git](https://github.com/M2129/ewallet.git)
   cd ewallet
   # Pour voir la partie A : git checkout develop-partA
   # Pour voir la partie B : git checkout develop-partB