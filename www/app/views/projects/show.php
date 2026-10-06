<?php

use \Core\Helpers;

// Vue "détail d'un projet" (route /projets/id/slug.html).
// Variables préparées par ProjectsController\showAction().

/** @var array $project Le projet + pseudo, creatif_image et creatif_bio du créa'tif */
/** @var array $tags    Les tags associés au projet */

$slug = Helpers\slugify($project['titre']);
?>
<div class="container" style="margin-top: 2.5rem">

    <div class="row">

        <!-- Colonne principale -->
        <div class="col-lg-8">

            <h1><?= Helpers\escape($project['titre']) ?></h1>

            <p class="ct-byline">
                par
                <a href="creatifs/<?= $project['creatif'] ?>.html">
                    <?= Helpers\escape($project['pseudo']) ?>
                </a>
                · <?= Helpers\dateFormator($project['dateCreation']) ?>
            </p>

            <div class="mb-4">

                <!-- Modifier : /projects/id/slug/edit/form.html -->
                <a
                    href="projects/<?= $project['id'] ?>/<?= $slug ?>/edit/form.html"
                    class="ct-btn ct-btn--primary">
                    Éditer le projet
                </a>

                <!-- Supprimer : /projets/delete/id/slug.html (confirmation JS avant l'envoi). -->
                <a
                    href="projets/delete/<?= $project['id'] ?>/<?= $slug ?>.html"
                    class="ct-btn ct-btn--danger"
                    onclick="return confirm('Supprimer définitivement ce projet ?');">
                    Supprimer le projet
                </a>

            </div>

            <article class="ct-card">

                <div class="row">

                    <div class="col-md-6">
                        <img
                            class="img-fluid mb-3 mb-md-0"
                            src="images/<?= Helpers\escape($project['image']) ?>"
                            alt="<?= Helpers\escape($project['titre']) ?>">
                    </div>

                    <div class="col-md-6">

                        <p class="lead" style="font-weight: 600">
                            <?= Helpers\escape($project['texte']) ?>
                        </p>

                        <!-- Tags : affichés seulement si le projet en a au moins un. -->
                        <?php if (!empty($tags)): ?>
                            <div class="mt-4">

                                <h5>Tags</h5>

                                <ul class="ct-tags">
                                    <?php foreach ($tags as $tag): ?>
                                        <li>
                                            <a
                                                class="ct-tag"
                                                href="tags/<?= $tag['id'] ?>/<?= Helpers\slugify($tag['nom']) ?>.html">
                                                <?= Helpers\escape($tag['nom']) ?>
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

        <!-- Colonne latérale : mini fiche du créa'tif. -->
        <div class="col-lg-4">

            <div class="ct-side-card">

                <h5 class="ct-side-card__head">Le créa'tif</h5>

                <div class="ct-side-card__body">

                    <div class="ct-profile">

                        <img
                            src="images/<?= Helpers\escape($project['creatif_image']) ?>"
                            alt="<?= Helpers\escape($project['pseudo']) ?>">

                        <div>
                            <strong>
                                <a href="creatifs/<?= $project['creatif'] ?>.html">
                                    <?= Helpers\escape($project['pseudo']) ?>
                                </a>
                            </strong>

                            <p><?= Helpers\escape($project['creatif_bio']) ?></p>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>
