<?php
session_start();

function getDbConnection() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "freelance";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection échouée : " . $conn->connect_error);
    }

    return $conn;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom        = trim($_POST['nom'] ?? '');
    $prenom     = trim($_POST['prenom'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $telephone  = trim($_POST['telephone'] ?? '');
    $motdepasse = $_POST['motdepasse'] ?? '';
    $action     = $_POST['action'] ?? '';

    if (!$nom || !$prenom || !$email || !$telephone || !$motdepasse) {
        $message = 'Tous les champs sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Email invalide.';
    } elseif (!preg_match('/^[A-Za-z0-9]{6,}$/', $motdepasse)) {
        $message = 'Le mot de passe doit contenir au moins 6 caractères alphanumériques.';
    } else {
        $conn = getDbConnection();

        // Vérifie si l'email existe déjà
        $checkStmt = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $message = "Cet email est déjà utilisé.";
            $checkStmt->close();
            $conn->close();
        } else {
            $checkStmt->close();

            $motdepasse_hash = password_hash($motdepasse, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, prenom, email, tel, mot_de_passe) VALUES (?, ?, ?, ?, ?)");

            if (!$stmt) {
                $message = 'Erreur de préparation : ' . $conn->error;
            } else {
                $stmt->bind_param("sssss", $nom, $prenom, $email, $telephone, $motdepasse_hash);

                if ($stmt->execute()) {
                    $utilisateur_id = $stmt->insert_id;
                    $_SESSION['utilisateur_id'] = $utilisateur_id;
                    $_SESSION['user_email'] = $email;

                    $stmt->close();
                    $conn->close();

                    // Redirection selon le rôle
                    if ($action === 'prestataire') {
                        header("Location: profil.php");
                    } elseif ($action === 'publier') {
                        header("Location: publier_projet.php");
                    } else {
                        header("Location: dashboard.php");
                    }
                    exit;
                } else {
                    $message = "Erreur lors de l'inscription : " . $conn->error;
                    $stmt->close();
                    $conn->close();
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Login</title>
<link rel="stylesheet" href="register.css">
</head>

<body>
<div class="container">
  <h1>Login</h1>

  <?php if($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="POST" action="register.php" id="form-register">
    <label for="nom">Nom</label>
    <input type="text" id="nom" name="nom" placeholder="Votre nom" required value="<?= htmlspecialchars($_POST['nom'] ?? '') ?>" />

    <label for="prenom">Prénom</label>
    <input type="text" id="prenom" name="prenom" placeholder="Votre prénom" required value="<?= htmlspecialchars($_POST['prenom'] ?? '') ?>" />

    <label for="email">Email</label>
    <input type="email" id="email" name="email" placeholder="exemple@domain.com" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" />

    <label for="telephone">Téléphone</label>
    <input type="tel" id="telephone" name="telephone" placeholder="Numéro de téléphone" pattern="\d+" maxlength="15" required value="<?= htmlspecialchars($_POST['telephone'] ?? '') ?>" />

    <label for="motdepasse">Mot de passe</label>
    <input type="password" id="motdepasse" name="motdepasse" placeholder="6 caractères (chiffres et lettres)" pattern="[A-Za-z0-9]{6,}" title="Minimum 6 caractères, chiffres et lettres" required />

    <div class="bottom-buttons">
      <button type="submit" name="action" value="prestataire" style="background-color:#28a745; color:#fff; padding:10px 15px; border:none; border-radius:5px; cursor:pointer;">Devenir prestataire</button>
      <button type="submit" name="action" value="publier" style="background-color:#17a2b8; color:#fff; padding:10px 15px; border:none; border-radius:5px; cursor:pointer;">Publier un projet</button>
    </div>
  </form>
</div>
</body>
</html>