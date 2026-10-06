<?php

use \Core\Helpers;

/** @var string $title Zone dynamique : titre de la page (défini par le contrôleur) */
?>
<!-- Contenu du <head> (la balise <head> est dans default.php). -->

<!-- <base> permet d'utiliser des chemins relatifs (images/..., css/...) partout
     dans les vues, quelle que soit l'URL "propre" affichée dans le navigateur. -->
<base href="<?= PUBLIC_BASE_URL ?>">
<meta charset="utf-8" />
<meta
    name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<meta name="description" content="CREA'TIFS - portfolio capillaire, liste des projets" />
<meta name="author" content="" />

<title><?= Helpers\escape($title) ?> - CREA'TIFS</title>

<!-- Bootstrap core CSS -->
<link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />

<!-- Styles CREA'TIFS -->
<link href="css/creatifs.css" rel="stylesheet" />
