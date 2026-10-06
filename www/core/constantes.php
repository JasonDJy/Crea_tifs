<?php

// URL de base du dossier public (ex: http://localhost/creatifs/www/public/).
// Utilisée pour les redirections et dans <base href> (voir _head.php).
define(
    'PUBLIC_BASE_URL',
    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/'
);

// Nombre de projets affichés par page (consigne de l'énoncé).
define('PROJECTS_PER_PAGE', 10);

// Longueur maximale (en caractères) de l'aperçu d'un texte dans les listes.
define('PREVIEW_LENGTH', 100);

// Dossier physique où sont enregistrées les images envoyées par formulaire.
define('IMAGES_DIR', dirname(__DIR__) . '/public/images/');

// Extensions d'images acceptées à l'upload.
define('IMAGES_ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
