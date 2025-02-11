# WAW Travel - Initialisation du Projet Symfony

## 📌 Prérequis
Avant de commencer, assurez-vous d'avoir installé :

- **PHP** (vérifiez avec `php -v`)
- **Composer** (vérifiez avec `composer -V`)
- **MySQL** (ou MariaDB compatible, vérifiez avec `mysql --version`)
- **Symfony CLI** (optionnel mais recommandé, vérifiez avec `symfony -v`)

---

## 🚀 Installation du projet

```bash
git clone votre-lien-git
cd wawTravel
composer install
```

Modifiez ensuite le fichier `.env.local` avec vos informations de base de données :

```dotenv
DATABASE_URL="mysql://nom-utilisateur:mot-de-passe@127.0.0.1:3306/nom-base-de-donnee?serverVersion=8.0"
```

---

## 🗄️ Initialisation de la base de données

```bash
php bin/console doctrine:database:create
mysql -u root -p wawTravel < wawTravelData.sql
php bin/console doctrine:migrations:migrate
```

---

## ✅ Lancer le serveur Symfony

```bash
symfony serve -d
# ou
php -S 127.0.0.1:8000 -t public/
```

Accédez à l’application : [http://127.0.0.1:8000/](http://127.0.0.1:8000/)

---


## 📜 Conclusion
Votre projet Symfony **WAW Travel** est maintenant installé et fonctionnel ! 🚀
Si vous rencontrez des problèmes, vérifiez les logs dans `var/log/` ou exécutez `php bin/console debug:autowiring` pour vérifier les services Symfony.


