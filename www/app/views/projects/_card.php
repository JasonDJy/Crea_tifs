<?php

use \Core\Helpers;

// Partial "carte d'un projet" : réutilisé par la liste des projets (accueil,
// tags) et par la page d'un créa'tif.
// Si $hideAuthor est défini, la ligne "par pseudo · date" n'est pas affichée.

/** @var array $project */
/** @var bool $hideAuthor */

// Le slug n'est pas stocké en base : il est recalculé à la volée.
$projectUrl = 'projets/' . $project['id'] . '/' . Helpers\slugify($project['titre']) . '.html';
?>
<article class="ct-card">

    <div class="row">

        <div class="col-md-4">

            <!-- Vignette cliquable vers le détail du projet. -->
            <a href="<?= $projectUrl ?>">
                <img
                    class="img-fluid mb-3 mb-md-0"
                    src="images/<?= Helpers\escape($project['image']) ?>"
                    alt="<?= Helpers\escape($project['titre']) ?>">
            </a>

        </div>

        <div class="col-md-8">

            <h3>
                <a href="<?= $projectUrl ?>">
                    <?= Helpers\escape($project['titre']) ?>
                </a>
            </h3>

            <?php if (empty($hideAuthor)): ?>
                <p class="ct-byline">
                    par
                    <a href="creatifs/<?= $project['creatif'] ?>.html">
                        <?= Helpers\escape($project['pseudo']) ?>
                    </a>
                    · <?= Helpers\dateFormator($project['dateCreation']) ?>
                </p>
            <?php endif; ?>

            <!-- Aperçu tronqué (consigne : 100 caractères). -->
            <p>
                <?= Helpers\escape(Helpers\truncate((string) $project['texte'], PREVIEW_LENGTH)) ?>
            </p>

            <a class="ct-btn ct-btn--primary ct-btn--sm" href="<?= $projectUrl ?>">
                Voir le projet
            </a>

        </div>

    </div>

</article>
