<?php
session_start();

// Désactiver le cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

// Vérifier la méthode POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['error_message'] = "Méthode non autorisée";
    header("Location: proposant.php");
    exit;
}

// Vérifier la connexion utilisateur
if (empty($_SESSION['utilisateur_id'])) {
    $_SESSION['error_message'] = "Connectez-vous pour publier";
    header("Location: connection.php");
    exit;
}

// Valider les champs
$required = ['title', 'description', 'budget'];
foreach ($required as $field) {
    if (empty($_POST[$field])) {
        $_SESSION['error_message'] = "Le champ '$field' est requis";
        header("Location: proposant.php");
        exit;
    }
}

// Nettoyer les données
$titre = htmlspecialchars(trim($_POST['title']));
$description = htmlspecialchars(trim($_POST['description']));
$budget = (float)str_replace([' ', 'XAF', ','], '', $_POST['budget']);

// Connexion BDD
try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=freelance;charset=utf8mb4",
        "root",
        "",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // Insertion du projet
    $stmt = $pdo->prepare(
        "INSERT INTO offre (utilisateur_id, titre, description, budget) 
        VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([
        $_SESSION['utilisateur_id'],
        $titre,
        $description,
        $budget
    ]);

    // Succès
    $_SESSION['new_project_id'] = $pdo->lastInsertId();
    $_SESSION['success_message'] = "Projet publié avec succès !";
    header("Location: proposant.php?refresh=".time());
    exit;

} catch (PDOException $e) {
    $_SESSION['error_message'] = "Erreur : " . $e->getMessage();
    header("Location: proposant.php");
    exit;
}
?>