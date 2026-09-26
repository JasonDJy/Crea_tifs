
<!--
    Template "squelette" de toutes les pages du site.
    Il ne contient aucune logique : il assemble simplement les partials
    (fichiers _xxx.php) dans le bon ordre. C'est le dernier fichier inclus
    par public/index.php, une fois que le routeur/contrôleur a préparé
    les variables (ex: $projects, $project...) utilisées par ces partials.
-->
<!DOCTYPE html>
<html lang="fr">

<head>

    <!-- Informations et feuilles de style. -->
    <?php include '../app/views/templates/partials/_head.php'; ?>

</head>

<body>

    <!-- Navigation principale. -->
    <?php include '../app/views/templates/partials/_nav.php'; ?>

    <!-- En-tête du site. -->
    <?php include '../app/views/templates/partials/_header.php'; ?>

    <!-- Contenu principal de la page. -->
    <?php include '../app/views/templates/partials/_main.php'; ?>

    <!-- Pied de page. -->
    <?php include '../app/views/templates/partials/_footer.php'; ?>

    <!-- Scripts JavaScript. -->
    <?php include '../app/views/templates/partials/_scripts.php'; ?>

</body>

</html>
