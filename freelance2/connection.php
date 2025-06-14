<?php
session_start();

$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
if (isset($_GET['id'])) {
    $redirect .= '?id=' . urlencode($_GET['id']);
}


// Connexion à la base de données
$host = 'localhost';
$dbname = 'freelance';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur base de données : ' . $e->getMessage());
}

$message = '';
$redirectPage = $_GET['redirect'] ?? '';
$redirectId = $_GET['id'] ?? '';

// Si déjà connecté → rediriger immédiatement
if (isset($_SESSION['user_email']) && $redirectPage) {
    header("Location: $redirectPage?id=" . urlencode($redirectId));
    exit;
}

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email']), FILTER_VALIDATE_EMAIL);
    $password = trim($_POST['password']);

    if (!$email || !$password) {
        $message = "Veuillez fournir un email valide et un mot de passe.";
    } else {
        // Chercher l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['utilisateur_id'] = $user['id']; // utile pour plus tard

            // Redirection après succès
            if ($redirectPage) {
                header("Location: $redirectPage?id=" . urlencode($redirectId));
                exit;
            } else {
                header("Location: Page_acceuil_w4a.html");
                exit;
            }
        } else {
            $message = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Connexion</title>
    <link rel="stylesheet" href="connection.css">
</head>
<body>

<a href="Page_acceuil_w4a.html" class="home-btn" style="text-decoration: none;">
    <button type="button">← Accueil</button>
</a>

<h2 style="text-align:center;">Connexion</h2>

<?php if ($message): ?>
    <p class="message <?= strpos($message, 'succès') !== false ? 'success' : '' ?>">
        <?= htmlspecialchars($message) ?>
    </p>
<?php endif; ?>

<?php if (!isset($_SESSION['user_email'])): ?>
<form method="post" action="?redirect=<?= urlencode($redirectPage) ?>&id=<?= urlencode($redirectId) ?>">
    <input type="email" name="email" placeholder="Email" required />
    <input type="password" name="password" placeholder="Mot de passe" required />
    <button type="submit">Se connecter</button>
</form>
<?php else: ?>
    <p style="text-align:center;">Vous êtes connecté en tant que <strong><?= htmlspecialchars($_SESSION['user_email']) ?></strong>.</p>
    <form method="post" action="logout.php" style="text-align:center;">
        <button type="submit">Se déconnecter</button>
    </form>
<?php endif; ?>

</body>
</html>
