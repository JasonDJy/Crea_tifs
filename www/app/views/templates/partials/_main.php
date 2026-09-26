
<?php

// -----------------------------------------------------------------------------
// Partial "central" du template : c'est ce fichier qui décide QUELLE vue
// afficher, en fonction des variables globales définies par le contrôleur
// (voir app/controllers/pagesController.php). Chaque bloc ci-dessous
// correspond à un cas possible, testé dans l'ordre, avec un `return`
// pour arrêter le fichier dès qu'un cas correspond.
// Si aucun cas particulier ne correspond, le code par défaut plus bas
// affiche la liste des projets (page d'accueil).
// -----------------------------------------------------------------------------

/** @var array $projects */
/** @var array $project */


/*
|--------------------------------------------------------------------------
| AJOUT D'UN PROJET
|--------------------------------------------------------------------------
| $nouveauProjet est mis à `true` par nouveauProjetAction().
*/

if (isset($nouveauProjet)) {

  require_once '../app/views/pages/nouveauProjet.php';

  return;
}


/*
|--------------------------------------------------------------------------
| MODIFICATION D'UN PROJET
|--------------------------------------------------------------------------
| $projetAModifier est défini par modifierProjetAction() (le projet à éditer).
*/

if (isset($projetAModifier)) {

  require_once '../app/views/pages/modifierProjet.php';

  return;
}


/*
|--------------------------------------------------------------------------
| PROJET INTROUVABLE
|--------------------------------------------------------------------------
| $projetIntrouvable passe à true si projectAction() n'a pas trouvé
| de projet correspondant à l'id demandé dans l'URL.
*/

if (!empty($projetIntrouvable)) {

  echo '<div class="container" style="margin-top: 2.5rem">';
  echo '<h1>Projet introuvable</h1>';
  echo '<p>Le projet demandé n’existe pas.</p>';
  echo '<a href="./" class="ct-btn ct-btn--primary">Retour aux projets</a>';
  echo '</div>';

  return;
}

/*
|--------------------------------------------------------------------------
| AUTEUR INTROUVABLE
|--------------------------------------------------------------------------
| Même logique que ci-dessus, mais pour un créa'tif introuvable
| (défini par authorAction()).
*/


if (!empty($auteurIntrouvable)) {

  echo '<div class="container" style="margin-top: 2.5rem">';
  echo '<h1>Créa\'tif introuvable</h1>';
  echo '<p>Le créa\'tif demandé n’existe pas.</p>';
  echo '<a href="./" class="ct-btn ct-btn--primary">Retour aux projets</a>';
  echo '</div>';

  return;
}


/*
|--------------------------------------------------------------------------
| PAGE AUTEUR
|--------------------------------------------------------------------------
| $auteur est défini par authorAction() : affiche la fiche du créa'tif
| et la liste de ses projets.
*/

if (isset($auteur)) {

  require_once '../app/views/pages/auteurs.php';

  return;
}


/*
|--------------------------------------------------------------------------
| DETAIL D'UN PROJET
|--------------------------------------------------------------------------
| $project est défini par projectAction() : affiche le détail d'un projet.
*/

if (isset($project)) {

  require_once '../app/views/pages/projets.php';

  return;
}

?>

<!--
    CAS PAR DÉFAUT : aucune des conditions ci-dessus n'a matché,
    on est donc sur la page d'accueil (liste paginée des projets),
    éventuellement filtrée par tag (variable $projects préparée
    par dashboardAction() dans les deux cas).
-->
<div class="container ct-content-wrap">

  <div class="row">

    <div class="col-lg-8">

      <!-- Une "carte" par projet de la page courante -->
      <?php foreach ($projects as $project): ?>

        <article class="ct-card">

          <div class="row">

            <div class="col-md-4">

              <!-- Vignette du projet, cliquable, menant à son détail. -->
              <!-- Le slug est recalculé à la volée avec slugify() (pas stocké en BDD). -->
              <a href="projets/<?= $project['id'] ?>/<?= slugify($project['titre']) ?>.html">

                <img
                  class="img-fluid mb-3 mb-md-0"
                  src="images/<?= $project['image'] ?>"
                  alt="<?= $project['titre'] ?>">

              </a>

            </div>

            <div class="col-md-8">

              <h3>
                <a href="projets/<?= $project['id'] ?>/<?= slugify($project['titre']) ?>.html">
                  <?= $project['titre'] ?>
                </a>
              </h3>

              <!-- Auteur + date de création formatée en jj/mm/aaaa. -->
              <p class="ct-byline">
                par
                <a href="creatifs/<?= $project['creatif'] ?>.html">
                  <?= $project['pseudo'] ?>
                </a>
                · <?= date('d/m/Y', strtotime($project['dateCreation'])) ?>
              </p>

              <!-- Aperçu du texte tronqué à 100 caractères (consigne de l'énoncé). -->
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

      <!-- Liens de pagination (Précédent / numéros / Suivant). -->
      <?php require_once '../app/views/templates/partials/_pagination.php'; ?>

    </div>

    <!-- Colonne latérale : liste des créa'tifs + liste des tags. -->
    <?php require_once '../app/views/templates/partials/_aside.php'; ?>

  </div>

</div>
