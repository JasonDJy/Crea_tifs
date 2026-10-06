<?php

use \Core\Helpers;

// Zone dynamique "créa'tifs" de la sidebar, alimentée par
// CreatifsController\indexAsideAction().

/** @var array $creatifs */
?>
<div class="ct-side-card">

    <h5 class="ct-side-card__head">Les créa'tifs</h5>

    <div class="ct-side-card__body">

        <ul class="ct-creatif-list">

            <!-- Une ligne "avatar + pseudo" par créa'tif, vers sa page : /creatifs/id.html -->
            <?php foreach ($creatifs as $creatif): ?>
                <li>
                    <img
                        class="ct-avatar"
                        src="images/<?= Helpers\escape($creatif['image']) ?>"
                        alt="">

                    <a href="creatifs/<?= $creatif['id'] ?>.html">
                        <?= Helpers\escape($creatif['pseudo']) ?>
                    </a>
                </li>
            <?php endforeach; ?>

        </ul>

    </div>

</div>
