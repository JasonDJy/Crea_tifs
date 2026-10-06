<?php

// Vue "Ajouter un projet" (route /projects/add/form.html).
// Variables préparées par ProjectsController\addFormAction().

/** @var array $errors */
/** @var array $old Valeurs saisies avant un échec de validation ($_POST) */

$formAction = 'projects/add/insert.html';
$isEdit = false;
$values = [
    'title' => $old['title'] ?? '',
    'text' => $old['text'] ?? '',
    'creatif' => (int) ($old['category_id'] ?? 0),
    'tagIds' => array_map('intval', (array) ($old['tags'] ?? [])),
];
?>
<div class="container" style="margin-top: 2.5rem">

    <div class="row">

        <div class="col-lg-8 py-3">

            <h1 class="mb-4">Ajouter un projet</h1>

            <?php include '../app/views/projects/_form.php'; ?>

        </div>

        <!-- Sidebar : mêmes partials que l'accueil. -->
        <?php include '../app/views/templates/partials/_aside.php'; ?>

    </div>

</div>
