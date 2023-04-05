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

### Création de données fixtives

- On peut créer une nouvelle base de données en supprimant l'ancienne, et en y créant des données factices en utilisant la commande : `composer db`

### Lancement des tests

Via Codeception:
`composer test:cs`
