<?php

namespace App\Models\AuteursModel;

// -----------------------------------------------------------------------------
// Modèle "Créa'tifs" (auteurs) : requêtes SQL liées à la table `creatifs`.
// -----------------------------------------------------------------------------

use \PDO;


/*
|--------------------------------------------------------------------------
| Récupère tous les créa'tifs
|--------------------------------------------------------------------------
*/

function getAuthors(PDO $connexion)
{
    // Utilisé notamment pour afficher la liste des créa'tifs dans la sidebar
    // et pour remplir le <select> des formulaires d'ajout/modification.
    $sql = "SELECT * FROM creatifs";

    $stmt = $connexion->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/*
|--------------------------------------------------------------------------
| Récupère un créa'tif par son identifiant
|--------------------------------------------------------------------------
*/

function getAuthorById(PDO $connexion, int $id)
{
    $sql = "SELECT *
            FROM creatifs
            WHERE id = :id";

    $stmt = $connexion->prepare($sql);

    $stmt->execute([
        'id' => $id
    ]);

    // fetch() renvoie une seule ligne (ou false si l'id n'existe pas).
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


/*
|--------------------------------------------------------------------------
| Récupère les projets d'un créa'tif
|--------------------------------------------------------------------------
*/

function getProjectsByAuthor(PDO $connexion, int $authorId, int $page = 1)
{
    // Même logique de pagination que ProjectsModel\getProjects(),
    // mais restreinte aux projets d'un seul créa'tif.
    $limit = 10;
    $offset = ($page - 1) * $limit;

    $sql = "SELECT projets.*, creatifs.pseudo
            FROM projets
            INNER JOIN creatifs
                ON projets.creatif = creatifs.id
            WHERE projets.creatif = :author
            ORDER BY projets.id ASC
            LIMIT $limit OFFSET $offset";

    $stmt = $connexion->prepare($sql);

    $stmt->execute([
        'author' => $authorId
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


/*
|--------------------------------------------------------------------------
| Compte les projets d'un créa'tif
|--------------------------------------------------------------------------
*/

function countProjectsByAuthor(PDO $connexion, int $authorId)
{
    // Compte les projets d'un créa'tif donné (pour une éventuelle pagination
    // de sa page de profil).
    $sql = "SELECT COUNT(*)
            FROM projets
            WHERE creatif = :author";

    $stmt = $connexion->prepare($sql);

    $stmt->execute([
        'author' => $authorId
    ]);

    return $stmt->fetchColumn();
}
