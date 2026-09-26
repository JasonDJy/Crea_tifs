
<?php

// -----------------------------------------------------------------------------
// Vue "détail d'un projet" (route /projets/id/slug.html).
// Reçoit $project (le projet + les infos du créa'tif via l'INNER JOIN
// du modèle) et $tagsProjet (les tags associés), préparés par projectAction().
// -----------------------------------------------------------------------------

/** @var array $project */
/** @var array $tagsProjet */

?>

<div class="container" style="margin-top: 2.5rem">

    <div class="row">

        <!-- Colonne principale -->
        <div class="col-lg-8">

            <h1>
                <?= $project['titre'] ?>
            </h1>

            <p class="ct-byline">

                par

                <a href="creatifs/<?= $project['creatif'] ?>.html">
                    <?= $project['pseudo'] ?>
                </a>

                · <?= date('d/m/Y', strtotime($project['dateCreation'])) ?>

            </p>


            <div class="mb-4">

                <!-- Modifier le projet : route /projects/id/slug/edit/form.html -->
                <a
                    href="projects/<?= $project['id'] ?>/<?= slugify($project['titre']) ?>/edit/form.html"
                    class="ct-btn ct-btn--primary">

                    Éditer le projet

                </a>


                <!-- Supprimer le projet : route /projets/delete/id/slug.html.
                     confirm() JS demande une confirmation avant d'envoyer le lien. -->
                <a
                    href="projets/delete/<?= $project['id'] ?>/<?= slugify($project['titre']) ?>.html"
                    class="ct-btn ct-btn--danger"
                    onclick="return confirm('Supprimer définitivement ce projet ?');">

                    Supprimer le projet

                </a>

            </div>


            <article class="ct-card">

                <div class="row">

                    <!-- Image du projet -->
                    <div class="col-md-6">

                        <img
                            class="img-fluid mb-3 mb-md-0"
                            src="images/<?= $project['image'] ?>"
                            alt="<?= $project['titre'] ?>">

                    </div>


                    <!-- Description du projet -->
                    <div class="col-md-6">

                        <p class="lead" style="font-weight: 600">
                            <?= $project['texte'] ?>
                        </p>


                        <!-- Tags : affichés uniquement si le projet en a au moins un. -->
                        <?php if (!empty($tagsProjet)): ?>

                            <div class="mt-4">

                                <h5>
                                    Tags
                                </h5>

                                <ul class="ct-tags">

                                    <?php foreach ($tagsProjet as $tag): ?>

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

                        <?php endif; ?>

                    </div>

                </div>

            </article>

        </div>


        <!-- Colonne latérale : mini fiche du créa'tif de ce projet. -->
        <div class="col-lg-4">

            <div class="ct-side-card">

                <h5 class="ct-side-card__head">
                    Le créa'tif
                </h5>

                <div class="ct-side-card__body">

                    <div class="ct-profile">

                        <!-- Image du créa'tif -->
                        <img
                            src="images/<?= $project['creatif_image'] ?>"
                            alt="<?= $project['pseudo'] ?>">

                        <div>

                            <strong>

                                <a href="creatifs/<?= $project['creatif'] ?>.html">

                                    <?= $project['pseudo'] ?>

                                </a>

                            </strong>

                            <p>
                                <?= $project['creatif_bio'] ?>
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
