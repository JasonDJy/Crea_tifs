<?php

namespace Core\Helpers;

// -----------------------------------------------------------------------------
// Fonctions utilitaires partagées par toute l'application.
// Utilisation : `use \Core\Helpers;` puis `Helpers\slugify(...)`.
// -----------------------------------------------------------------------------

/**
 * Transforme une chaîne en slug (partie lisible d'une URL).
 * Le slug n'existe pas en base : il est recalculé à la volée.
 * Ex : "Frange Kamikaze !" => "frange-kamikaze"
 *
 * Étapes : minuscules, suppression des accents (é è ê à ç â ...), puis
 * remplacement de tout ce qui n'est pas une lettre/un chiffre (' ', '.', '!',
 * '?', ''', ';' ...) par un tiret.
 */
function slugify(string $texte): string
{
    $texte = mb_strtolower($texte, 'UTF-8');

    $texte = strtr($texte, [
        'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
        'à' => 'a', 'â' => 'a', 'ä' => 'a',
        'î' => 'i', 'ï' => 'i',
        'ô' => 'o', 'ö' => 'o',
        'ù' => 'u', 'û' => 'u', 'ü' => 'u',
        'ç' => 'c'
    ]);

    // Plusieurs caractères spéciaux consécutifs donnent un seul tiret.
    $texte = preg_replace('/[^a-z0-9]+/', '-', $texte);
    $texte = trim($texte, '-');

    // Une URL ne peut pas avoir un slug vide (ex: titre "!!!").
    return $texte === '' ? 'sans-titre' : $texte;
}

/**
 * Coupe un texte à l'espace situé juste avant le $x-ème caractère
 * (pour ne pas couper un mot en deux) et ajoute "...".
 * Les fonctions mb_* évitent de couper un caractère accentué en deux.
 */
function truncate(string $texte, int $x): string
{
    if (mb_strlen($texte) <= $x) {
        return $texte;
    }

    $texte = mb_substr($texte, 0, $x);
    $position = mb_strrpos($texte, ' ');

    if ($position !== false) {
        $texte = mb_substr($texte, 0, $position);
    }

    return $texte . '...';
}

/**
 * Formate une date SQL (ex: 2018-07-18 00:00:00) pour l'affichage.
 */
function dateFormator(string $date, string $format = 'd/m/Y'): string
{
    return date($format, strtotime($date));
}

/**
 * Protège l'affichage d'une donnée dans le HTML (contre les failles XSS).
 */
function escape(?string $texte): string
{
    return htmlspecialchars((string) $texte, ENT_QUOTES, 'UTF-8');
}

/**
 * Numéro de la page demandée dans l'URL (?page=2). Minimum 1.
 */
function currentPage(): int
{
    $page = (int) ($_GET['page'] ?? 1);

    return $page < 1 ? 1 : $page;
}

/**
 * Enregistre une image envoyée par formulaire dans public/images.
 * Le fichier est renommé (slug + identifiant unique) pour éviter d'écraser
 * une image existante ; l'extension doit faire partie de la liste autorisée.
 *
 * @param array $fichier Un élément de $_FILES (ex: $_FILES['image'])
 * @return string|null Le nom du fichier enregistré, null en cas d'échec
 */
function uploadImage(array $fichier): ?string
{
    if (($fichier['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }

    $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, IMAGES_ALLOWED_EXTENSIONS, true)) {
        return null;
    }

    // Colonne `image` = varchar(45) : slug limité à 20 caractères + uniqid (13) + extension.
    $nom = mb_substr(slugify(pathinfo($fichier['name'], PATHINFO_FILENAME)), 0, 20)
        . '-' . uniqid() . '.' . $extension;

    if (!move_uploaded_file($fichier['tmp_name'], IMAGES_DIR . $nom)) {
        return null;
    }

    return $nom;
}
