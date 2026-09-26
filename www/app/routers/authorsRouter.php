
<?php

include_once '../app/controllers/pagesController.php';

/*
|--------------------------------------------------------------------------
| ROUTEUR - CRÉA'TIFS
|--------------------------------------------------------------------------
| Gère la route d'affichage du détail d'un créa'tif : /creatifs/id.html
|
| @param PDO $connexion
| @return bool true si la route a été trouvée et traitée, false sinon.
*/

function routeAuthors(PDO $connexion): bool
{
    // Le .htaccess transforme /creatifs/id.html en index.php?projects=author&id=...
    // (la valeur 'author' du paramètre "projects" est donc réservée à cette route).
    if (isset($_GET['projects']) && $_GET['projects'] === 'author') {

        // On délègue l'affichage au contrôleur, en castant l'id en entier
        // (sécurité minimale : on force un type numérique avant la requête SQL).
        \App\Controllers\PagesController\authorAction(
            $connexion,
            (int) $_GET['id']
        );

        // La route a été reconnue et traitée : on le signale au dispatcher.
        return true;
    }

    // Cette route ne correspond pas à la requête actuelle.
    return false;
}
