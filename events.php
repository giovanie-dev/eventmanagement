<?php
session_start();
require 'config/connect.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: index.php');
    exit();
}

$sql = $db->prepare('SELECT * FROM events');
$sql->execute();

$results = $sql->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="theme-color" content="#3f1924" />
        <title>Explorer les événements — Év.</title>
        <link rel="stylesheet" href="CSS/style.css" />
    </head>
    <body>
        <?php require 'nav.php'; ?>
        <main id="main-content">
            <div class="page-heading">
                <div><h1>Les événements</h1></div>
                <a class="text-link" href="profile.php">Mes réservations <?= eventIcon(
        'ticket',
    ) ?></a>
            </div>
            <div class="events-toolbar">
                <label class="sr-only" for="catalog-search"
                    >Filtrer les événements par titre, lieu ou description</label
                ><input
                    id="catalog-search"
                    type="search"
                    data-live-search
                    placeholder="Rechercher un événement, un lieu…"
                    value="<?= htmlspecialchars(
        $_GET['q'] ?? '',
    ) ?>"
                /><span data-event-count><?= count($results) ?> événement<?= count($results) > 1
     ? 's'
     : '' ?></span>
            </div>
            <section class="events-grid" aria-label="Événements disponibles">
                <?php foreach ($results as $row): ?>
                <article class="event-card" data-event>
                    <div class="event-date"><?= eventIcon('calendar') ?><span><?= !empty($row['date_event'])
    ? date('d.m.Y', strtotime($row['date_event']))
    : 'Date à préciser' ?></span></div>
                    <h2><?= htmlspecialchars($row['title_event']) ?></h2>
                    <div class="event-description"><?= nl2br(
                htmlspecialchars($row['description_event']),
            ) ?></div>
                    <div class="event-place"><?= eventIcon('pin') ?><span><?= htmlspecialchars(
    $row['place_event'],
) ?></span></div>
                    <a href="reservation.php?id_event=<?= (int) $row[
                'id_event'
            ] ?>"
                        >Réserver ma place <?= eventIcon('arrow') ?></a
                    >
                </article>
                <?php endforeach; ?>
                <div class="empty-state" data-no-results hidden>
                    <?= eventIcon(
            'search',
        ) ?>
                    <h2>Aucun événement trouvé</h2>
                    <p>Essayez un autre titre ou un autre lieu.</p>
                </div>
            </section>
        </main>
    </body>
</html>
