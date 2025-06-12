<?php
// backend/submit_project.php

// 1. Configurer les en-têtes CORS pour permettre les requêtes depuis votre frontend
// Si votre frontend est sur http://localhost/publier_projet, et votre backend aussi,
// vous n'aurez pas forcément besoin de cela, mais c'est une bonne pratique pour les requêtes AJAX.
header("Access-Control-Allow-Origin: *"); // Permet à n'importe quelle origine d'envoyer des requêtes.
                                         // Pour un projet de production, vous devriez spécifier
                                         // votre domaine précis (ex: http://localhost) au lieu de *.
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST"); // Autorise seulement la méthode POST
header("Access-Control-Max-Age: 3600"); // Cache la pré-vérification OPTIONS pendant 1 heure
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// 2. Vérifier si la requête est bien de type POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Méthode non autorisée
    echo json_encode(array("message" => "Méthode non autorisée. Seules les requêtes POST sont acceptées."));
    exit();
}

// 3. Inclure le fichier de connexion à la base de données (nous allons le créer ensuite)
require_once 'db_config.php'; // Ce fichier contiendra vos identifiants de connexion

// Initialiser la connexion à la BD
$conn = getDbConnection(); // Fonction définie dans db_config.php

// 4. Récupérer les données du formulaire
// FormData envoie les champs comme $_POST et les fichiers comme $_FILES
$title = $_POST['title'] ?? '';
$category = $_POST['category'] ?? '';
$sub_category = $_POST['sub_category'] ?? NULL; // Peut être NULL si non renseigné
$description = $_POST['description'] ?? '';
$location = $_POST['location'] ?? '';
$desired_date = $_POST['desiredDate'] ?? ''; // Notez que le nom est 'desiredDate' dans le HTML
$budget = $_POST['budget'] ?? NULL; // Peut être NULL si non renseigné

// 5. Validation simple des données (très importante pour la sécurité et l'intégrité)
if (empty($title) || empty($category) || empty($description) || empty($location) || empty($desired_date)) {
    http_response_code(400); // Bad Request
    echo json_encode(array("message" => "Veuillez remplir tous les champs obligatoires."));
    $conn->close();
    exit();
}

// 6. Gestion de l'upload des photos
$photos_paths = [];
$upload_dir = '../uploads/'; // Dossier où les photos seront stockées. Assurez-vous qu'il existe et est accessible en écriture !
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true); // Crée le dossier s'il n'existe pas
}

if (isset($_FILES['photos'])) {
    foreach ($_FILES['photos']['name'] as $key => $name) {
        if ($_FILES['photos']['error'][$key] == UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['photos']['tmp_name'][$key];
            $file_extension = pathinfo($name, PATHINFO_EXTENSION);
            $new_file_name = uniqid('photo_') . '.' . $file_extension; // Nom unique pour éviter les conflits
            $target_file = $upload_dir . $new_file_name;

            if (move_uploaded_file($tmp_name, $target_file)) {
                $photos_paths[] = $target_file; // Enregistre le chemin relatif pour la BD
            } else {
                // Gérer l'erreur d'upload si nécessaire
                error_log("Erreur lors de l'upload du fichier: " . $name);
            }
        }
    }
}
$photos_paths_str = implode(',', $photos_paths); // Convertit le tableau de chemins en une chaîne séparée par des virgules

// 7. Préparation et exécution de la requête SQL d'insertion
// Utilisation de requêtes préparées pour la sécurité (prévention des injections SQL)
$sql = "INSERT INTO projets (title, category, sub_category, description, location, desired_date, budget, photos_paths) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ssssssss", $title, $category, $sub_category, $description, $location, $desired_date, $budget, $photos_paths_str);

    if ($stmt->execute()) {
        http_response_code(201); // Created
        echo json_encode(array("message" => "Projet publié avec succès !", "project_id" => $conn->insert_id));
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(array("message" => "Erreur lors de la publication du projet.", "error" => $stmt->error));
    }

    $stmt->close();
} else {
    http_response_code(500); // Internal Server Error
    echo json_encode(array("message" => "Erreur de préparation de la requête SQL.", "error" => $conn->error));
}

// 8. Fermer la connexion à la base de données
$conn->close();

?>