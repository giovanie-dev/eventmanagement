<?php
session_start();
$exploreLink = isset($_SESSION['id_user']) ? 'events.php' : 'signin.php';
$connected = isset($_SESSION['id_user']);
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="theme-color" content="#3f1924" />
        <meta
            name="description"
            content="Trouvez votre prochaine sortie : concerts, culture et plein air. Découvrez les événements et réservez votre place."
        />
        <title>Év. — Réservation d’événements</title>
        <link rel="stylesheet" href="CSS/style.css?v=4" />
    </head>
    <body class="dashboard-page refined-home">
        <?php require 'nav.php'; ?>
        <main id="main-content" class="dashboard-main">
            <div class="discovery-heading">
                <div><h1>Découvrez les événements</h1></div>
            </div>
            <div class="spotlight-layout">
                <section class="spotlight" aria-labelledby="spotlight-title">
                    <img
                        class="spotlight-photo"
                        src="img/concert.jpg"
                        alt="Une foule réunie devant une scène de concert éclairée de lumières violettes"
                        fetchpriority="high"
                    />
                    <div class="spotlight-shade"></div>

                    <div class="spotlight-copy">
                        <h2 id="spotlight-title">Réservez votre<br />prochain événement.</h2>
                        <p>
                            Concerts, expositions et activités de plein air.<br
                                class="desktop-break"
                            />
                            Consultez les événements et réservez votre place.
                        </p>
                        <a class="button spotlight-button" href="<?= $exploreLink ?>"
                            >Explorer les événements <?= eventIcon(
    'arrow',
) ?></a
                        >
                    </div>
                </section>
                <aside class="discovery-aside">
                    <section class="quick-discover" aria-labelledby="quick-title">
                        <h2 id="quick-title">Catégories</h2>
                        <div class="quick-list">
                            <button type="button" data-category="music" aria-pressed="false">
                                <span class="quick-icon"><?= eventIcon(
                'music',
            ) ?></span
                                ><span><strong>Musique & concerts</strong></span
                                ><span class="quick-arrow">↗</span></button
                            ><button type="button" data-category="culture" aria-pressed="false">
                                <span class="quick-icon"><?= eventIcon(
    'explore',
) ?></span
                                ><span><strong>Art & culture</strong></span
                                ><span class="quick-arrow">↗</span></button
                            ><button type="button" data-category="sport" aria-pressed="false">
                                <span class="quick-icon"><?= eventIcon(
    'pin',
) ?></span
                                ><span><strong>Sport & plein air</strong></span
                                ><span class="quick-arrow">↗</span>
                            </button>
                        </div>
                    </section>
                    <section class="account-invitation">
                        <div class="invitation-icon"><?= eventIcon(
                'ticket',
            ) ?></div>
                        <div>
                            <h2><?= $connected
    ? 'Mes réservations'
    : 'Créer un compte' ?></h2>
                            <p><?= $connected
    ? 'Consultez les événements réservés.'
    : 'Inscrivez-vous pour réserver un événement.' ?></p>
                        </div>
                        <a href="<?= $connected
    ? 'profile.php'
    : 'register.php' ?>" aria-label="<?= $connected
    ? 'Voir mes réservations'
    : 'Créer mon compte' ?>"><?= eventIcon('arrow') ?></a>
                    </section>
                </aside>
            </div>
            <section class="category-section" aria-labelledby="categories-title">
                <div class="category-section-heading">
                    <div><h2 id="categories-title">Explorer par catégorie</h2></div>
                    <div class="category-filters" role="group" aria-label="Filtrer les univers">
                        <button
                            class="filter-chip is-active"
                            data-category="all"
                            aria-pressed="true"
                        >
                            Tout</button
                        ><button class="filter-chip" data-category="music" aria-pressed="false">
                            Musique</button
                        ><button class="filter-chip" data-category="culture" aria-pressed="false">
                            Culture</button
                        ><button class="filter-chip" data-category="sport" aria-pressed="false">
                            Plein air
                        </button>
                    </div>
                </div>
                <div class="photo-category-grid">
                    <a class="photo-category" data-experience="music" href="<?= $exploreLink ?>"
                        ><img
                            src="img/live.jpg"
                            alt="Les mains levées du public pendant un concert"
                            loading="lazy"
                        /><span class="photo-category-tag">MUSIQUE</span>
                        <div><h3>Concerts & live</h3></div>
                        <span class="photo-category-arrow">↗</span></a
                    >
                    <a class="photo-category" data-experience="culture" href="<?= $exploreLink ?>"
                        ><img
                            src="img/culture.jpg"
                            alt="Des visiteurs découvrent les tableaux d’une exposition"
                            loading="lazy"
                        /><span class="photo-category-tag">CULTURE</span>
                        <div><h3>Art & découvertes</h3></div>
                        <span class="photo-category-arrow">↗</span></a
                    >
                    <a class="photo-category" data-experience="sport" href="<?= $exploreLink ?>"
                        ><img
                            src="img/outdoor.jpg"
                            alt="Une chaîne de montagnes sous un ciel lumineux"
                            loading="lazy"
                        /><span class="photo-category-tag">PLEIN AIR</span>
                        <div><h3>Sport & évasion</h3></div>
                        <span class="photo-category-arrow">↗</span></a
                    >
                </div>
            </section>
            <footer class="refined-footer">
                <a class="footer-wordmark" href="index.php">év.</a
                ><a href="<?= $exploreLink ?>">Voir les événements <?= eventIcon(
    'arrow',
) ?></a>
            </footer>
        </main>
    </body>
</html>
