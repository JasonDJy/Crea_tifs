<?php

/** @var PDO $connexion Disponible ici car ce partial est inclus depuis une vue elle-même incluse par un contrôleur */

use \App\Controllers\CreatifsController;
use \App\Controllers\TagsController;
?>
<!--
    Colonne latérale (sidebar) : deux zones dynamiques, chacune alimentée
    par l'action "indexAside" de son contrôleur.
-->
<div class="col-lg-4">

    <!-- Créa'tifs (CreatifsController\indexAsideAction) -->
    <?php CreatifsController\indexAsideAction($connexion); ?>

    <!-- Tags (TagsController\indexAsideAction) -->
    <?php TagsController\indexAsideAction($connexion); ?>

</div>
