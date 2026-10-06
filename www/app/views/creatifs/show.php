<?php

use \Core\Helpers;

// Vue "Page d'un créa'tif" (route /creatifs/id.html).
// Variables préparées par CreatifsController\showAction().

/** @var array $creatif */
/** @var array $projects */

// La carte projet (_card.php) n'affiche pas le créa'tif : on est déjà sur sa page.
$hideAuthor = true;
?>
<div class="container" style="margin-top: 2.5rem">

    <div class="row">

        <!-- Fiche du créa'tif. -->
        <div class="col-lg-4">

            <div class="ct-side-card">

                <h5 class="ct-side-card__head">
                    <?= Helpers\escape($creatif['pseudo']) ?>
                </h5>

                <div class="ct-side-card__body">

                    <img
                        class="img-fluid"
                        src="images/<?= Helpers\escape($creatif['image']) ?>"
                        alt="<?= Helpers\escape($creatif['pseudo']) ?>">

                    <p class="mt-3"><?= Helpers\escape($creatif['bio']) ?></p>

                </div>

            </div>

        </div>

        <!-- Projets du créa'tif (même carte que l'accueil). -->
        <div class="col-lg-8">

            <h1>Les projets de <?= Helpers\escape($creatif['pseudo']) ?></h1>

            <?php if (empty($projects)): ?>
                <p>Ce créa'tif n'a pas encore de projet.</p>
            <?php endif; ?>

            <?php foreach ($projects as $project): ?>
                <?php include '../app/views/projects/_card.php'; ?>
            <?php endforeach; ?>

        </div>

    </div>

</div>
