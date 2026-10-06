<?php

namespace App\Controllers\ProjectsController;

use \PDO;
use \Core\Helpers;
use \App\Models\ProjectsModel;
use \App\Models\CreatifsModel;
use \App\Models\TagsModel;
use \App\Controllers\PagesController;

include_once '../app/models/projectsModel.php';
include_once '../app/models/creatifsModel.php';
include_once '../app/models/tagsModel.php';
include_once '../app/controllers/pagesController.php';
// Nécessaires car la sidebar (_aside.php) appelle leurs actions "indexAside".
include_once '../app/controllers/creatifsController.php';
include_once '../app/controllers/tagsController.php';

/**
 * Liste paginée des projets (route par défaut).
 */
function indexAction(PDO $connexion): void
{
    global $content, $title;

    $page = Helpers\currentPage();
    $projects = ProjectsModel\findAll($connexion, $page);
    $totalPages = (int) ceil(ProjectsModel\countAll($connexion) / PROJECTS_PER_PAGE);

    // Variables lues par la vue et par _pagination.php.
    $heading = null;        // pas de titre de section sur l'accueil
    $paginationUrl = '';    // les liens de pagination restent sur l'accueil

    $title = "Les projets";
    ob_start();
    include '../app/views/projects/index.php';
    $content = ob_get_clean();
}

/**
 * Détail d'un projet : image, texte, tags et fiche du créa'tif.
 */
function showAction(PDO $connexion, int $id): void
{
    global $content, $title;

    $project = ProjectsModel\findOneById($connexion, $id);

    if ($project === null) {
        PagesController\notFoundAction("Le projet demandé n'existe pas.");
        return;
    }

    $tags = TagsModel\findAllByProjectId($connexion, $id);

    $title = $project['titre'];
    ob_start();
    include '../app/views/projects/show.php';
    $content = ob_get_clean();
}

/**
 * Affiche le formulaire d'ajout d'un projet.
 *
 * @param array $errors Messages d'erreur (si le formulaire est ré-affiché après un échec)
 * @param array $old    Valeurs déjà saisies ($_POST) pour pré-remplir le formulaire
 */
function addFormAction(PDO $connexion, array $errors = [], array $old = []): void
{
    global $content, $title;

    $creatifs = CreatifsModel\findAll($connexion);
    $tags = TagsModel\findAll($connexion);

    $title = "Ajouter un projet";
    ob_start();
    include '../app/views/projects/addForm.php';
    $content = ob_get_clean();
}

/**
 * Traite le formulaire d'ajout : validation, upload de l'image, INSERT,
 * association des tags, puis redirection vers l'accueil.
 */
function addInsertAction(PDO $connexion): void
{
    // Cette route n'a de sens qu'en POST : sinon retour au formulaire.
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . PUBLIC_BASE_URL . 'projects/add/form.html');
        exit;
    }

    $data = getFormData();
    $errors = validateFormData($connexion, $data);

    // Image facultative : si un fichier est envoyé mais refusé, on prévient.
    $data['image'] = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $data['image'] = Helpers\uploadImage($_FILES['image']);

        if ($data['image'] === null) {
            $errors[] = "L'image est invalide (formats acceptés : jpg, png, gif, webp).";
        }
    }

    // Échec : on ré-affiche le formulaire avec les erreurs et les valeurs saisies.
    if (!empty($errors)) {
        addFormAction($connexion, $errors, $_POST);
        return;
    }

    $projectId = ProjectsModel\insertOne($connexion, $data);
    syncTags($connexion, $projectId);

    header('Location: ' . PUBLIC_BASE_URL);
    exit;
}

/**
 * Affiche le formulaire de modification, pré-rempli avec le projet.
 *
 * @param array $errors Messages d'erreur (ré-affichage après un échec)
 * @param array $old    Valeurs déjà saisies ($_POST) qui remplacent celles de la base
 */
