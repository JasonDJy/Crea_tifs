<?php

namespace App\Models\ProjectsModel;

use \PDO;

// Modèle "Projets" : requêtes SQL sur la table `projets`.
// Les contrôleurs ne parlent jamais directement à la base de données :
// ils passent par ces fonctions.

/**
 * Récupère une page de projets (avec le pseudo du créa'tif).
 * L'INNER JOIN évite une requête supplémentaire par projet.
 */
function findAll(PDO $connexion, int $page = 1, int $limit = PROJECTS_PER_PAGE): array
{
    $sql = "SELECT projets.*, creatifs.pseudo
            FROM projets
            INNER JOIN creatifs ON projets.creatif = creatifs.id
            ORDER BY projets.id ASC
            LIMIT :limit OFFSET :offset;";
    $rs = $connexion->prepare($sql);
    $rs->bindValue(':limit', $limit, PDO::PARAM_INT);
    $rs->bindValue(':offset', ($page - 1) * $limit, PDO::PARAM_INT);
    $rs->execute();
    return $rs->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Compte tous les projets (pour calculer le nombre de pages).
 */
function countAll(PDO $connexion): int
{
    $sql = "SELECT COUNT(*)
            FROM projets;";
    $rs = $connexion->prepare($sql);
    $rs->execute();
    return (int) $rs->fetchColumn();
}

/**
 * Récupère un projet par son id, avec les infos de son créa'tif
 * (colonnes renommées pour ne pas entrer en conflit avec celles de `projets`).
 * Renvoie null si le projet n'existe pas.
 */
function findOneById(PDO $connexion, int $id): ?array
{
    $sql = "SELECT projets.*,
                   creatifs.pseudo,
                   creatifs.image AS creatif_image,
                   creatifs.bio AS creatif_bio
            FROM projets
            INNER JOIN creatifs ON projets.creatif = creatifs.id
            WHERE projets.id = :id;";
    $rs = $connexion->prepare($sql);
    $rs->bindValue(':id', $id, PDO::PARAM_INT);
    $rs->execute();
    return $rs->fetch(PDO::FETCH_ASSOC) ?: null;
}

/**
 * Récupère tous les projets d'un créa'tif.
 */
function findAllByCreatifId(PDO $connexion, int $creatifId): array
{
    $sql = "SELECT projets.*
            FROM projets
            WHERE projets.creatif = :creatif
            ORDER BY projets.id ASC;";
    $rs = $connexion->prepare($sql);
    $rs->bindValue(':creatif', $creatifId, PDO::PARAM_INT);
    $rs->execute();
    return $rs->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère une page de projets associés à un tag.
 */
function findAllByTagId(PDO $connexion, int $tagId, int $page = 1, int $limit = PROJECTS_PER_PAGE): array
{
    $sql = "SELECT projets.*, creatifs.pseudo
            FROM projets
            INNER JOIN creatifs ON projets.creatif = creatifs.id
            INNER JOIN projets_has_tags ON projets.id = projets_has_tags.projet
            WHERE projets_has_tags.tag = :tag
            ORDER BY projets.id ASC
            LIMIT :limit OFFSET :offset;";
    $rs = $connexion->prepare($sql);
    $rs->bindValue(':tag', $tagId, PDO::PARAM_INT);
    $rs->bindValue(':limit', $limit, PDO::PARAM_INT);
    $rs->bindValue(':offset', ($page - 1) * $limit, PDO::PARAM_INT);
    $rs->execute();
    return $rs->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Compte les projets associés à un tag.
 */
function countAllByTagId(PDO $connexion, int $tagId): int
{
    $sql = "SELECT COUNT(*)
            FROM projets_has_tags
            WHERE tag = :tag;";
    $rs = $connexion->prepare($sql);
    $rs->bindValue(':tag', $tagId, PDO::PARAM_INT);
    $rs->execute();
    return (int) $rs->fetchColumn();
}

/**
 * Insère un projet et renvoie son id.
 * La date de création est générée par MySQL (NOW()).
 *
 * @param array $data Clés attendues : titre, texte, creatif, image
 */
function insertOne(PDO $connexion, array $data): int
{
    $sql = "INSERT INTO projets (titre, texte, dateCreation, image, creatif)
            VALUES (:titre, :texte, NOW(), :image, :creatif);";
    $rs = $connexion->prepare($sql);
    $rs->bindValue(':titre', $data['titre'], PDO::PARAM_STR);
    $rs->bindValue(':texte', $data['texte'], PDO::PARAM_STR);
    $rs->bindValue(':image', $data['image'], PDO::PARAM_STR);
    $rs->bindValue(':creatif', $data['creatif'], PDO::PARAM_INT);
    $rs->execute();
    return (int) $connexion->lastInsertId();
}

/**
 * Modifie le titre, le texte, l'image et le créa'tif d'un projet.
 *
 * @param array $data Clés attendues : titre, texte, creatif, image
 */
function updateOneById(PDO $connexion, int $id, array $data): void
{
    $sql = "UPDATE projets
            SET titre = :titre,
                texte = :texte,
                image = :image,
                creatif = :creatif
            WHERE id = :id;";
    $rs = $connexion->prepare($sql);
    $rs->bindValue(':id', $id, PDO::PARAM_INT);
    $rs->bindValue(':titre', $data['titre'], PDO::PARAM_STR);
    $rs->bindValue(':texte', $data['texte'], PDO::PARAM_STR);
    $rs->bindValue(':image', $data['image'], PDO::PARAM_STR);
    $rs->bindValue(':creatif', $data['creatif'], PDO::PARAM_INT);
    $rs->execute();
}

/**
 * Supprime un projet : d'abord ses associations avec les tags (clés
 * étrangères), puis le projet. La transaction garantit que les deux
 * suppressions réussissent ensemble ou pas du tout.
 */
function deleteOneById(PDO $connexion, int $id): void
{
    $connexion->beginTransaction();

    try {
        $sql = "DELETE FROM projets_has_tags
                WHERE projet = :id;";
        $rs = $connexion->prepare($sql);
        $rs->bindValue(':id', $id, PDO::PARAM_INT);
        $rs->execute();

        $sql = "DELETE FROM projets
                WHERE id = :id;";
        $rs = $connexion->prepare($sql);
        $rs->bindValue(':id', $id, PDO::PARAM_INT);
        $rs->execute();

        $connexion->commit();
    } catch (\PDOException $e) {
        $connexion->rollBack();
        throw $e;
    }
}
