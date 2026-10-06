<?php

// Pagination (Précédent / numéros / Suivant) de la liste des projets.
// Variables préparées par le contrôleur :
//   $page          : page courante
//   $totalPages    : nombre total de pages
//   $paginationUrl : '' pour l'accueil, 'tags/id/slug.html' pour un tag
//                    (les liens sont relatifs à <base href>)

/** @var int $page */
/** @var int $totalPages */
/** @var string $paginationUrl */

// Rien à paginer s'il n'y a qu'une page.
if ($totalPages <= 1) {
    return;
}
?>
<nav aria-label="Navigation entre les pages de projets">

    <ul class="pagination ct-pagination" style="justify-content: center">

        <!-- "Précédent" : seulement si on n'est pas sur la 1ère page. -->
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="<?= $paginationUrl ?>?page=<?= $page - 1 ?>">
                    Précédent
                </a>
            </li>
        <?php endif; ?>

        <!-- Un numéro par page ; la page courante reçoit la classe "active". -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $page === $i ? 'active' : '' ?>">
                <a class="page-link" href="<?= $paginationUrl ?>?page=<?= $i ?>">
                    <?= $i ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- "Suivant" : seulement s'il reste des pages après la courante. -->
        <?php if ($page < $totalPages): ?>
            <li class="page-item">
                <a class="page-link" href="<?= $paginationUrl ?>?page=<?= $page + 1 ?>">
                    Suivant
                </a>
            </li>
        <?php endif; ?>

    </ul>

</nav>
