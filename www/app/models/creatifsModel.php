<?php

namespace App\Models\CreatifsModel;

use \PDO;

// Modèle "Créa'tifs" : requêtes SQL sur la table `creatifs`.

/**
 * Récupère tous les créa'tifs.
 * Utilisé par la sidebar et par les <select> des formulaires.
 */
function findAll(PDO $connexion): array
{
    $sql = "SELECT *
            FROM creatifs;";
    $rs = $connexion->prepare($sql);
    $rs->execute();
    return $rs->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * Récupère un créa'tif par son id (null s'il n'existe pas).
 */
function findOneById(PDO $connexion, int $id): ?array
{
    $sql = "SELECT *
            FROM creatifs
            WHERE id = :id;";
    $rs = $connexion->prepare($sql);
    $rs->bindValue(':id', $id, PDO::PARAM_INT);
    $rs->execute();
    return $rs->fetch(PDO::FETCH_ASSOC) ?: null;
}
