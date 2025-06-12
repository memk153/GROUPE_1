<?php
$host = 'localhost';
$dbname = 'freelance';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    http_response_code(500);
    die('Erreur de connexion : ' . $e->getMessage());
}

// Récupérer les données POST
$field = $_POST['field'] ?? '';
$value = $_POST['value'] ?? '';
$utilisateur_id = 1; // À adapter selon l'utilisateur connecté

$validFields = [
    'photo' => ['table' => 'prestataire', 'column' => 'photo'],
    'bio' => ['table' => 'prestataire', 'column' => 'bio'],
    'competences_experiences' => ['custom' => true],
    'email' => ['table' => 'utilisateurs', 'column' => 'email'],
    'nom_complet' => ['custom' => true],
];

// Vérification
if (!isset($validFields[$field])) {
    http_response_code(400);
    echo "Champ invalide.";
    exit;
}

try {
    if (isset($validFields[$field]['custom'])) {
        if ($field == 'nom_complet') {
            [$nom, $prenom] = explode(' ', $value, 2) + ["", ""]; // Séparer nom/prénom
            $stmt = $pdo->prepare("UPDATE utilisateurs SET nom = ?, prenom = ? WHERE id = ?");
            $stmt->execute([$nom, $prenom, $utilisateur_id]);
        } elseif ($field == 'competences_experiences') {
            [$competences, $experiences] = explode('-', $value, 2) + ["", ""];
            $stmt = $pdo->prepare("UPDATE prestataire SET competences = ?, experiences = ? WHERE utilisateur_id = ?");
            $stmt->execute([trim($competences), trim($experiences), $utilisateur_id]);
        }
    } else {
        $table = $validFields[$field]['table'];
        $column = $validFields[$field]['column'];

        $stmt = $pdo->prepare("UPDATE $table SET $column = ? WHERE utilisateur_id = ?");
        if ($table === 'utilisateurs') {
            $stmt = $pdo->prepare("UPDATE $table SET $column = ? WHERE id = ?");
        }
        $stmt->execute([$value, $utilisateur_id]);
    }

    echo "Mis à jour avec succès";
} catch (Exception $e) {
    http_response_code(500);
    echo "Erreur : " . $e->getMessage();
}
?>
