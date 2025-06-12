<?php
session_start(); // Démarre la session

header('Content-Type: application/json');

// Vérifie que les données sont bien envoyées en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["message" => "Méthode non autorisée"]);
    exit;
}

// Vérifie que l'utilisateur est connecté
if (!isset($_SESSION['utilisateur_id'])) {
    http_response_code(403);
    echo json_encode(["message" => "Utilisateur non connecté"]);
    exit;
}

// Vérifie que les champs requis sont bien envoyés
if (empty($_POST['title']) || empty($_POST['description']) || empty($_POST['budget'])) {
    http_response_code(400);
    echo json_encode(["message" => "Champs manquants"]);
    exit;
}

$utilisateur_id = $_SESSION['utilisateur_id'];
$titre = trim($_POST['title']);
$description = trim($_POST['description']);
$budget = trim($_POST['budget']);

// Connexion à la BDD
try {
    $pdo = new PDO('mysql:host=localhost;dbname=freelance;charset=utf8mb4', 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    $stmt = $pdo->prepare("INSERT INTO offre (utilisateur_id, titre, description, budget) VALUES (?, ?, ?, ?)");
    $stmt->execute([$utilisateur_id, $titre, $description, $budget]);

    echo json_encode(["message" => "Projet publié avec succès."]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["message" => "Erreur DB : " . $e->getMessage()]);
}
?>
