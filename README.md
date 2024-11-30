Forum Vaudois
================

Commment installer et faire tourner ce programme sur votre machine

Getting Started
---------------
Pour installer et faire tourner ce programme, vous aurez besoin de 
* Symfony mailer
* Composer
* PHP 7.4 ou supérieur
* MAMP ou XAMP
* DB Browser for SQLite


1. Cloner ce repositoire à la racine de votre dossier htdocs selon ce chemin:
```bash
$> /Applications/MAMP/htdocs/ForumVaudois
```
Pour cloner cliquez sur le bouton code en vert et copier le lien ssh.
voici une commande qui pourrait vous aider:
```bash
git clone git@github.com:AgatheMakumbi/forumVaudois.git
```

2. Cd dans /ForumVaudois et Installer symfony mailer
```bash
composer require symfony/mailer
```

3. Pour utiliser les methodes des classes, veuillez ajouter le require_once pour le autoload.php ainsi que le use pour la classe dont vous vouez utiliser les methodes.
***Attention à ce que les chemins soient corrects ***

Exemple: 
```php
require_once 'vendor/autoload.php';

use M521\ForumVaudois\CRUDManager\DbManagerCRUD;
use M521\ForumVaudois\Entity\Personne;

```

4. Pour utiliser les methodes des classes, vous devez instancier la classe et appeler la methode que vous souhaitez utiliser.
Exemple: 
```php
$db = new DbManagerCRUD();
echo $db->showCategories();

```

N'oubliez pas de stage, de commit et de push votre travail à la fin de chaque session. 

```
git status
git add .
git commit -m"Je resume ce que j'ai fait"
git push origin main

```

Et au debut de chaque session, n'oubliez pas de pull pour être à jour avec le remote.

```
git pull origin main

```

Ressources
---------

 * [Documentation](https://symfony.com/doc/current/mailer.html)
 * [Documentation](https://getcomposer.org/download/)
