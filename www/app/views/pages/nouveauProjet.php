
<?php

// -----------------------------------------------------------------------------
// Vue "Ajouter un projet" (routes /projects/add/form.html et /projects/add/insert.html,
// toutes deux gérées par nouveauProjetAction()). $auteurs et $tags servent
// à remplir le <select> et les cases à cocher du formulaire.
// -----------------------------------------------------------------------------

/** @var array $auteurs */
/** @var array $tags */

?>

<div class="container" style="margin-top: 2.5rem">

    <div class="row">

        <!-- Formulaire d'ajout d'un projet. -->
        <div class="col-lg-8 py-3">

            <h1 class="mb-4">
                Ajouter un projet
            </h1>

            <!-- L'action pointe vers la route "insert.html" ; enctype
                 multipart obligatoire car le formulaire envoie un fichier (image). -->
            <form
                action="projects/add/insert.html"
                method="post"
                enctype="multipart/form-data"
                class="ct-form-card">

                <label for="title">
                    Titre du projet
                </label>

                <input
                    type="text"
                    name="title"
                    id="title"
                    class="form-control"
                    placeholder="Ex : Frange Kamikaze">


                <label for="text">
                    Description
                </label>

                <textarea
                    id="text"
                    name="text"
                    class="form-control"
                    rows="5"
                    placeholder="Racontez l'histoire (courageuse) de ce projet..."></textarea>


                <label for="creatif-file">
                    Photo du résultat
                </label>

                <div class="ct-dropzone">

                    ✂️ Glissez une image ou choisissez-la ci-dessous

                    <input
                        type="file"
                        class="form-control-file"
                        id="creatif-file"
                        name="image">

                </div>


                <label for="category">
                    Créa'tif
                </label>

                <!-- Liste déroulante générée dynamiquement depuis $auteurs. -->
                <select
                    id="category"
                    name="category_id"
                    class="form-control">

                    <option value="" disabled selected>
                        Sélectionnez le créa'tif
                    </option>

                    <?php foreach ($auteurs as $auteur): ?>

                        <option value="<?= $auteur['id'] ?>">
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

                <!-- Cases à cocher générées depuis $tags ; envoyées comme
                     tableau "tags[]" et lues côté contrôleur via $_POST['tags']. -->
                <div class="ct-tag-choice">

                    <?php foreach ($tags as $tag): ?>

                        <label>

                            <input
                                type="checkbox"
                                name="tags[]"
                                value="<?= $tag['id'] ?>">

                            <?= $tag['nom'] ?>

                        </label>

                    <?php endforeach; ?>

                </div>


                <div>

                    <input
                        class="ct-btn ct-btn--primary"
                        type="submit"
                        value="Enregistrer">

                    <input
                        class="ct-btn ct-btn--ghost"
                        type="reset"
                        value="Réinitialiser">

                </div>

            </form>

        </div>


        <!-- Sidebar avec les créa'tifs et les tags (réutilise les mêmes partials
             que la page d'accueil, d'où le découpage en _auteurs.php / _tags.php). -->
        <div class="col-lg-4 py-3">

            <?php require_once '../app/views/templates/partials/_auteurs.php'; ?>

            <?php require_once '../app/views/templates/partials/_tags.php'; ?>

        </div>

    </div>

</div>
