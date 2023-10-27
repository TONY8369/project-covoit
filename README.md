# Projet COVOIT - Symfony
## Dev_Metal_Jacket
### Installer le projet


Aller dans service de window et démarrer mysql puis rentrer $ mysql -u root -p
et le password correspondant
Enter password: ****

Soit on part de zero :
symfony new my_project_directory --version="6.0.*" --webapp

Soit : 

- Cloner le projet depuis GitLab
  `git clone git@git.alt-tools.tech:bootcamp-4m/covoit_symfony.git`

- Installer les dépendances
  `composer install`
Lancer le serveur:
-php -S localhost:8001 -t public
-symfony server:start

- Configurer le .env 
    `DATABASE_URL="mysql://LOGIN:PASSWORD@127.0.0.1:3306/covoit_symfony?serverVersion=mariadb-10.6.12"`

- Créer une nouvelle branche 
    `git checkout -b <branch name>`
Ensuite 
Pour créer une "migration":
-php bin/console make:migration pour que symfony convertit l'entity en requete sql pour la base de donnée

Puis on Migrer la table vers la base de donnée: 
-php bin/console doctrine:migrations:migrate
-> yes

-(Pour prévenir des bug , supprimer les migrations quand on reprend un projet pour le mettre à jour dans la db)

Pour crée une relation refaire un :
-php bin/console make:entity et relier les deux tables .

Controller:
-php bin/console make:controller avec le nom du controller 

Formulaire:
-php bin/console make:form avec le nom du formulaire pour crée un formulaire ratacher à l'entity
Installer composer require --dev orm-fixtures et composer require fakerphp/faker pour crée des fixtures et simuler vos objets 
cela va crée un dossier dataFixtures et aprés l'avoir compléter : 

-php bin/console doctrine:fixtures:load pour la mettre dans la base de donnée

////////////////

Explication des relations : 
-OneToMany => 1 seul ID peut être lié à plusieurs ID
-ManyToOne => plusieurs ID peuvent être liés que à 1 ID
-ManyToMany => plusieurs ID ne peuvent être liés à plusieurs ID
-OneToOne => 1 ID ne peu être lié que à 1 ID 

/////////

après que tous les merges request sont ok

fait bien un pull

et rebasé vos branche depuis le main

git rebase main VOTRE_BRANCH

// composer update pour réinstaller les dépendances et les metres à jour //

