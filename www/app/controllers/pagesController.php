<?php

namespace App\Controllers\PagesController;

// -----------------------------------------------------------------------------
// Contrôleur "Pages" : contient toutes les actions appelées par les routeurs
// (app/routers/*.php). Chaque fonction ci-dessous correspond à une page :
// elle va chercher les données nécessaires via les modèles, puis les stocke
// dans des variables globales qui seront lues par les vues (app/views/...).
// -----------------------------------------------------------------------------

use \PDO;
use \App\Models\AuteursModel;
use \App\Models\TagsModel;
use \App\Models\ProjectsModel;

// Chargement des modèles utilisés par les actions de ce contrôleur.
include '../app/models/auteursModel.php';
include '../app/models/tagsModel.php';
include '../app/models/projectsModel.php';


/*
|--------------------------------------------------------------------------
| Affiche la page d'accueil
|--------------------------------------------------------------------------
| Affiche les projets avec pagination.
| Peut également filtrer les projets par créa'tif ou par tag.
*/

function dashboardAction(PDO $connexion)
{
    // Ces variables globales seront directement utilisées par le template
    // et les partials (ex: _main.php, _pagination.php, _aside.php).
    global $auteurs, $tags, $projects, $page, $totalPages;

    // Récupère les créa'tifs et les tags pour l'affichage dans la sidebar.
    $auteurs = AuteursModel\getAuthors($connexion);
    $tags = TagsModel\getTags($connexion);

    // Récupère le numéro de page demandé dans l'URL (ex: ?page=2), 1 par défaut.
    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

    // Garde-fou : on ne descend jamais en dessous de la page 1
    // (évite un LIMIT/OFFSET négatif si l'utilisateur bidouille l'URL).
    if ($page < 1) {
        $page = 1;
    }


    /*
    |--------------------------------------------------------------------------
    | Filtre par tag
    |--------------------------------------------------------------------------
    */

    if (isset($_GET['tag'])) {

        // Un tag est sélectionné (lien "?tag=..." cliqué dans la sidebar) :
        // on ne récupère que les projets liés à ce tag, page par page.
        $tagId = (int) $_GET['tag'];

        $projects = TagsModel\getProjectsByTag(
            $connexion,
            $tagId,
            $page
        );

        // Nécessaire pour calculer le nombre de pages de CE filtre précis.
        $totalProjects = TagsModel\countProjectsByTag(
            $connexion,
            $tagId
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Tous les projets
    |--------------------------------------------------------------------------
    */ else {

        // Aucun filtre : comportement par défaut de la page d'accueil,
        // on récupère tous les projets (10 par page).
        $projects = ProjectsModel\getProjects(
            $connexion,
            $page
        );

        $totalProjects = ProjectsModel\countProjects(
            $connexion
        );
    }


    // Calcule le nombre total de pages en fonction du nombre de projets
    // trouvé ci-dessus et de la limite fixe de 10 projets par page.
    $totalPages = ceil($totalProjects / 10);
}



/*
|--------------------------------------------------------------------------
| Affiche le détail d'un projet
|--------------------------------------------------------------------------
*/

function projectAction(PDO $connexion, int $id)
{
    global $project, $tagsProjet, $projetIntrouvable;

    // Recherche le projet demandé (avec les infos du créa'tif associé).
    $project = ProjectsModel\getProjectById(
        $connexion,
        $id
    );

    // Si l'id ne correspond à aucun projet, on prévient la vue
    // (elle affichera un message "Projet introuvable") et on arrête ici.
    if ($project === false) {

        $projetIntrouvable = true;

        return;
    }

    $projetIntrouvable = false;

    // Récupère les tags associés à ce projet, pour les afficher sous la description.
    $tagsProjet = ProjectsModel\getProjectTags(
        $connexion,
        $id
    );
}


/*
|--------------------------------------------------------------------------
| Affiche les informations d'un créa'tif
|--------------------------------------------------------------------------
*/

function authorAction(PDO $connexion, int $id)
{
    global $auteur, $projetsAuteur, $auteurIntrouvable;

    // Recherche le créa'tif demandé par son id.
    $auteur = AuteursModel\getAuthorById(
        $connexion,
        $id
    );

    // Créa'tif inexistant : on le signale à la vue et on s'arrête.
    if ($auteur === false) {

        $auteurIntrouvable = true;

        return;
    }

    $auteurIntrouvable = false;

    // Récupère tous les projets réalisés par ce créa'tif, pour les lister sur sa page.
    $projetsAuteur = AuteursModel\getProjectsByAuthor(
        $connexion,
        $id
    );
}


/*
|--------------------------------------------------------------------------
| Affiche les projets d'un tag
|--------------------------------------------------------------------------
| Remarque : cette action n'est actuellement appelée par aucun routeur
| (le filtrage par tag de la page d'accueil est géré directement dans
| dashboardAction() via $_GET['tag']). Elle est conservée ici au cas où
| une route dédiée /tags/id.html serait ajoutée plus tard.
*/

function tagAction(PDO $connexion, int $id)
{
    global $tag, $projetsTag, $tagIntrouvable;

    $tag = null;

    // Récupère tous les tags puis cherche celui dont l'id correspond,
    // faute d'une fonction getTagById() dédiée dans le modèle.
    $tags = TagsModel\getTags($connexion);

    foreach ($tags as $element) {

        if ((int) $element['id'] === $id) {

            $tag = $element;

            break;
        }
    }

    // Tag inexistant : on le signale à la vue et on s'arrête.
    if ($tag === null) {

        $tagIntrouvable = true;

        return;
    }

    $tagIntrouvable = false;

    // Récupère les projets associés à ce tag.
    $projetsTag = TagsModel\getProjectsByTag(
        $connexion,
        $id
    );
}


/*
|--------------------------------------------------------------------------
| Ajoute un nouveau projet
|--------------------------------------------------------------------------
*/

function nouveauProjetAction(PDO $connexion)
{
    global $auteurs, $tags, $nouveauProjet;

    // Nécessaires pour remplir les listes déroulantes/cases à cocher du formulaire.
    $auteurs = AuteursModel\getAuthors($connexion);
    $tags = TagsModel\getTags($connexion);

    // Indique à _main.php qu'il faut afficher le formulaire d'ajout (nouveauProjet.php).
    $nouveauProjet = true;

    // Cette même action gère 2 routes : affichage du formulaire (GET)
    // et traitement de l'envoi (POST). On ne traite l'insertion qu'en POST.
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Récupère les champs du formulaire (valeur par défaut si absents).
        $titre = $_POST['title'] ?? '';
        $texte = $_POST['text'] ?? '';
        $creatif = (int) ($_POST['category_id'] ?? 0);

        // Validation minimale : les champs obligatoires doivent être renseignés.
        // Si ce n'est pas le cas, on arrête là : le formulaire sera réaffiché.
        if ($titre === '' || $texte === '' || $creatif === 0) {
            return;
        }

        $image = null;

        // Gestion de l'upload de l'image (optionnelle).
        if (
            isset($_FILES['image']) &&
            $_FILES['image']['error'] === 0
        ) {

            // basename() évite tout chemin malicieux dans le nom de fichier envoyé.
            $nomImage = basename($_FILES['image']['name']);

            $destination = '../public/images/' . $nomImage;

            // Déplace le fichier temporaire uploadé vers le dossier public/images.
            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                $destination
            );

            $image = $nomImage;
        }

        // Insère le nouveau projet en base et récupère son id généré.
        $projectId = ProjectsModel\createProject(
            $connexion,
            $titre,
            $texte,
            $creatif,
            $image
        );

        // Si des tags ont été cochés, on crée les associations projet <-> tag.
        if (isset($_POST['tags']) && is_array($_POST['tags'])) {

            foreach ($_POST['tags'] as $tagId) {

                ProjectsModel\addProjectTag(
                    $connexion,
                    $projectId,
                    (int) $tagId
                );
            }
        }

        // Redirection vers la page d'accueil.
        // On utilise la constante PUBLIC_BASE_URL (définie dans core/constantes.php)
        // plutôt qu'un chemin codé en dur, pour que ça reste portable.
        header('Location: ' . PUBLIC_BASE_URL);

        exit;
    }
}


