<?php
session_start();

$message = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = is_string($_POST['email'] ?? null)
        ? trim($_POST['email'])
        : '';

    $pass = is_string($_POST['pass'] ?? null)
        ? $_POST['pass']
        : '';

    if ($email === '' || $pass === '') {
        $message = 'Tous les champs doivent être remplis.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Adresse e-mail invalide.';
    } else {
        require_once __DIR__ . '/config/connect.php';

        try {
            $sql = $db->prepare(
                'SELECT id_user, firstname_user, lastname_user,
                        pass_user, fk_id_role
                 FROM users
                 WHERE email_user = :email'
            );

            $sql->execute([
                'email' => $email,
            ]);

            $user = $sql->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($pass, $user['pass_user'])) {
                // Renouveler la session après la connexion.
                session_regenerate_id(true);

                $_SESSION['email'] = $email;
                $_SESSION['firstname'] = $user['firstname_user'];
                $_SESSION['lastname'] = $user['lastname_user'];
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['role'] = $user['fk_id_role'];

                header('Location: profile.php');
                exit();
            } else {
                $message = 'E-mail ou mot de passe incorrect.';
            }
        } catch (PDOException $e) {
            error_log('Erreur de connexion utilisateur : ' . $e->getCode());
            $message = 'La connexion est momentanément indisponible. Réessayez plus tard.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>Connexion au site</title>

        <link rel="stylesheet" href="CSS/style.css" />
    </head>

    <body class="login-page">
        <div class="login-container">
            <h1>Connexion</h1>

            <?php if ($message !== ''): ?>
                <p class="message" role="alert">
                    <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                </p>
            <?php endif; ?>

            <form method="post">
                <label for="email">E-mail :</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                    autocomplete="username"
                    maxlength="100"
                    required
                />

                <label for="pass">Mot de passe :</label>
                <input
                    type="password"
                    id="pass"
                    name="pass"
                    autocomplete="current-password"
                    required
                />

                <button type="submit">Se connecter !</button>
            </form>

            <a href="register.php" class="register-link">
                Pas encore inscrit ?
            </a>
        </div>
    </body>
</html>