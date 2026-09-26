
<?php

// $page et $totalPages sont calculés par dashboardAction() (page courante
// et nombre total de pages en fonction du nombre de projets / 10).

/** @var int $page */
/** @var int $totalPages */

?>

<nav aria-label="Navigation entre les pages de projets">

    <ul class="pagination ct-pagination" style="justify-content: center">


        <!-- Lien "Précédent", affiché seulement si on n'est pas sur la 1ère page. -->
        <?php if ($page > 1): ?>

            <li class="page-item">

                <a
                    class="page-link"
                    href="<?= isset($_GET['tag'])
                                ? '?tag=' . (int) $_GET['tag'] . '&page=' . ($page - 1)
                                : '?page=' . ($page - 1) ?>">

                    Précédent

                </a>

            </li>

        <?php endif; ?>


        <!-- Un numéro de page par page disponible ; la page courante
             reçoit la classe "active" pour être mise en évidence.
             Le filtre par tag ($_GET['tag']), s'il est actif, est conservé
             dans chaque lien pour ne pas le perdre en changeant de page. -->
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>

            <li class="page-item <?= $page == $i ? 'active' : '' ?>">

                <a
                    class="page-link"
                    href="<?= isset($_GET['tag'])
                                ? '?tag=' . (int) $_GET['tag'] . '&page=' . $i
                                : '?page=' . $i ?>">

                    <?= $i ?>

                </a>

            </li>

        <?php endfor; ?>


        <!-- Lien "Suivant", affiché seulement s'il reste des pages après la courante. -->
        <?php if ($page < $totalPages): ?>

            <li class="page-item">

                <a
                    class="page-link"
                    href="<?= isset($_GET['tag'])
                                ? '?tag=' . (int) $_GET['tag'] . '&page=' . ($page + 1)
                                : '?page=' . ($page + 1) ?>">

                    Suivant

                </a>

            </li>

        <?php endif; ?>


    </ul>

</nav>
