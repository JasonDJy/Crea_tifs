<?php

// -----------------------------------------------------------------------------
// Dispatcher principal : délègue à un routeur spécialisé par ressource.
// -----------------------------------------------------------------------------

// ROUTES CREATIFS
// URL: ?creatifs=xxx
// ROUTER: creatifs
if (isset($_GET['creatifs'])):
    include_once '../app/routers/creatifs.php';

// ROUTES TAGS
// URL: ?tags=xxx
// ROUTER: tags
elseif (isset($_GET['tags'])):
    include_once '../app/routers/tags.php';

// ROUTES PROJECTS
// URL: ?projects=xxx
// ROUTER: projects
elseif (isset($_GET['projects'])):
    include_once '../app/routers/projects.php';

else:
    // ROUTE PAR DÉFAUT: LISTE DES PROJETS
    // PATTERN: /
    // URL: ?
    // CTRL: projectsController
    // ACTION: index

    include_once '../app/controllers/projectsController.php';
    \App\Controllers\ProjectsController\indexAction($connexion);
endif;