function editFormAction(PDO $connexion, int $id, array $errors = [], array $old = []): void
{
    global $content, $title;

    $project = ProjectsModel\findOneById($connexion, $id);

    if ($project === null) {
        PagesController\notFoundAction("Le projet à modifier n'existe pas.");
        return;
    }

    $creatifs = CreatifsModel\findAll($connexion);
    $tags = TagsModel\findAll($connexion);
    $projectTags = TagsModel\findAllByProjectId($connexion, $id);

    $title = "Modifier le projet";
    ob_start();
    include '../app/views/projects/editForm.php';
    $content = ob_get_clean();
}

/**
 * Traite le formulaire de modification : validation, nouvelle image
 * éventuelle, UPDATE, mise à jour des tags, puis redirection vers l'accueil.
 */
function editUpdateAction(PDO $connexion, int $id): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ' . PUBLIC_BASE_URL . 'projects/' . $id . '/projet/edit/form.html');
        exit;
    }

    $project = ProjectsModel\findOneById($connexion, $id);

    if ($project === null) {
        PagesController\notFoundAction("Le projet à modifier n'existe pas.");
        return;
    }

    $data = getFormData();
    $errors = validateFormData($connexion, $data);

    // Par défaut on garde l'image actuelle ; un nouveau fichier la remplace.
    $data['image'] = $project['image'];
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $newImage = Helpers\uploadImage($_FILES['image']);

        if ($newImage === null) {
            $errors[] = "L'image est invalide (formats acceptés : jpg, png, gif, webp).";
        } else {
            $data['image'] = $newImage;
        }
    }

    if (!empty($errors)) {
        editFormAction($connexion, $id, $errors, $_POST);
        return;
    }

    ProjectsModel\updateOneById($connexion, $id, $data);

    // On repart d'associations "propres" : suppression puis recréation.
    TagsModel\deleteProjectTags($connexion, $id);
    syncTags($connexion, $id);

    header('Location: ' . PUBLIC_BASE_URL);
    exit;
}

/**
 * Supprime un projet (et ses associations de tags) puis redirige vers l'accueil.
 */
function deleteAction(PDO $connexion, int $id): void
{
    if (ProjectsModel\findOneById($connexion, $id) === null) {
        PagesController\notFoundAction("Le projet à supprimer n'existe pas.");
        return;
    }

    ProjectsModel\deleteOneById($connexion, $id);

    // PUBLIC_BASE_URL (et non un chemin relatif) : cette action est appelée
    // depuis /projets/delete/id/slug.html, un chemin relatif serait faux.
    header('Location: ' . PUBLIC_BASE_URL);
    exit;
}

// -----------------------------------------------------------------------------
// Fonctions internes au contrôleur (partagées par l'ajout et la modification)
// -----------------------------------------------------------------------------

/**
 * Lit les champs texte du formulaire (valeurs par défaut si absents).
 */
function getFormData(): array
{
    return [
        'titre' => trim($_POST['title'] ?? ''),
        'texte' => trim($_POST['text'] ?? ''),
        'creatif' => (int) ($_POST['category_id'] ?? 0),
    ];
}

/**
 * Valide les champs du formulaire et renvoie la liste des erreurs
 * (tableau vide si tout est correct).
 */
function validateFormData(PDO $connexion, array $data): array
{
    $errors = [];

    if ($data['titre'] === '') {
        $errors[] = "Le titre est obligatoire.";
    } elseif (mb_strlen($data['titre']) > 45) {
        // La colonne `titre` est un varchar(45).
        $errors[] = "Le titre ne peut pas dépasser 45 caractères.";
    }

    if ($data['texte'] === '') {
        $errors[] = "La description est obligatoire.";
    }

    if (CreatifsModel\findOneById($connexion, $data['creatif']) === null) {
        $errors[] = "Veuillez sélectionner un créa'tif.";
    }

    return $errors;
}

/**
 * Associe au projet les tags cochés dans le formulaire ($_POST['tags']).
 * Seuls les tags qui existent réellement en base sont pris en compte.
 */
function syncTags(PDO $connexion, int $projectId): void
{
    $existingTagIds = array_column(TagsModel\findAll($connexion), 'id');

    foreach (array_unique((array) ($_POST['tags'] ?? [])) as $tagId) {
        if (in_array((int) $tagId, array_map('intval', $existingTagIds), true)) {
            TagsModel\insertProjectTag($connexion, $projectId, (int) $tagId);
        }
    }
}
