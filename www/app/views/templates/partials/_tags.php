<?php

use \Core\Helpers;

// Zone dynamique "tags" de la sidebar, alimentée par
// TagsController\indexAsideAction().

/** @var array $tags */
?>
<div class="ct-side-card">

    <h5 class="ct-side-card__head">Tags</h5>

    <div class="ct-side-card__body">

        <ul class="ct-tags">

            <!-- Chaque tag mène à ses projets : /tags/id/slug.html -->
            <?php foreach ($tags as $tag): ?>
                <li>
                    <a
                        class="ct-tag"
                        href="tags/<?= $tag['id'] ?>/<?= Helpers\slugify($tag['nom']) ?>.html">
                        <?= Helpers\escape($tag['nom']) ?>
                    </a>
                </li>
            <?php endforeach; ?>

        </ul>

    </div>

</div>
