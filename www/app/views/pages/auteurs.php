
<?php

// -----------------------------------------------------------------------------
// Vue "Page d'un créa'tif" (route /creatifs/id.html, gérée par authorAction()).
// Affiche sa fiche ($auteur) et la liste de tous ses projets ($projetsAuteur).
// -----------------------------------------------------------------------------

/** @var array $auteur */
/** @var array $projetsAuteur */

?>

<div class="container" style="margin-top: 2.5rem">

    <div class="row">

        <!-- Informations du créa'tif. -->
        <div class="col-lg-4">

            <div class="ct-side-card">

                <h5 class="ct-side-card__head">
                    <?= $auteur['pseudo'] ?>
                </h5>

                <div class="ct-side-card__body">

                    <img
                        class="img-fluid"
                        src="images/<?= $auteur['image'] ?>"
                        alt="<?= $auteur['pseudo'] ?>">

                    <p class="mt-3">
                        <?= $auteur['bio'] ?>
                    </p>

                </div>

            </div>

        </div>


        <!-- Liste des projets du créa'tif (même style de "carte" que la page d'accueil). -->
        <div class="col-lg-8">

            <h1>
                Les projets de <?= $auteur['pseudo'] ?>
            </h1>

            <?php foreach ($projetsAuteur as $project): ?>

                <article class="ct-card">

                    <div class="row">

                        <div class="col-md-4">

                            <a
                                href="projets/<?= $project['id'] ?>/<?= slugify($project['titre']) ?>.html">

                                <img
                                    class="img-fluid"
                                    src="images/<?= $project['image'] ?>"
                                    alt="<?= $project['titre'] ?>">

                            </a>

                        </div>

                        <div class="col-md-8">

                            <h3>
                                <?= $project['titre'] ?>
                            </h3>

                            <!-- Aperçu tronqué à 100 caractères, comme sur la page d'accueil. -->
                            <p>
                                <?= truncate($project['texte'], 100) ?>
                            </p>

                            <a
                                class="ct-btn ct-btn--primary ct-btn--sm"
                                href="projets/<?= $project['id'] ?>/<?= slugify($project['titre']) ?>.html">

                                Voir le projet

                            </a>

                        </div>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    </div>

</div>
