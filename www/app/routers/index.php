
<?php

// -----------------------------------------------------------------------------
// Dispatcher principal.
// N'analyse plus les routes lui-même : il délègue à des routeurs spécialisés
// (un par ressource), chacun renvoyant true s'il a pris en charge la requête.
// -----------------------------------------------------------------------------

// Charge les fonctions routeAuthors() et routeProjects() définies plus bas.
include_once '../app/routers/authorsRouter.php';
include_once '../app/routers/projectsRouter.php';

// $connexion vient de core/connexion.php (chargé par core/init.php) ;
// on le transmet à chaque routeur pour qu'il puisse interroger la BDD.

// On teste d'abord la route "créa'tif" (valeur dédiée de $_GET['projects']).
// Si elle correspond, le contrôleur a déjà été exécuté : on arrête ici.
if (routeAuthors($connexion)) {
    return;
}

// Sinon, on tente les routes "projets" (accueil, détail, ajout, modif, suppression).
routeProjects($connexion);
