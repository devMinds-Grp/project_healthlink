# 🏥 Application de Service Médical

> Une application web développée avec **Symfony** qui permet de gérer différents services médicaux tels que les utilisateurs, les rendez-vous, les prescriptions, les soins, les dons du sang, les réclamations et un forum d'échange.

## 📋 Description
Cette application a été conçue pour aider les professionnels de santé ainsi que les patients à mieux gérer leurs interactions et services au sein d’un établissement médical. Elle inclut 6 modules principaux :

### 🧩 Modules Principaux
1. **Gestion des Utilisateurs** : Création, modification et suppression des utilisateurs (patients, médecins, infirmiers, kinésithérapeutes, administrateurs...), gestion des rôles et permissions.
2. **Gestion du Forum** : Forums thématiques, création de sujets et participation aux discussions, modération par les admins.
3. **Gestion des Réclamations** : Soumission de réclamations par les patients, suivi et réponse par le personnel concerné.
4. **Gestion des Rendez-vous et Prescriptions** : Prise de rendez-vous en ligne, génération et gestion des ordonnances/prescriptions, confirmation, annulation et rappel automatique.
5. **Gestion des Soins** : Enregistrement des soins prodigués par les kinésithérapeutes ou infirmiers, historique des soins par patient.
6. **Gestion des Dons du Sang** : Inscription aux campagnes de don du sang, suivi des donneurs et historique des dons, recherche selon les groupes sanguins.

## 🛠️ Technologies Utilisées
PHP avec le framework Symfony
MySQL 
Twig 
Doctrine ORM
JavaScript
Bootstrap 
Mailer

## 📦 Installation
```bash
# Étape 1 : Cloner le projet
git clone https://github.com/devMinds-Grp/project_healthlink.git
cd project_healthlink

# Étape 2 : Installer les dépendances
composer install
npm install
npm run build

# Étape 3 : Configurer la base de données
# Modifiez le fichier `.env` :
DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=8.0"

# Étape 4 : Créer la base de données et migrer
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# Étape 5 : Charger les fixtures (optionnel)
php bin/console doctrine:fixtures:load

# Étape 6 : Lancer le serveur
symfony server:start
```

## 🧪 Tests
```bash
php bin/phpunit
```

## 🤝 Contribution
Les contributions sont bienvenues ! Si vous souhaitez participer :
1. Fork le repo
2. Crée une branche (`git checkout -b feature/feature-name`)
3. Commit tes changements (`git commit -m 'feat: add new feature'`)
4. Push sur la branche (`git push origin feature/feature-name`)
5. Ouvre une Pull Request


## 📞 Contact
Pour toute question ou suggestion, contactez-nous à :
📧 healthlink@devminds.com  
💻 GitHub: [github.com/project_healthlink](https://github.com/devMinds-Grp/project_healthlink )
```
