<?php
session_start();

if (!isset($_SESSION['user_email'])) {
    $redirectUrl = "connection.php?redirect=portfolio.php";
    if (isset($_GET['id'])) {
        $redirectUrl .= "&id=" . urlencode($_GET['id']);
    }
    header("Location: $redirectUrl");
    exit;
}

$utilisateurConnecteId = $_SESSION['utilisateur_id'];

// Connexion à la base de données
$host = 'localhost';
$dbname = 'freelance';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Requête pour récupérer le prestataire selon utilisateur_id = $id
    $sql = "SELECT u.nom, u.prenom, u.email, u.tel, p.photo, p.bio, p.competences, p.experiences, p.utilisateur_id
            FROM utilisateurs u
            INNER JOIN prestataire p ON u.id = p.utilisateur_id
            WHERE u.id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $prestataire = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$prestataire) {
        die("Prestataire non trouvé.");
    }

} else {
    die("ID invalide ou manquant.");
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Portfolio</title>
    <link rel="stylesheet" href="portfolio.css">
</head>

<body>
    <main>
        <?php if ($utilisateurConnecteId == $prestataire['utilisateur_id']): ?>
        <form action="update_port.php" method="post" novalidate>
            <input type="hidden" name="utilisateur_id" value="<?= $prestataire['utilisateur_id'] ?>">

            <div class="form-group photo-group">
                <label for="photo">Photo</label>
                <input type="url" id="photo" name="photo"
                       value="<?= htmlspecialchars($prestataire['photo'] ?? '') ?>"
                       placeholder="Ajouter une photo" required />
                <div class="photo-preview">
                    <img id="photoPreviewImg"
                         src="<?= htmlspecialchars($prestataire['photo'] ?? '') ?>"
                         alt="Aperçu Photo" />
                </div>
            </div>

            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea id="bio" name="bio" required><?= htmlspecialchars($prestataire['bio']) ?></textarea>
            </div>

            <div class="form-group">
                <label for="competences">Compétences</label>
                <input type="text" id="competences" name="competences"
                       value="<?= htmlspecialchars($prestataire['competences']) ?>" required />
            </div>

            <div class="form-group">
                <label for="experiences">Expériences</label>
                <textarea id="experiences" name="experiences" required><?= htmlspecialchars($prestataire['experiences']) ?></textarea>
            </div>

            <button type="submit" class="btn-primary">Modifier</button>
            <a href="delete_port.php?id=<?= $prestataire['utilisateur_id'] ?>" class="contact-item" onclick="return confirm('Supprimer ce portfolio ?')"> Supprimer</a>
        </form>
        <?php else: ?>
            <p style="text-align:center; color:red; font-weight:bold;">Vous ne pouvez pas modifier ce portfolio.</p>
        <?php endif; ?>
    </main>
</body>

<script>
    // Optionnel : mettre à jour l'aperçu de la photo en temps réel
    const photoInput = document.getElementById('photo');
    const photoPreviewImg = document.getElementById('photoPreviewImg');

    photoInput.addEventListener('input', () => {
        photoPreviewImg.src = photoInput.value;
    });
</script>

</html>