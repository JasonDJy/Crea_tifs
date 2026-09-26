
<?php

// -----------------------------------------------------------------------------
// Construit l'URL de base du dossier public.
// Cette constante est utilisée notamment lors des redirections
// (ex: après l'ajout/la modification/la suppression d'un projet)
// et dans les vues pour générer des liens absolus vers les assets (CSS/JS).
// -----------------------------------------------------------------------------
define(
    'PUBLIC_BASE_URL',
    // Concatène : schéma (http/https) + hôte + dossier courant (public/) + slash final.
    $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/'
);
