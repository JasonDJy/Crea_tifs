<?php

namespace App\Models\ProjectsModel;

// -----------------------------------------------------------------------------
// Modèle "Projets" : toutes les requêtes SQL liées à la table `projets`
// (et à la table de liaison `projets_has_tags`) sont centralisées ici.
// Les contrôleurs ne parlent jamais directement à la base de données :
// ils passent systématiquement par ces fonctions.
// -----------------------------------------------------------------------------

use \PDO;


/*
|--------------------------------------------------------------------------
| Récupère les projets
|--------------------------------------------------------------------------
| Récupère 10 projets par page pour l'affichage de l'accueil.
*/

function getProjects(PDO $connexion, int $page = 1)
{
    // 10 projets par page (imposé par les consignes) ; l'offset détermine
    // à partir de quelle ligne on commence à lire selon la page demandée.
    $limit = 10;
    $offset = ($page - 1) * $limit;

    // INNER JOIN sur creatifs pour récupérer le pseudo de l'auteur
    // en une seule requête (évite une requête supplémentaire par projet).
    // $limit/$offset sont injectés directement dans la chaîne (et non liés en
    // paramètre) car PDO ne permet pas de binder LIMIT/OFFSET nativement ;
    // ils restent sûrs ici car typés en int dès la signature de la fonction.
    $sql = "SELECT projets.*, creatifs.pseudo
            FROM projets
            INNER JOIN creatifs
                ON projets.creatif = creatifs.id
            ORDER BY projets.id ASC
            LIMIT $limit OFFSET $offset";

    $stmt = $connexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/*
|--------------------------------------------------------------------------
| Compte le nombre total de projets
|--------------------------------------------------------------------------
| Permet de calculer le nombre de pages nécessaires.
*/

function countProjects(PDO $connexion)
{
    // Compte tous les projets, sans pagination, pour connaître le total
    // et pouvoir en déduire le nombre de pages dans le contrôleur.
    $sql = "SELECT COUNT(*)
            FROM projets";

    $stmt = $connexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchColumn();
}


/*
|--------------------------------------------------------------------------
| Récupère un projet par son identifiant
|--------------------------------------------------------------------------
| Récupère également les informations du créa'tif associé.
*/

function getProjectById(PDO $connexion, int $id)
{
    // Les colonnes du créa'tif sont renommées (AS creatif_image, AS creatif_bio)
    // pour ne pas entrer en conflit avec les colonnes de la table projets
    // et pour être facilement exploitables dans la vue de détail.
    $sql = "SELECT projets.*,
                   creatifs.pseudo,
                   creatifs.image AS creatif_image,
                   creatifs.bio AS creatif_bio
            FROM projets
            INNER JOIN creatifs
                ON projets.creatif = creatifs.id
            WHERE projets.id = :id";

    $stmt = $connexion->prepare($sql);

    // Requête préparée : la valeur de $id est liée en paramètre, pas concaténée
    // dans le SQL, ce qui protège contre les injections SQL.
    $stmt->execute([
        'id' => $id
    ]);

    // fetch() (et non fetchAll()) car on attend au maximum une seule ligne.
    // Renvoie `false` si aucun projet ne correspond à cet id.
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


/*
|--------------------------------------------------------------------------
| Crée un nouveau projet
|--------------------------------------------------------------------------
| Insère un projet dans la base de données
| et retourne son identifiant.
*/

function createProject(
    PDO $connexion,
    string $titre,
    string $texte,
    int $creatif,
    ?string $image
) {
    // NOW() : la date de création est générée côté MySQL, pas en PHP.
    $sql = "INSERT INTO projets
            (titre, texte, dateCreation, image, creatif)
            VALUES
            (:titre, :texte, NOW(), :image, :creatif)";

    $stmt = $connexion->prepare($sql);

    $stmt->execute([
        'titre' => $titre,
        'texte' => $texte,
        'image' => $image,
        'creatif' => $creatif
    ]);

    // lastInsertId() renvoie l'id auto-incrémenté généré pour ce nouveau projet,
    // utile ensuite pour lui associer ses tags.
    return $connexion->lastInsertId();
}


/*
|--------------------------------------------------------------------------
| Ajoute un tag à un projet
|--------------------------------------------------------------------------
| Crée une association entre un projet et un tag.
*/

function addProjectTag(
    PDO $connexion,
    int $projectId,
    int $tagId
) {
    $sql = "INSERT INTO projets_has_tags
            (projet, tag)
            VALUES
            (:projet, :tag)";

    $stmt = $connexion->prepare($sql);

    $stmt->execute([
        'projet' => $projectId,
        'tag' => $tagId
    ]);
}


/*
|--------------------------------------------------------------------------
| Modifie un projet
|--------------------------------------------------------------------------
| Met à jour le titre, le texte, le créa'tif et l'image.
*/

function updateProject(
    PDO $connexion,
    int $id,
    string $titre,
    string $texte,
    int $creatif,
    ?string $image
) {
    $sql = "UPDATE projets
            SET titre = :titre,
                texte = :texte,
                creatif = :creatif,
                image = :image
            WHERE id = :id";

    $stmt = $connexion->prepare($sql);

    $stmt->execute([
        'id' => $id,
        'titre' => $titre,
        'texte' => $texte,
        'creatif' => $creatif,
        'image' => $image
    ]);
}


/*
|--------------------------------------------------------------------------
| Supprime les tags d'un projet
|--------------------------------------------------------------------------
| Supprime toutes les associations entre un projet et ses tags.
| Utilisé avant d'ajouter les nouveaux tags lors d'une modification.
*/

function deleteProjectTags(
    PDO $connexion,
    int $projectId
) {
    // Ne supprime que les lignes de la table de liaison (les tags eux-mêmes
    // et le projet restent intacts) — sert de "reset" avant une modification.
    $sql = "DELETE FROM projets_has_tags
            WHERE projet = :projet";

    $stmt = $connexion->prepare($sql);

    $stmt->execute([
        'projet' => $projectId
    ]);
}


/*
|--------------------------------------------------------------------------
| Récupère les tags d'un projet
|--------------------------------------------------------------------------
| Retourne tous les tags associés au projet demandé.
*/

function getProjectTags(
    PDO $connexion,
    int $projectId
) {
    // Passe par la table de liaison projets_has_tags pour ne récupérer
    // que les tags réellement associés à ce projet précis.
    $sql = "SELECT tags.*
            FROM tags
            INNER JOIN projets_has_tags
                ON tags.id = projets_has_tags.tag
            WHERE projets_has_tags.projet = :projet";

    $stmt = $connexion->prepare($sql);

    $stmt->execute([
        'projet' => $projectId
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/*
|--------------------------------------------------------------------------
| Supprime un projet
|--------------------------------------------------------------------------
| Supprime d'abord les associations avec les tags,
| puis supprime le projet.
*/

function deleteProject(
    PDO $connexion,
    int $id
) {
    // Supprime les associations du projet avec les tags.
    $sql = "DELETE FROM projets_has_tags
            WHERE projet = :id";

    $stmt = $connexion->prepare($sql);

    $stmt->execute([
        'id' => $id
    ]);


    // Supprime ensuite le projet.
    $sql = "DELETE FROM projets
            WHERE id = :id";

    $stmt = $connexion->prepare($sql);

    $stmt->execute([
        'id' => $id
    ]);
}
