<?php

// -----------------------------------------------------------------------------
// Point d'entrée unique (front controller).
// .htaccess redirige toutes les URLs "propres" vers ce fichier, en les
// transformant en paramètres GET (ex: /projets/1/titre.html => ?projects=show&id=1).
// -----------------------------------------------------------------------------

// 1. Configuration, connexion MySQL, helpers
require_once '../core/init.php';

// 2. Routage : exécute le contrôleur qui prépare $title et $content
require_once '../app/routers/index.php';

// 3. Affichage du template qui assemble les partials
require_once '../app/views/templates/default.php';
