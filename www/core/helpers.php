
<?php

// -----------------------------------------------------------------------------
// Fonctions utilitaires (helpers) partagées par toute l'application :
// - slugify() : construit des URLs "propres" à partir d'un titre.
// - truncate() : raccourcit un texte pour les aperçus (page d'accueil, etc.).
// Ces fonctions sont chargées une seule fois par core/init.php.
// -----------------------------------------------------------------------------

/**
 * Transforme une chaîne de caractères en slug.
 * Un slug est la partie "lisible" d'une URL (ex: "mon-super-titre"),
 * utilisée ici dans les liens /projets/id/slug.html pour rendre les URLs
 * plus explicites (le "slug" n'existe pas en base, il est recalculé à la volée).
 *
 * @param string $texte
 * @return string
 */
function slugify(string $texte): string
{
    // Etape 1 : tout passer en minuscules.
    $texte = strtolower($texte);

    // Etape 2 : remplacer les lettres accentuées par leur équivalent sans accent
    // (é/è/ê -> e, à/â -> a, ç -> c) pour éviter les soucis d'encodage dans l'URL.
    $texte = str_replace(
        ['é', 'è', 'ê', 'à', 'ç', 'â'],
        ['e', 'e', 'e', 'a', 'c', 'a'],
        $texte
    );

    // Etape 3 : remplacer les espaces et la ponctuation par des tirets,
    // qui sont le séparateur habituel dans une URL lisible.
    $texte = str_replace(
        [' ', '.', '!', '?', "'", ';', ','],
        '-',
        $texte
    );

    // Remplace plusieurs tirets consécutifs par un seul
    // (ex: "salut !!" donnerait sinon "salut--" au lieu de "salut-")
    $texte = preg_replace('/-+/', '-', $texte);

    // Supprime les tirets au début et à la fin
    // (au cas où le titre commence/finit par un espace ou un signe de ponctuation)
    $texte = trim($texte, '-');

    return $texte;
}


/**
 * Tronque une chaîne au niveau de l'espace
 * situé avant le nombre de caractères demandé.
 * Utilisée pour afficher un aperçu court du texte d'un projet
 * (ex: 100 caractères max sur la page d'accueil) sans couper un mot en deux.
 *
 * @param string $texte Le texte complet à raccourcir.
 * @param int $x Le nombre maximum de caractères autorisés.
 * @return string
 */
function truncate(string $texte, int $x): string
{
    // Si le texte est déjà assez court, on le renvoie tel quel (rien à couper).
    if (strlen($texte) <= $x) {
        return $texte;
    }

    // On coupe brutalement à x caractères (ça peut tomber au milieu d'un mot).
    $texte = substr($texte, 0, $x);

    // On cherche le dernier espace avant cette coupure,
    // afin de ne pas afficher un mot tronqué.
    $position = strrpos($texte, ' ');

    if ($position !== false) {
        // On recoupe juste avant cet espace pour garder des mots entiers.
        $texte = substr($texte, 0, $position);
    }

    // On ajoute "..." pour indiquer que le texte a été raccourci.
    return $texte . '...';
}
