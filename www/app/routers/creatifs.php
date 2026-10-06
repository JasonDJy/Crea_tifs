<?php

use \App\Controllers\CreatifsController;
use \App\Controllers\PagesController;

include_once '../app/controllers/creatifsController.php';
include_once '../app/controllers/pagesController.php';

switch ($_GET['creatifs']):
    case 'show':
        // DETAIL D'UN CREA'TIF (fiche + liste de ses projets)
        // PATTERN: /creatifs/id.html
        // URL: ?creatifs=show&id=x
        // CTRL: creatifsController
        // ACTION: show
        CreatifsController\showAction($connexion, (int) ($_GET['id'] ?? 0));
        break;

    default:
        // ROUTE INCONNUE
        // CTRL: pagesController
        // ACTION: notFound
        PagesController\notFoundAction("Cette page n'existe pas.");
        break;
endswitch;
