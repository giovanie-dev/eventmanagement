<?php
session_start();

$message = '';
$firstname = '';
$lastname = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les informations du formulaire.
    $firstname = is_string($_POST['firstname'] ?? null)
        ? trim($_POST['firstname'])
        : '';

    $lastname = is_string($_POST['lastname'] ?? null)
        ? trim($_POST['lastname'])
        : '';

    $email = is_string($_POST['email'] ?? null)
        ? trim($_POST['email'])
        : '';

    // Ne pas transformer le mot de passe.
    $pass = is_string($_POST['pass'] ?? null)
        ? $_POST['pass']
        : '';

    // Accepter les lettres, accents, espaces, tirets et apostrophes.
    $namePattern = "/^\p{L}[\p{L}\p{M}]*(?:[ '’-][\p{L}\p{M}]+)*$/u";

    // Compter les caractères, y compris les lettres accentuées.
    $nameLength = static function (string $name): int {
        $length = preg_match_all('/./us', $name);

        return $length === false ? 0 : $length;
    };

    // Vérifier les données avant de contacter la base.
    if (
        $firstname === '' ||
        $lastname === '' ||
        $email === '' ||
        $pass === ''
    ) {
        $message = 'Tous les champs doivent être renseignés.';
    } elseif (!preg_match($namePattern, $firstname)) {
        $message = 'Le prénom doit contenir uniquement des lettres, des espaces, des tirets ou des apostrophes.';
    } elseif ($nameLength($firstname) > 45) {
        $message = 'Le prénom ne doit pas dépasser 45 caractères.';
    } elseif (!preg_match($namePattern, $lastname)) {
        $message = 'Le nom doit contenir uniquement des lettres, des espaces, des tirets ou des apostrophes.';
    } elseif ($nameLength($lastname) > 45) {
        $message = 'Le nom ne doit pas dépasser 45 caractères.';
    } elseif (
        strlen($email) > 100 ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        $message = 'L’adresse e-mail est invalide ou dépasse 100 caractères.';
    } elseif (!preg_match('/^.{8,}$/us', $pass)) {
        $message = 'Le mot de passe doit contenir au moins 8 caractères.';
    } elseif (strlen($pass) > 72) {
        $message = 'Le mot de passe est trop long : 72 octets maximum, les caractères accentués pouvant en utiliser plusieurs.';
    }

    if ($message === '') {
        require_once __DIR__ . '/config/connect.php';

        try {
            // Vérifier si l’adresse e-mail est déjà utilisée.
            $data = $db->prepare(
                'SELECT COUNT(*)
                 FROM users
                 WHERE email_user = :email'
            );

            $data->execute([
                'email' => $email,
            ]);

            if ((int) $data->fetchColumn() > 0) {
                $message = 'Cette adresse e-mail est déjà utilisée.';
            } else {
                // Bcrypt produit un hachage compatible avec pass_user VARCHAR(72).
                $hash = password_hash($pass, PASSWORD_BCRYPT);

                $req = $db->prepare(
                    'INSERT INTO users (
                        firstname_user,
                        lastname_user,
                        email_user,
                        pass_user,
                        fk_id_role
                    ) VALUES (
                        :firstname,
                        :lastname,
                        :email,
                        :pass,
                        :id_role
                    )'
                );

                $req->execute([
                    'firstname' => $firstname,
                    'lastname' => $lastname,
                    'email' => $email,
                    'pass' => $hash,
                    'id_role' => 3,
                ]);

                header('Location: signin.php');
                exit();
            }
        } catch (PDOException $e) {
            // Ne pas afficher les détails techniques aux visiteurs.
            error_log(
                'Erreur inscription : SQLSTATE ' . $e->getCode()
                . ', code ' . ($e->errorInfo[1] ?? 'inconnu')
            );

            if (
                ($e->errorInfo[1] ?? null) === 1062 &&
                strpos($e->getMessage(), 'email_user_UNIQUE') !== false
            ) {
                $message = 'Cette adresse e-mail est déjà utilisée.';
            } else {
                $message = 'L’inscription est momentanément indisponible. Réessayez plus tard.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <title>Inscription au site</title>

        <link rel="stylesheet" href="CSS/style.css" />
    </head>

    <body class="register-page">
        <?php require __DIR__ . '/nav.php'; ?>

        <main id="main-content">
            <div class="register-container">
                <h1>Inscription</h1>

                <?php if ($message !== ''): ?>
                    <p class="message" role="alert">
                        <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
                    </p>
                <?php endif; ?>

                <form method="post">
                    <label for="firstname">Prénom :</label>
                    <input
                        type="text"
                        id="firstname"
                        name="firstname"
                        value="<?= htmlspecialchars($firstname, ENT_QUOTES, 'UTF-8') ?>"
                        autocomplete="given-name"
                        maxlength="45"
                        required
                    />

                    <label for="lastname">Nom :</label>
                    <input
                        type="text"
                        id="lastname"
                        name="lastname"
                        value="<?= htmlspecialchars($lastname, ENT_QUOTES, 'UTF-8') ?>"
                        autocomplete="family-name"
                        maxlength="45"
                        required
                    />

                    <label for="email">E-mail :</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>"
                        autocomplete="email"
                        maxlength="100"
                        required
                    />

                    <label for="pass">Mot de passe :</label>
                    <input
                        type="password"
                        id="pass"
                        name="pass"
                        autocomplete="new-password"
                        minlength="8"
                        aria-describedby="password-help"
                        required
                    />

                    <p id="password-help">Au moins 8 caractères.</p>

                    <button type="submit">S’inscrire !</button>
                </form>

                <p class="login-link">
                    Déjà un compte ?
                    <a href="signin.php">Se connecter</a>
                </p>
            </div>
        </main>
    </body>
</html>