/*
|--------------------------------------------------------------------------
| Modifie un projet
|--------------------------------------------------------------------------
*/

function modifierProjetAction(PDO $connexion, int $id)
{
    global $auteurs, $tags, $projetAModifier, $tagsProjet;

    // Nécessaires pour remplir les listes déroulantes/cases à cocher du formulaire.
    $auteurs = AuteursModel\getAuthors($connexion);
    $tags = TagsModel\getTags($connexion);

    // Charge le projet à modifier, pour pré-remplir le formulaire
    // (que la requête soit en GET pour l'afficher, ou en POST pour le traiter).
    $projetAModifier = ProjectsModel\getProjectById(
        $connexion,
        $id
    );

    // Tags déjà associés au projet, pour cocher les bonnes cases dans le formulaire.
    $tagsProjet = ProjectsModel\getProjectTags(
        $connexion,
        $id
    );

    // Cette même action gère 2 routes : affichage du formulaire pré-rempli (GET)
    // et traitement de la modification (POST).
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $titre = $_POST['title'];
        $texte = $_POST['text'];
        $creatif = (int) $_POST['category_id'];

        // Par défaut, on garde l'image existante si aucune nouvelle n'est envoyée.
        $image = $projetAModifier['image'];

        // Si un nouveau fichier a été envoyé, il remplace l'image actuelle.
        if (
            isset($_FILES['image']) &&
            $_FILES['image']['error'] === 0
        ) {

            $nomImage = basename($_FILES['image']['name']);

            $destination = '../public/images/' . $nomImage;

            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                $destination
            );

            $image = $nomImage;
        }

        // Met à jour le projet en base avec les nouvelles valeurs.
        ProjectsModel\updateProject(
            $connexion,
            $id,
            $titre,
            $texte,
            $creatif,
            $image
        );

        // On repart d'une association de tags "propre" : on supprime les
        // anciennes associations avant de recréer celles cochées dans le formulaire.
        ProjectsModel\deleteProjectTags(
            $connexion,
            $id
        );

        if (isset($_POST['tags']) && is_array($_POST['tags'])) {

            foreach ($_POST['tags'] as $tagId) {

                ProjectsModel\addProjectTag(
                    $connexion,
                    $id,
                    (int) $tagId
                );
            }
        }

        // Redirection vers la page d'accueil (même logique que l'ajout).
        header('Location: ' . PUBLIC_BASE_URL);

        exit;
    }
}


/*
|--------------------------------------------------------------------------
| Supprime un projet
|--------------------------------------------------------------------------
*/

function supprimerProjetAction(PDO $connexion, int $id)
{
    // Supprime le projet (et ses associations de tags, gérées dans le modèle).
    ProjectsModel\deleteProject(
        $connexion,
        $id
    );

    // ATTENTION : avant, ce header pointait vers 'index.php' en relatif.
    // Comme cette action est appelée depuis /projets/delete/id/slug.html,
    // le navigateur résolvait ça en /projets/delete/index.php (route inexistante).
    // On utilise donc PUBLIC_BASE_URL pour repartir depuis la racine du site.
    header('Location: ' . PUBLIC_BASE_URL);

    exit;
}
