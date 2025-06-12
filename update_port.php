<?php
session_start();

if (!isset($_SESSION['utilisateur_id'])) {
    header('Location: connection.php');
    exit;
}

$utilisateurConnecteId = $_SESSION['utilisateur_id'];

if (!isset($_POST['utilisateur_id']) || !is_numeric($_POST['utilisateur_id'])) {
    die("ID de prestataire manquant ou invalide.");
}

$utilisateurId = (int)$_POST['utilisateur_id'];

// Connexion PDO
try {
    $pdo = new PDO("mysql:host=localhost;dbname=freelance;charset=utf8", 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifier que le portfolio appartient bien à l'utilisateur connecté
    $stmt = $pdo->prepare("SELECT * FROM prestataire WHERE utilisateur_id = ?");
    $stmt->execute([$utilisateurId]);
    $portfolio = $stmt->fetch();

    if (!$portfolio) {
        die("Portfolio introuvable.");
    }

    if ($utilisateurConnecteId !== $utilisateurId) {
        die("Accès non autorisé : ce portfolio ne vous appartient pas.");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $stmt = $pdo->prepare("UPDATE prestataire SET photo = ?, bio = ?, competences = ?, experiences = ? WHERE utilisateur_id = ?");
        $stmt->execute([
            $_POST['photo'],
            $_POST['bio'],
            $_POST['competences'],
            $_POST['experiences'],
            $utilisateurId
        ]);
    }

    header("Location: portfolio.php?id=" . $utilisateurId);
    exit;

} catch (Exception $e) {
    die("Erreur : " . $e->getMessage());
}