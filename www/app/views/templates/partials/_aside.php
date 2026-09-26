
<!--
    Colonne latérale (sidebar) de la page d'accueil : regroupe elle-même
    deux partials, un par bloc de contenu, pour rester simple à maintenir.
-->
<div class="col-lg-4">

    <!-- Liste des créa'tifs avec accès à leur page. -->
    <?php require_once '../app/views/templates/partials/_auteurs.php'; ?>

    <!-- Liste des tags permettant de filtrer les projets. -->
    <?php require_once '../app/views/templates/partials/_tags.php'; ?>

</div>
