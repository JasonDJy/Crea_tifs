
<?php

// -----------------------------------------------------------------------------
// Vue "Modifier un projet" (routes .../edit/form.html et .../edit/update.html,
// toutes deux gérées par modifierProjetAction()). Même formulaire que l'ajout,
// mais pré-rempli avec les valeurs actuelles du projet ($projetAModifier)
// et avec les cases à cocher des tags déjà associés ($tagsProjet) cochées.
// -----------------------------------------------------------------------------

/** @var array $projetAModifier */
/** @var array $auteurs */
/** @var array $tags */
/** @var array $tagsProjet */

?>

<div class="container" style="margin-top: 2.5rem">

    <div class="row">

        <!-- Formulaire de modification du projet. -->
        <div class="col-lg-8 py-3">

            <h1 class="mb-4">
                Modifier le projet
            </h1>

            <!-- L'action pointe vers la route "update.html" du projet en cours. -->
            <form
                action="projects/<?= $projetAModifier['id'] ?>/<?= slugify($projetAModifier['titre']) ?>/edit/update.html"
                method="post"
                enctype="multipart/form-data"
                class="ct-form-card">


                <label for="title">
                    Titre du projet
                </label>

                <!-- value pré-rempli avec le titre actuel du projet. -->
                <input
                    type="text"
                    name="title"
                    id="title"
                    class="form-control"
                    value="<?= $projetAModifier['titre'] ?>">


                <label for="text">
                    Description
                </label>

                <!-- Contenu actuel du textarea = texte actuel du projet. -->
                <textarea
                    id="text"
                    name="text"
                    class="form-control"
                    rows="5"><?= $projetAModifier['texte'] ?></textarea>


                <label for="creatif-file">
                    Photo du résultat
                </label>

                <!-- Champ facultatif : si aucun fichier n'est choisi,
                     l'image actuelle est conservée (voir modifierProjetAction()). -->
                <div class="ct-dropzone">

                    ✂️ Choisissez une nouvelle image si vous souhaitez la remplacer

                    <input
                        type="file"
                        class="form-control-file"
                        id="creatif-file"
                        name="image">

                </div>


                <label for="category">
                    Créa'tif
                </label>

                <!-- Le créa'tif actuellement associé au projet est présélectionné. -->
                <select
                    id="category"
                    name="category_id"
                    class="form-control">

                    <?php foreach ($auteurs as $auteur): ?>

                        <option
                            value="<?= $auteur['id'] ?>"
                            <?= $auteur['id'] == $projetAModifier['creatif'] ? 'selected' : '' ?>>

                            <?= $auteur['pseudo'] ?>

                        </option>

                    <?php endforeach; ?>

                </select>


                <label>
                    Tags
                    <span style="font-weight:400;font-size:.8rem;color:#4a3a5a">
                        (facultatif)
                    </span>
                </label>

                <!-- Les tags déjà liés au projet ($tagsProjet) sont pré-cochés :
                     on vérifie leur présence dans array_column($tagsProjet, 'id'). -->
                <div class="ct-tag-choice">

                    <?php foreach ($tags as $tag): ?>

                        <label>

                            <input
                                type="checkbox"
                                name="tags[]"
                                value="<?= $tag['id'] ?>"
                                <?= in_array(
                                    $tag['id'],
                                    array_column($tagsProjet, 'id')
                                ) ? 'checked' : '' ?>>

                            <?= $tag['nom'] ?>

                        </label>

                    <?php endforeach; ?>

                </div>


                <div>

                    <!-- Enregistrer les modifications -->
                    <input
                        class="ct-btn ct-btn--primary"
                        type="submit"
                        value="Enregistrer">

                    <!-- Annuler : simple lien retour vers le détail du projet (pas de POST). -->
                    <a
                        href="projets/<?= $projetAModifier['id'] ?>/<?= slugify($projetAModifier['titre']) ?>.html"
                        class="ct-btn ct-btn--ghost">

                        Annuler

                    </a>

                </div>

            </form>

        </div>


        <!-- Sidebar avec les créa'tifs et les tags. -->
        <div class="col-lg-4 py-3">

            <?php require_once '../app/views/templates/partials/_auteurs.php'; ?>

            <?php require_once '../app/views/templates/partials/_tags.php'; ?>

        </div>

    </div>

</div>
