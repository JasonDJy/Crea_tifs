<?php

use \Core\Helpers;

// Vue "Page introuvable" (404), appelée par PagesController\notFoundAction().

/** @var string $message */
?>
<div class="container" style="margin-top: 2.5rem">

    <h1>Page introuvable</h1>

    <p><?= Helpers\escape($message) ?></p>

    <a href="./" class="ct-btn ct-btn--primary">Retour aux projets</a>

</div>
