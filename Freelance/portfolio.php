<?php
// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=freelance;charset=utf8', 'root', '');

// Supposons qu'on récupère le portfolio pour user_id = 1
$stmt = $pdo->prepare("SELECT portfolio_url FROM prestataire WHERE utilisateur_id = ?");
$stmt->execute([1]);
$url = $stmt->fetchColumn();

if ($url) {
    echo "Le portfolio est accessible ici : <a href='$url' target='_blank'>$url</a>";
} else {
    echo "Portfolio non trouvé.";
}
?>