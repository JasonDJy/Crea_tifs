<?php

namespace App\Models\TagsModel;

// -----------------------------------------------------------------------------
// Modèle "Tags" : requêtes SQL liées à la table `tags` et au filtrage
// des projets par tag (via la table de liaison `projets_has_tags`).
// -----------------------------------------------------------------------------

use \PDO;


/*
|--------------------------------------------------------------------------
| Récupère tous les tags
|--------------------------------------------------------------------------
*/

function getTags(PDO $connexion)
{
    // Liste complète des tags, utilisée dans la sidebar (filtre) et
    // dans les formulaires d'ajout/modification (cases à cocher).
    $sql = "SELECT * FROM tags";

    $stmt = $connexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/*
|--------------------------------------------------------------------------
| Récupère les projets associés à un tag
|--------------------------------------------------------------------------
*/

function getProjectsByTag(PDO $connexion, int $tagId, int $page = 1)
{
    // Même logique de pagination que ProjectsModel\getProjects(),
    // mais restreinte aux projets liés à un tag précis (double INNER JOIN :
    // un pour récupérer le pseudo du créa'tif, un pour filtrer par tag).
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $sql = "SELECT projets.*, creatifs.pseudo
            FROM projets
            INNER JOIN creatifs
                ON projets.creatif = creatifs.id
            INNER JOIN projets_has_tags
                ON projets.id = projets_has_tags.projet
            WHERE projets_has_tags.tag = :tag
            ORDER BY projets.id ASC
            LIMIT $limit OFFSET $offset";

    $stmt = $connexion->prepare($sql);

    $stmt->execute([
        'tag' => $tagId
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/*
|--------------------------------------------------------------------------
| Compte les projets associés à un tag
|--------------------------------------------------------------------------
*/

function countProjectsByTag(PDO $connexion, int $tagId)
{
    // Compte directement dans la table de liaison : un projet compte une fois
    // par tag auquel il est associé (pas besoin de rejoindre `projets` ici).
    $sql = "SELECT COUNT(*)
            FROM projets_has_tags
            WHERE tag = :tag";

    $stmt = $connexion->prepare($sql);

    $stmt->execute([
        'tag' => $tagId
    ]);

    return $stmt->fetchColumn();
}
