
<?php

// -----------------------------------------------------------------------------
// Création de la connexion à MySQL avec PDO.
// PDO permet notamment d'utiliser des requêtes préparées dans les modèles.
// -----------------------------------------------------------------------------
try {
    $connexion = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PWD,
        [
            // Transforme les erreurs SQL en exceptions PHP.
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            // Les résultats SQL seront récupérés sous forme de tableaux associatifs.
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    // Si la connexion échoue, on arrête l'application et on affiche l'erreur.
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}
