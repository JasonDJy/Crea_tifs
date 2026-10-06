<?php

namespace App\Models\TagsModel;

use \PDO;

// Modèle "Tags" : requêtes SQL sur la table `tags` et sur la table
// de liaison `projets_has_tags` (association projet <-> tag).

/**
 * Récupère tous les tags (sidebar, cases à cocher des formulaires).
 */
function findAll(PDO $connexion): array
{
    $sql = "SELECT *
            FROM tags;";
    $rs = $connexion->prepare($sql);
    $rs->execute();
    return $rs->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère un tag par son id (null s'il n'existe pas).
 */
function findOneById(PDO $connexion, int $id): ?array
{
    $sql = "SELECT *
            FROM tags
            WHERE id = :id;";
    $rs = $connexion->prepare($sql);
    $rs->bindValue(':id', $id, PDO::PARAM_INT);
    $rs->execute();
    return $rs->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Récupère les tags associés à un projet (via la table de liaison).
 */
function findAllByProjectId(PDO $connexion, int $projectId): array
{
    $sql = "SELECT tags.*
            FROM tags
            INNER JOIN projets_has_tags
                ON tags.id = projets_has_tags.tag
            WHERE projets_has_tags.projet = :projet;";
    $rs = $connexion->prepare($sql);
    $rs->bindValue(':projet', $projectId, PDO::PARAM_INT);
    $rs->execute();
    return $rs->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Associe un tag à un projet.
 */
function insertProjectTag(PDO $connexion, int $projectId, int $tagId): void
{
    $sql = "INSERT INTO projets_has_tags (projet, tag)
            VALUES (:projet, :tag);";
    $rs = $connexion->prepare($sql);
    $rs->bindValue(':projet', $projectId, PDO::PARAM_INT);
    $rs->bindValue(':tag', $tagId, PDO::PARAM_INT);
    $rs->execute();
}

/**
 * Supprime toutes les associations d'un projet avec ses tags
 * (les tags et le projet restent intacts). Sert de "reset" avant une modification.
 */
function deleteProjectTags(PDO $connexion, int $projectId): void
{
    $sql = "DELETE FROM projets_has_tags
            WHERE projet = :projet;";
    $rs = $connexion->prepare($sql);
    $rs->bindValue(':projet', $projectId, PDO::PARAM_INT);
    $rs->execute();
}
