
<?php

include_once '../app/controllers/pagesController.php';

/*
|--------------------------------------------------------------------------
| ROUTEUR - PROJETS
|--------------------------------------------------------------------------
| Gère toutes les routes liées aux projets :
| accueil (liste + pagination), détail, ajout, modification, suppression.
|
| @param PDO $connexion
| @return bool true si une route a été trouvée et traitée, false sinon.
*/

function routeProjects(PDO $connexion): bool
{
    // Pas de paramètre "projects" => on considère que c'est l'accueil (route "/").
    if (!isset($_GET['projects'])) {

        \App\Controllers\PagesController\dashboardAction($connexion);

        return true;
    }

    // La valeur de $_GET['projects'] (fixée par le .htaccess selon l'URL demandée)
    // détermine quelle action du contrôleur doit être appelée.
    switch ($_GET['projects']) {

        // ACCUEIL : /projects
        case 'index':
            \App\Controllers\PagesController\dashboardAction($connexion);
            return true;

        // DETAIL D'UN PROJET : /projets/id/slug.html
        case 'show':
            \App\Controllers\PagesController\projectAction(
                $connexion,
                (int) $_GET['id']
            );
            return true;

        // SUPPRESSION D'UN PROJET : /projets/delete/id/slug.html
        case 'delete':
            \App\Controllers\PagesController\supprimerProjetAction(
                $connexion,
                (int) $_GET['id']
            );
            return true;

        // AJOUT - FORMULAIRE : /projects/add/form.html
        // AJOUT - INSERTION  : /projects/add/insert.html
        // Les deux routes pointent vers la même action : elle affiche
        // le formulaire en GET et traite l'insertion en POST.
        case 'add-form':
        case 'add-insert':
            \App\Controllers\PagesController\nouveauProjetAction($connexion);
            return true;

        // MODIFICATION - FORMULAIRE : /projects/id/slug/edit/form.html
        // MODIFICATION - UPDATE     : /projects/id/slug/edit/update.html
        case 'edit-form':
        case 'edit-update':
            \App\Controllers\PagesController\modifierProjetAction(
                $connexion,
                (int) $_GET['id']
            );
            return true;
    }

    // Aucun des cas ci-dessus ne correspond : la route est inconnue.
    return false;
}
