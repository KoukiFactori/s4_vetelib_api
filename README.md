# Vetelib API

## About

Vetelib-API est l'application API Platform exposant l'ensemble des informations au travers d'une API consommable depuis n'importe quel terminal compatible.

## Dépendances

L'application est basée sur API Platform 3.1 et Symfony 6.2.

Nécessite PHP >= 8.1

## Auteurs

- Simon Ledoux      (ledo0024)
- Tom Raineri       (rain0005)
- Nicolas Mossman   (moss0006)
- Antoine Marechal  (mare0055)

## Objectif du projet

L'objectif du projet est de permettre (principalement) à une application front-end de consommer les données pour les exposer à l'utilisateur.

## Installation du projet

Le projet peut simplement être cloné puis lancer en prenant soin d'installer les dépendances à l'aide de `composer`
Avant de lancer `composer install`, il est nécessaire de configurer la variable DATABASE_URL dans le `.env` (ou `.env.local`)

```bash
git clone https://iut-info.univ-reims.fr/gitlab/rain0005/sae4-01-api
cd sae4-01-api
composer install
```

## Scripts

### Lancer le serveur de test

- `composer start` : Pour démarrer le serveur web

### Style de codage

Le code peut être contrôlé avec :

`composer test:cs`

Il peut être reformaté automatiquement avec :

`composer fix:cs`

### Création de données fictives

- On peut créer une nouvelle base de données en supprimant l'ancienne, et en y créant des données factices en utilisant la commande : `composer db`

### Lancement des tests

Via Codeception:
`composer test:cs`

### Comment contribuer ?

Pour contribuer sur ce dépot, il est nécessaire au préalable de récupérer la dernière version de la branche principale.
Toute nouvelle branche doit obligatoirement être basé sur le dernier commit, ainsi qu'au moment du merge request.

Le reset a été choisi pour s'assurer qu'aucun commit en trop ou conflit ne soit possible.

```bash
git checkout main
git fetch
git reset --hard origin/main
```

Tout nouveau commit doit se faire sur une branche séparée
Il est recommandé de rebaser ou de merger les changements de main le plus souvent possible, afin d'éviter le plus possible les conflits.

Lors du rebase/merge, en cas d'installation d'une nouvelle dépendance sur la branche, il est possible que git passe en mode conflit.
Pour corriger ce problème et éviter les désynchronisations du composer.lock, il est recommandé de rebaser jusqu'au conflit, de retirer les modifications apportées par la branche aux fichiers de composer, puis de réinstaller la dépendance. De cette façon, composer se base sur le lockfile de la branche main et ne devrait pas poser de soucis par la suite. Les nouveaux fichiers peuvent ensuite être ajouter à git et le rebase peut se continuer sans erreur (normalement)
