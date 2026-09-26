
<?php

// $tags vient de dashboardAction() : tableau de tous les tags disponibles.

/** @var array $tags */

?>

<div class="ct-side-card">

    <h5 class="ct-side-card__head">
        Tags
    </h5>

    <div class="ct-side-card__body">

        <ul class="ct-tags">

            <!-- Chaque tag est un lien "?tag=id" : lu par dashboardAction()
                 via $_GET['tag'] pour filtrer la liste des projets. -->
            <?php foreach ($tags as $tag): ?>

                <li>

                    <a
                        class="ct-tag"
                        href="?tag=<?= $tag['id'] ?>">
                        <?= $tag['nom'] ?>
                    </a>

                </li>

            <?php endforeach; ?>

        </ul>

    </div>

</div>
