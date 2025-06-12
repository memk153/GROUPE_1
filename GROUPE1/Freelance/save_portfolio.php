<?php
// Connexion à la base de données (à adapter)
$pdo = new PDO('mysql:host=localhost;dbname=freelance;charset=utf8', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Gérer l'upload du fichier
if (isset($_FILES['profilePhoto']) && $_FILES['profilePhoto']['error'] === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['profilePhoto']['tmp_name'];
    $fileName = $_FILES['profilePhoto']['name'];
    $fileSize = $_FILES['profilePhoto']['size'];
    $fileType = $_FILES['profilePhoto']['type'];

    // Sécurité : nettoyage du nom de fichier
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

    // Dossier où enregistrer les fichiers uploadés (crée-le si inexistant)
    $uploadFileDir = './uploads/';
    if (!is_dir($uploadFileDir)) {
        mkdir($uploadFileDir, 0755, true);
    }

    $dest_path = $uploadFileDir . $newFileName;

    if(move_uploaded_file($fileTmpPath, $dest_path)) {
        // $dest_path est le chemin relatif vers la photo uploadée, à stocker en base
        $photoPath = $dest_path;
    } else {
        die('Erreur lors du déplacement du fichier.');
    }
} else {
    $photoPath = null; // Pas d'image uploadée
}

// Récupérer les autres données POST
$fullName = filter_var($_POST['fullName'] ?? '', FILTER_SANITIZE_STRING);
$aboutMe = filter_var($_POST['aboutMe'] ?? '', FILTER_SANITIZE_STRING);
$subtitle = filter_var($_POST['subtitle'] ?? '', FILTER_SANITIZE_STRING);
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);

if (!$email) {
    die('Email invalide.');
}

// Exemple utilisateur_id fixe
$utilisateur_id = ? ;

// Requête UPDATE
$sql = "UPDATE prestataire SET 
            photo_url = :photo_url,
            nom_complet = :nom_complet,
            description = :description,
            biographie = :biographie,
            email = :email
        WHERE utilisateur_id = :utilisateur_id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':photo_url' => $photoPath,
    ':nom_complet' => $fullName,
    ':description' => $subtitle,
    ':biographie' => $aboutMe,
    ':email' => $email,
    ':utilisateur_id' => $utilisateur_id
]);

echo "Profil mis à jour avec succès ! <br><a href='portfolio.php'>Voir mon portfolio</a>";
?>