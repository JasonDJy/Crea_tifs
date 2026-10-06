<?php

use \App\Controllers\TagsController;
use \App\Controllers\PagesController;

include_once '../app/controllers/tagsController.php';
include_once '../app/controllers/pagesController.php';

switch ($_GET['tags']):
    case 'show':
        // PROJETS D'UN TAG (paginés via ?page=x)
        // PATTERN: /tags/id/slug.html
        // URL: ?tags=show&id=x
        // CTRL: tagsController
        // ACTION: show
        TagsController\showAction($connexion, (int) ($_GET['id'] ?? 0));
        break;

    default:
        // ROUTE INCONNUE
        // CTRL: pagesController
        // ACTION: notFound
        PagesController\notFoundAction("Cette page n'existe pas.");
        break;
endswitch;
