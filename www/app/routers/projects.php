<?php

use \App\Controllers\ProjectsController;

include_once '../app/controllers/projectsController.php';

// La valeur de $_GET['projects'] est fixée par public/.htaccess
// selon l'URL demandée.
switch ($_GET['projects']):
    case 'show':
        // DETAIL D'UN PROJET
        // PATTERN: /projets/id/slug.html
        // URL: ?projects=show&id=x
        // CTRL: projectsController
        // ACTION: show
        ProjectsController\showAction($connexion, (int) ($_GET['id'] ?? 0));
        break;

    case 'delete':
        // SUPPRESSION D'UN PROJET (puis redirection vers l'accueil)
        // PATTERN: /projets/delete/id/slug.html
        // URL: ?projects=delete&id=x
        // CTRL: projectsController
        // ACTION: delete
        ProjectsController\deleteAction($connexion, (int) ($_GET['id'] ?? 0));
        break;

    case 'add-form':
        // AJOUT D'UN PROJET - FORMULAIRE
        // PATTERN: /projects/add/form.html
        // URL: ?projects=add-form
        // CTRL: projectsController
        // ACTION: addForm
        ProjectsController\addFormAction($connexion);
        break;

    case 'add-insert':
        // AJOUT D'UN PROJET - INSERTION (puis redirection vers l'accueil)
        // PATTERN: /projects/add/insert.html
        // URL: ?projects=add-insert
        // CTRL: projectsController
        // ACTION: addInsert
        ProjectsController\addInsertAction($connexion);
        break;

    case 'edit-form':
        // MODIFICATION D'UN PROJET - FORMULAIRE
        // PATTERN: /projects/id/slug/edit/form.html
        // URL: ?projects=edit-form&id=x
        // CTRL: projectsController
        // ACTION: editForm
        ProjectsController\editFormAction($connexion, (int) ($_GET['id'] ?? 0));
        break;

    case 'edit-update':
        // MODIFICATION D'UN PROJET - UPDATE (puis redirection vers l'accueil)
        // PATTERN: /projects/id/slug/edit/update.html
        // URL: ?projects=edit-update&id=x
        // CTRL: projectsController
        // ACTION: editUpdate
        ProjectsController\editUpdateAction($connexion, (int) ($_GET['id'] ?? 0));
        break;

    default:
        // LISTE DES PROJETS (paginée)
        // PATTERN: / ou /projects
        // URL: ?projects=index
        // CTRL: projectsController
        // ACTION: index
        ProjectsController\indexAction($connexion);
        break;
endswitch;
