<?php
$role = $_SESSION['role'] ?? null;
$currentPage = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
$firstName = $_SESSION['firstname'] ?? '';
if (!function_exists('eventIcon')) {
    function eventIcon($name)
    {
        $paths = [
            'home' => '<path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z"/>',
            'explore' => '<circle cx="12" cy="12" r="9"/><path d="m16 8-2.5 5.5L8 16l2.5-5.5z"/>',
            'ticket' =>
                '<path d="M4 5h16v5a2 2 0 0 0 0 4v5H4v-5a2 2 0 0 0 0-4z"/><path d="M14 5v3m0 3v2m0 3v3"/>',
            'user' => '<circle cx="12" cy="8" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2"/>',
            'plus' => '<path d="M12 5v14M5 12h14"/>',
            'settings' => '<path d="m12 3 8 4v10l-8 4-8-4V7z"/><circle cx="12" cy="12" r="3"/>',
            'logout' => '<path d="M9 4H4v16h5m6-12 4 4-4 4m-8-4h12"/>',
            'search' => '<circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 4 4"/>',
            'arrow' => '<path d="M5 12h14m-6-6 6 6-6 6"/>',
            'music' =>
                '<path d="M9 18V5l11-2v13M9 8l11-2"/><ellipse cx="6" cy="18" rx="3" ry="3"/><ellipse cx="17" cy="16" rx="3" ry="3"/>',
            'calendar' =>
                '<rect x="3" y="5" width="18" height="16" rx="3"/><path d="M7 3v4m10-4v4M3 11h18m-12 4h.01m4 0h.01"/>',
            'spark' => '<path d="m12 3 2.5 6.5L21 12l-6.5 2.5L12 21l-2.5-6.5L3 12l6.5-2.5z"/>',
            'pin' =>
                '<path d="M19 10c0 5-7 11-7 11S5 15 5 10a7 7 0 0 1 14 0Z"/><circle cx="12" cy="10" r="2"/>',
        ];
        return '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.65" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' .
            ($paths[$name] ?? $paths['spark']) .
            '</svg>';
    }
}
$navItems = [
    ['index.php', 'home', 'Accueil'],
    ['events.php', 'explore', 'Explorer'],
    [$role ? 'profile.php' : 'signin.php', 'ticket', 'Mon espace'],
];
if ($role == 2 || $role == 1) {
    $navItems[] = ['createevent.php', 'plus', 'Organiser'];
}
if ($role == 1) {
    $navItems[] = ['admin.php', 'settings', 'Gérer'];
}
?>
<a class="skip-link" href="#main-content">Aller au contenu</a>
<aside class="sidebar" aria-label="Navigation principale">
    <a class="brand" href="index.php" aria-label="Événements, accueil">é<span>v.</span></a>
    <span class="sidebar-divider"></span>
    <nav>
        <ul class="nav-list">
            <?php foreach ($navItems as [$href, $icon, $label]): ?>
            <li>
                <a class="nav-item <?= $currentPage === $href
            ? 'is-active'
            : '' ?>" href="<?= $href ?>" <?= $currentPage === $href
    ? 'aria-current="page"'
    : '' ?>
                    ><?= eventIcon($icon) ?><span><?= $label ?></span></a
                >
            </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <div class="sidebar-bottom">
        <a class="nav-item" href="<?= $role ? 'logout.php' : 'signin.php' ?>"><?= eventIcon(
    $role ? 'logout' : 'user',
) ?><span><?= $role ? 'Quitter' : 'Connexion' ?></span></a>
    </div>
</aside>
<header class="app-header">
    <div class="greeting"><p>Bonjour<?= $firstName
        ? ', <strong>' . htmlspecialchars($firstName) . '</strong>'
        : ', bienvenue' ?></p></div>
    <form class="header-search" action="events.php" method="get" role="search">
        <?= eventIcon(
            'search',
        ) ?><label class="sr-only" for="event-search">Rechercher un événement</label
        ><input
            id="event-search"
            type="search"
            name="q"
            placeholder="Un événement, une ville…"
            value="<?= htmlspecialchars(
    $_GET['q'] ?? '',
) ?>"
        /><button type="submit" aria-label="Lancer la recherche">↵</button>
    </form>
    <div class="header-account">
        <?php if (
            !$role
        ): ?><a class="header-signin" href="signin.php">Se connecter</a
        ><a class="button button-small" href="register.php">Créer un compte <?= eventIcon(
    'arrow',
) ?></a>
        <?php else: ?><a class="account-chip" href="profile.php"
            ><span class="avatar"><?= htmlspecialchars(
    mb_substr($firstName ?: 'M', 0, 1),
) ?></span><span>Mon compte</span><?= eventIcon('arrow') ?></a
        ><?php endif; ?>
    </div>
</header>
<script src="CSS/app.js" defer></script>
