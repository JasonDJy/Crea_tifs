<?php

namespace App\Controllers\PagesController;

// Contrôleur "Pages" : pages génériques qui ne dépendent d'aucune ressource.

/**
 * Affiche une page d'erreur 404 avec le message donné.
 * Appelée par les autres contrôleurs quand un élément demandé n'existe pas.
 */
function notFoundAction(string $message): void
{
    global $content, $title;

    http_response_code(404);

    $title = "Page introuvable";
    ob_start();
    include '../app/views/pages/notFound.php';
    $content = ob_get_clean();
}
