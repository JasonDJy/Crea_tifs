<?php

use \Core\Helpers;

// Vue "Modifier un projet" (route /projects/id/slug/edit/form.html).
// Variables préparées par ProjectsController\editFormAction().
// Même formulaire que l'ajout, pré-rempli avec le projet ($project) et
// avec les tags déjà associés ($projectTags) cochés.

/** @var array $project */
/** @var array $projectTags */
/** @var array $errors */
/** @var array $old Valeurs saisies avant un échec de validation ($_POST) */

$slug = Helpers\slugify($project['titre']);

$formAction = 'projects/' . $project['id'] . '/' . $slug . '/edit/update.html';
$cancelUrl = 'projets/' . $project['id'] . '/' . $slug . '.html';
$isEdit = true;

// Après un échec de validation, les valeurs saisies ($old) l'emportent sur celles de la base.
$values = [
    'title' => $old['title'] ?? $project['titre'],
    'text' => $old['text'] ?? $project['texte'],
    'creatif' => (int) ($old['category_id'] ?? $project['creatif']),
    'tagIds' => empty($old)
        ? array_map('intval', array_column($projectTags, 'id'))
        : array_map('intval', (array) ($old['tags'] ?? [])),
];
?>
<div class="container" style="margin-top: 2.5rem">

    <div class="row">

        <div class="col-lg-8 py-3">

            <h1 class="mb-4">Modifier le projet</h1>

            <?php include '../app/views/projects/_form.php'; ?>

        </div>

        <?php include '../app/views/templates/partials/_aside.php'; ?>

    </div>

</div>
