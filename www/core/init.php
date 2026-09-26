
<?php

// -----------------------------------------------------------------------------
// Fichier d'amorçage (bootstrap) de l'application.
// Il regroupe, dans le bon ordre, tout ce dont l'application a besoin
// avant de pouvoir router une requête et afficher une vue.
// Ce fichier est inclus en tout premier par public/index.php.
// -----------------------------------------------------------------------------

// Démarre la session PHP afin de pouvoir utiliser $_SESSION dans l'application.
session_start();

// Charge la configuration de la base de données et les variables globales.
require_once '../app/config/params.php';

// Charge les constantes générales utilisées par l'application.
require_once '../core/constantes.php';

// Établit la connexion PDO avec MySQL.
require_once '../core/connexion.php';

// Charge les fonctions utilitaires utilisées par les contrôleurs et les vues.
require_once '../core/helpers.php';
