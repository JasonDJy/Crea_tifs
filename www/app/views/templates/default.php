<!--
    Template "squelette" de toutes les pages : aucune logique, il assemble
    les partials. Zones dynamiques : $title (dans _head.php) et $content
    (dans _main.php), préparées par le contrôleur appelé par le routeur.
-->
<!DOCTYPE html>
<html lang="fr">

<head>

    <!-- Métadonnées, titre et feuilles de style. -->
    <?php include '../app/views/templates/partials/_head.php'; ?>

</head>

<body>

    <!-- Navigation principale. -->
    <?php include '../app/views/templates/partials/_nav.php'; ?>

    <!-- Bandeau d'en-tête. -->
    <?php include '../app/views/templates/partials/_hero.php'; ?>

    <!-- Contenu principal de la page ($content). -->
    <?php include '../app/views/templates/partials/_main.php'; ?>

    <!-- Pied de page. -->
    <?php include '../app/views/templates/partials/_footer.php'; ?>

    <!-- Scripts JavaScript. -->
    <?php include '../app/views/templates/partials/_scripts.php'; ?>

</body>

</html>
