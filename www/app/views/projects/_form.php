<?php

use \Core\Helpers;

// Partial "formulaire de projet" : réutilisé par l'ajout (addForm.php)
// et par la modification (editForm.php).
// Variables attendues (définies par la vue appelante) :
//   $formAction : URL (route) vers laquelle le formulaire est envoyé
//   $isEdit     : true pour la modification, false pour l'ajout
//   $values     : valeurs des champs (title, text, creatif, tagIds)
//   $errors     : messages d'erreur à afficher
//   $creatifs   : tous les créa'tifs (pour le <select>)
//   $tags       : tous les tags (pour les cases à cocher)
//   $cancelUrl  : lien du bouton "Annuler" (modification uniquement)

/** @var string $formAction */
/** @var bool $isEdit */
/** @var array $values */
/** @var array $errors */
/** @var array $creatifs */
/** @var array $tags */
?>
<!-- Messages d'erreur de validation. -->
<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= Helpers\escape($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<!-- enctype multipart : obligatoire car le formulaire envoie un fichier (image). -->
<form
    action="<?= $formAction ?>"
    method="post"
    enctype="multipart/form-data"
    class="ct-form-card">

    <label for="title">Titre du projet</label>

    <input
        type="text"
        name="title"
        id="title"
        class="form-control"
        maxlength="45"
        value="<?= Helpers\escape($values['title']) ?>"
        placeholder="<?= $isEdit ? '' : 'Ex : Frange Kamikaze' ?>">


    <label for="text">Description</label>

    <textarea
        id="text"
        name="text"
        class="form-control"
        rows="5"
        placeholder="<?= $isEdit ? '' : "Racontez l'histoire (courageuse) de ce projet..." ?>"><?= Helpers\escape($values['text']) ?></textarea>


    <label for="creatif-file">Photo du résultat</label>

    <div class="ct-dropzone">

        <?= $isEdit
            ? '✂️ Choisissez une nouvelle image si vous souhaitez la remplacer'
            : '✂️ Glissez une image ou choisissez-la ci-dessous' ?>

        <!-- Champ facultatif : en modification, l'image actuelle est conservée si aucun fichier n'est choisi. -->
        <input
            type="file"
            class="form-control-file"
            id="creatif-file"
            name="image"
            accept="image/*">

    </div>


    <label for="category">Créa'tif</label>

    <!-- Liste déroulante générée depuis $creatifs. -->
    <select id="category" name="category_id" class="form-control">

        <?php if (!$isEdit): ?>
            <option value="" disabled <?= $values['creatif'] === 0 ? 'selected' : '' ?>>
                Sélectionnez le créa'tif
            </option>
        <?php endif; ?>

        <?php foreach ($creatifs as $creatif): ?>
            <option
                value="<?= $creatif['id'] ?>"
                <?= (int) $creatif['id'] === $values['creatif'] ? 'selected' : '' ?>>
                <?= Helpers\escape($creatif['pseudo']) ?>
            </option>
        <?php endforeach; ?>

    </select>


    <label>
        Tags
        <span style="font-weight:400;font-size:.8rem;color:#4a3a5a">(facultatif)</span>
    </label>

    <!-- Cases à cocher envoyées sous forme de tableau "tags[]". -->
    <div class="ct-tag-choice">
        <?php foreach ($tags as $tag): ?>
            <label>
                <input
                    type="checkbox"
                    name="tags[]"
                    value="<?= $tag['id'] ?>"
                    <?= in_array((int) $tag['id'], $values['tagIds'], true) ? 'checked' : '' ?>>
                <?= Helpers\escape($tag['nom']) ?>
            </label>
        <?php endforeach; ?>
    </div>


    <div>

        <input class="ct-btn ct-btn--primary" type="submit" value="Enregistrer">

        <?php if ($isEdit): ?>
            <!-- Annuler : simple lien retour vers le détail du projet (pas de POST). -->
            <a href="<?= $cancelUrl ?>" class="ct-btn ct-btn--ghost">Annuler</a>
        <?php else: ?>
            <input class="ct-btn ct-btn--ghost" type="reset" value="Réinitialiser">
        <?php endif; ?>

    </div>

</form>
