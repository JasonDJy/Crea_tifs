<?php

use \Core\Helpers;

// Vue "liste des projets" : accueil (/) et projets d'un tag (/tags/id/slug.html).
// Variables préparées par ProjectsController\indexAction() ou TagsController\showAction().

/** @var array $projects */
/** @var string|null $heading */
?>
<div class="container ct-content-wrap">

    <div class="row">

        <div class="col-lg-8">

            <?php if (!empty($heading)): ?>
                <h2 class="mb-4"><?= Helpers\escape($heading) ?></h2>
            <?php endif; ?>

            <?php if (empty($projects)): ?>
                <p>Aucun projet à afficher.</p>
            <?php endif; ?>

            <!-- Une carte par projet de la page courante. -->
            <?php foreach ($projects as $project): ?>
                <?php include '../app/views/projects/_card.php'; ?>
            <?php endforeach; ?>

            <!-- Précédent / numéros / Suivant. -->
            <?php include '../app/views/templates/partials/_pagination.php'; ?>

        </div>

        <!-- Sidebar : créa'tifs + tags. -->
        <?php include '../app/views/templates/partials/_aside.php'; ?>

    </div>

</div>
