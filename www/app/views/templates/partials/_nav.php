<!--
    Barre de navigation, affichée en haut de toutes les pages (2 liens statiques).
    href="" pointe vers <base href> (voir _head.php), donc vers l'accueil.
-->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top ct-navbar">
    <div class="container">

        <!-- Logo / retour à l'accueil -->
        <a class="navbar-brand" href="">
            <svg class="ct-scissors" viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="6" cy="6" r="2.6"></circle>
                <circle cx="6" cy="18" r="2.6"></circle>
                <line x1="20" y1="3" x2="8" y2="15.5"></line>
                <line x1="8" y1="8.5" x2="20" y2="21"></line>
            </svg>
            CREA'TIFS
        </a>

        <!-- Bouton "burger" en mobile (géré par Bootstrap). -->
        <button
            class="navbar-toggler"
            type="button"
            data-toggle="collapse"
            data-target="#navbarResponsive"
            aria-controls="navbarResponsive"
            aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarResponsive">

            <ul class="navbar-nav ml-auto align-items-lg-center">

                <!-- Liste des projets : route "/" -->
                <li class="nav-item active">
                    <a class="nav-link" href="">Les projets</a>
                </li>

                <!-- Formulaire d'ajout : route /projects/add/form.html -->
                <li class="nav-item">
                    <a class="ct-btn ct-btn--primary ct-btn--sm" href="projects/add/form.html">

                        <svg
                            class="ct-scissors"
                            style="width:16px;height:16px"
                            viewBox="0 0 24 24"
                            aria-hidden="true">
                            <circle cx="6" cy="6" r="2.6"></circle>
                            <circle cx="6" cy="18" r="2.6"></circle>
                            <line x1="20" y1="3" x2="8" y2="15.5"></line>
                            <line x1="8" y1="8.5" x2="20" y2="21"></line>
                        </svg>

                        Nouveau projet

                    </a>
                </li>

            </ul>

        </div>

    </div>
</nav>
