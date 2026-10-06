<?php

namespace App\Controllers\TagsController;

use \PDO;
use \Core\Helpers;
use \App\Models\TagsModel;
use \App\Models\ProjectsModel;
use \App\Controllers\PagesController;

include_once '../app/models/tagsModel.php';
include_once '../app/models/projectsModel.php';
include_once '../app/controllers/creatifsController.php';
include_once '../app/controllers/pagesController.php';

/**
 * Zone dynamique du template : liste des tags (sidebar).
 * Appelée par le partial _aside.php. Affiche directement le partial.
 */
function indexAsideAction(PDO $connexion): void
{
    $tags = TagsModel\findAll($connexion);

    include '../app/views/templates/partials/_tags.php';
}

/**
 * Liste paginée des projets d'un tag.
 * Réutilise la vue projects/index.php (même affichage que l'accueil).
 */
function showAction(PDO $connexion, int $id): void
{
    global $content, $title;

    $tag = TagsModel\findOneById($connexion, $id);

    if ($tag === null) {
        PagesController\notFoundAction("Le tag demandé n'existe pas.");
        return;
    }

    $page = Helpers\currentPage();
    $projects = ProjectsModel\findAllByTagId($connexion, $id, $page);
    $totalPages = (int) ceil(ProjectsModel\countAllByTagId($connexion, $id) / PROJECTS_PER_PAGE);

    // Variables lues par la vue projects/index.php et par _pagination.php.
    $heading = 'Tag : ' . $tag['nom'];
    $paginationUrl = 'tags/' . $tag['id'] . '/' . Helpers\slugify($tag['nom']) . '.html';

    $title = $heading;
    ob_start();
    include '../app/views/projects/index.php';
    $content = ob_get_clean();
}
