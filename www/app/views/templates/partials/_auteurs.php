
<?php

// $auteurs vient de dashboardAction() (défini pour toutes les pages
// utilisant _aside.php) : tableau de tous les créa'tifs.

/** @var array $auteurs */

?>

<div class="ct-side-card">

  <h5 class="ct-side-card__head">
    Les créa'tifs
  </h5>

  <div class="ct-side-card__body">

    <ul class="ct-creatif-list">

      <!-- Une ligne "avatar + pseudo" par créa'tif, menant à sa page dédiée. -->
      <?php foreach ($auteurs as $auteur): ?>

        <li>

          <img
            class="ct-avatar"
            src="images/<?= $auteur['image'] ?>"
            alt="">

          <a href="creatifs/<?= $auteur['id'] ?>.html">
            <?= $auteur['pseudo'] ?>
          </a>

        </li>

      <?php endforeach; ?>

    </ul>

  </div>

</div>
