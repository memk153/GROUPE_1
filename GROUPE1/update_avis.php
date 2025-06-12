<?php
$pdo = new PDO("mysql:host=localhost;dbname=freelance;charset=utf8", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mettre à jour l'avis
    $stmt = $pdo->prepare("UPDATE avis SET note = ?, commentaire = ? WHERE id = ?");
    $stmt->execute([$_POST['note'], $_POST['commentaire'], $_POST['id']]);
    header('Location: avis.php');
    exit;
}

// Si on est en GET, afficher le formulaire de modification
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: avis.php');
    exit;
}

$id = (int)$_GET['id'];

// Récupérer les données actuelles de l'avis
$stmt = $pdo->prepare("SELECT * FROM avis WHERE id = ?");
$stmt->execute([$id]);
$avis = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$avis) {
    header('Location: avis.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier l'avis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        form { max-width: 400px; margin: auto; }
        label { display: block; margin-top: 10px; }
        input[type="number"], textarea { width: 100%; padding: 8px; box-sizing: border-box; }
        button { margin-top: 15px; padding: 10px 20px; }
    </style>
</head>
<body>
    <h1>Modifier l'avis</h1>
    <form method="post" action="update_avis.php">
        <input type="hidden" name="id" value="<?= htmlspecialchars($avis['id']) ?>">
        
        <label for="note">Note (1 à 5) :</label>
        <input type="number" id="note" name="note" min="1" max="5" value="<?= htmlspecialchars($avis['note']) ?>" required>
        
        <label for="commentaire">Commentaire :</label>
        <textarea id="commentaire" name="commentaire" rows="5" required><?= htmlspecialchars($avis['commentaire']) ?></textarea>
        
        <button type="submit">Enregistrer</button>
        <a href="avis.php" style="margin-left: 10px;">Annuler</a>
    </form>
</body>
</html>