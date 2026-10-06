<?php

namespace App\Controllers\CreatifsController;

use \PDO;
use \App\Models\CreatifsModel;
use \App\Models\ProjectsModel;
use \App\Controllers\PagesController;

include_once '../app/models/creatifsModel.php';
include_once '../app/models/projectsModel.php';
include_once '../app/controllers/pagesController.php';

/**
 * Zone dynamique du template : liste des créa'tifs (sidebar).
 * Appelée par le partial _aside.php. Affiche directement le partial.
 */
function indexAsideAction(PDO $connexion): void
{
    $creatifs = CreatifsModel\findAll($connexion);

    include '../app/views/templates/partials/_creatifs.php';
}

/**
 * Détail d'un créa'tif : sa fiche et la liste de ses projets.
 */
function showAction(PDO $connexion, int $id): void
{
    global $content, $title;

    $creatif = CreatifsModel\findOneById($connexion, $id);

    if ($creatif === null) {
        PagesController\notFoundAction("Le créa'tif demandé n'existe pas.");
        return;
    }

    $projects = ProjectsModel\findAllByCreatifId($connexion, $id);

    $title = $creatif['pseudo'];
    ob_start();
    include '../app/views/creatifs/show.php';
    $content = ob_get_clean();
}
