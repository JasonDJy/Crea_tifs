<?php

// Connexion à MySQL avec PDO (requêtes préparées dans les modèles).
try {
    $connexion = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PWD,
        [
            // Les erreurs SQL deviennent des exceptions PHP.
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            // Les résultats sont des tableaux associatifs.
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
