# PHP - P6 Openclassrooms - Développez de A à Z le site communautaire SnowTricks

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/c9f536d8471d4dffba18f0715226d090)](https://app.codacy.com/gh/baeteromain/SnowTricks?utm_source=github.com&utm_medium=referral&utm_content=baeteromain/SnowTricks&utm_campaign=Badge_Grade_Settings)
[![Codacy Badge](https://app.codacy.com/project/badge/Grade/e8a88cbb47e4469da0f42b4a44f07394)](https://www.codacy.com/gh/baeteromain/SnowTricks/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=baeteromain/SnowTricks&amp;utm_campaign=Badge_Grade)

## Prérequis / Environnement de développement

```
PHP 7.4
NodeJS 14.17.~
Npm 6.14.~
Composer 2.1.3
```


## Installation du projet :building_construction:
Telechargez directement le projet ou effectuez un ```git clone``` via la commande suite :

```https://github.com/baeteromain/SnowTricks.git```

En suivant, effectuez un ```composer install``` à la racine du projet permettant d'installer les dépendances utilisées dans ce projet.
Vous pouvez maintenant effectuez un ```npm install``` puis un ```npm run build``` ( ou ```yarn build``` si vous n'utilisez pas npm)

## CSS :lipstick:

Le thème graphique du blog à été entièrement réalisé via le framework **Bootstrap 5**
( https://getbootstrap.com/docs/5.0/getting-started/introduction/ )

## Base de données :nerd_face:
### Configuration
Modifiez le fichier ```.env``` situé à la racine du projet avec vos informations spécifiques à votre base de données, voir l'exemple ci-dessous :

```
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
```
### Installation de la base de données

Si vous avez le CLI symfony vous pouvez effectuer les commandes suivantes :

Création de la base de donnée via la commande suivante :

```symfony console doctrine:database:create```

Lancement de la migration via la commande suivante :

```symfony console doctrine:migrations:migrate```

Ajout des fixtures en base de données permettant d'avoir les premières données du blog :

```symfony console doctrine:fixtures:load```

*Note : Si vous n'avez pas le client symfony, remplacé ```symfony console``` par ```php bin/console``` ( ex : ```php bin/console doctrine:database:create```)*

## Serveur mail :email:

Toujours dans le fichier .env à la racine du projet, décommenté la ligne corcernant symfony/mailer et ajouter les informations relatifs à votre serveur mail au niveau du MAILER_DNS.

```
###> symfony/mailer ###
MAILER_DSN=smtp://localhost
###< symfony/mailer ###
```

## Utilisateurs

Suite à l'implementation des fixtures, le blog contient déja des articles ainsi que des utilsateurs aléatoir excepté le compte administrateur. Vous trouverez ci-dessous les identifiants de ce compte :

* Nom d'utilisateur : **admin**
* Mot de passe : **admin**

## Utilisation du blog

Il ne vous reste plus qu'a lancer la commande ```symfony serve``` *(ou ```php bin/console server:run```, si vous n'avez pas le CLI symfony)*

Vous pouvez accéder au blog via l'adresse https://127.0.0.1:8000/

## Contexte

Jimmy Sweat est un entrepreneur ambitieux passionné de snowboard. Son objectif est la création d'un site collaboratif pour faire connaître ce sport auprès du grand public et aider à l'apprentissage des figures (tricks).

Il souhaite capitaliser sur du contenu apporté par les internautes afin de développer un contenu riche et suscitant l’intérêt des utilisateurs du site. Par la suite, Jimmy souhaite développer un business de mise en relation avec les marques de snowboard grâce au trafic que le contenu aura généré.

Pour ce projet, nous allons nous concentrer sur la création technique du site pour Jimmy.

## Description du besoin

Vous êtes chargé de développer le site répondant aux besoins de Jimmy. Vous devez ainsi implémenter les fonctionnalités suivantes :

* un annuaire des figures de snowboard. Vous pouvez vous inspirer de la liste des figures sur Wikipédia. Contentez-vous d'intégrer 10 figures, le reste sera saisi par les internautes ;
* la gestion des figures (création, modification, consultation) ;
* un espace de discussion commun à toutes les figures.

Pour implémenter ces fonctionnalités, vous devez créer les pages suivantes :

* la page d’accueil où figurera la liste des figures ;
* la page de création d'une nouvelle figure ;
* la page de modification d'une figure ;
* la page de présentation d’une figure (contenant l’espace de discussion commun autour d’une figure).

L’ensemble des spécifications détaillées pour les pages à développer est accessible ici : [Spécifications détaillées](https://s3-eu-west-1.amazonaws.com/course.oc-static.com/projects/DAPHPSF_P8/DAPHP_P6_spe%CC%81cifications.zip).


## Nota Bene

Il faut que les URL de page permettent une compréhension rapide de ce que la page représente et que le référencement naturel soit facilité.

L’utilisation de bundles tiers est interdite sauf pour les données initiales. Vous utiliserez les compétences acquises jusqu’ici ainsi que la documentation officielle afin de remplir les objectifs donnés.

Le design du site web est laissé complètement libre, attention cependant à respecter [les wireframes fournis](https://s3-eu-west-1.amazonaws.com/static.oc-static.com/prod/courses/files/Parcours-DA-PHP/Projet-6_Wireframes.pdf) pour le gabarit de vos pages. Néanmoins, il faudra que le site soit consultable aussi bien sur un ordinateur que sur mobile (téléphone mobile, tablette, phablette…).

En premier lieu, il vous faudra écrire l’ensemble des issues/tickets afin de découper votre travail méthodiquement et de vous assurer que l’ensemble du besoin client soit bien compris avec votre mentor. Les tickets/issues seront écrits dans un repository GitHub que vous aurez créé au préalable.

L’ensemble des figures de snowboard doivent être présentes à l’initialisation de l’application web. Vous utiliserez un bundle externe pour charger ces données. 