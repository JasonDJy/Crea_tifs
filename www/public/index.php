
<?php

// -----------------------------------------------------------------------------
// Point d'entrée unique de l'application (front controller).
// C'est le fichier .htaccess (mod_rewrite) qui redirige toutes les URLs
// "propres" (ex: /projets/1/mon-titre.html) vers ce fichier, en les
// transformant en paramètres GET (ex: index.php?projects=show&id=1).
// Toutes les requêtes qui arrivent dans /public passent par ce fichier.
// -----------------------------------------------------------------------------

// Étape 1 : Charge la configuration, la connexion MySQL et les helpers.
require_once '../core/init.php';

// Étape 2 : Analyse l'URL demandée (via $_GET) et exécute le contrôleur
// correspondant, qui va préparer les données nécessaires à l'affichage
// (ex: $projects, $project, $auteurs...) sous forme de variables globales.
require_once '../app/routers/index.php';

// Étape 3 : Affiche le template général, qui assemble les partials
// (head, nav, header, main, footer...) et utilise les variables
// préparées à l'étape précédente pour construire la page HTML finale.
require_once '../app/views/templates/default.php';
