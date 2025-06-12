<?php
session_start();

// Connexion à la base de données (à adapter selon votre config)
$mysqli = new mysqli("localhost", "root", "", "freelance");
if ($mysqli->connect_errno) {
    die("Échec de connexion à la base de données : " . $mysqli->connect_error);
}

$utilisateur_id = $_SESSION['utilisateur_id'];

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $bio = $_POST['bio'] ?? '';
    $competences = $_POST['competences'] ?? '';
    $experiences = $_POST['experiences'] ?? '';

    // Gestion de la photo uploadée
    $photo_path = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['photo']['tmp_name'];
        $name = basename($_FILES['photo']['name']);
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($ext, $allowed_ext)) {
            $new_filename = 'uploads/prestataire_' . $utilisateur_id . '.' . $ext;
            if (!is_dir('uploads')) {
                mkdir('uploads', 0755, true);
            }
            if (move_uploaded_file($tmp_name, $new_filename)) {
                $photo_path = $new_filename;
            }
        }
    }

    // Vérifier si un profil prestataire existe déjà pour cet utilisateur
    $stmt_check = $mysqli->prepare("SELECT id, photo FROM prestataire WHERE utilisateur_id = ?");
    $stmt_check->bind_param("i", $user_id);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // Mise à jour existante
        $row = $result->fetch_assoc();
        $prestataire_id = $row['id'];
        $old_photo = $row['photo'];

        // Si nouvelle photo uploadée, remplacer l'ancienne
        if ($photo_path === null) {
            $photo_path = $old_photo; // garder l'ancienne
        } else {
            // Optionnel: supprimer l'ancienne photo si différente
            if ($old_photo && file_exists($old_photo) && $old_photo !== $photo_path) {
                unlink($old_photo);
            }
        }

        $stmt_update = $mysqli->prepare("UPDATE prestataire SET photo = ?, bio = ?, competences = ?, experiences = ? WHERE id = ?");
        $stmt_update->bind_param("sssssi", $photo_path, $bio, $competences, $experiences, $prestataire_id);
        $stmt_update->execute();
        $stmt_update->close();

    } else {
        // Insertion nouvelle
        $stmt_insert = $mysqli->prepare("INSERT INTO prestataire (utilisateur_id, photo, bio, competences, experiences) VALUES (?, ?, ?, ?, ?)");
        $stmt_insert->bind_param("issss", $utilisateur_id, $photo_path, $bio, $competences, $experiences);
        $stmt_insert->execute();
        $stmt_insert->close();
    }

    $stmt_check->close();

    // Redirection ou message
    header("Location: profil.php?success=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Profil</title>
  <style>
    body {
      font-family: 'Helvetica Neue', sans-serif;
      margin: 0;
      background-color: #f4f6f9;
    }

    header {
      background-color: #fff;
      padding: 10px 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .btn {
      padding: 10px 15px;
      margin-left: 10px;
      border: none;
      border-radius: 5px;
      color: #fff;
      font-weight: bold;
      text-decoration: none;
    }

    .btn-primary { background-color: #007bff; }
    .btn-success { background-color: #28a745; }

    .profile {
      text-align: center;
      background: linear-gradient(to bottom right, #0062E6, #33AEFF);
      color: #fff;
      padding: 40px 20px;
      border-bottom-left-radius: 30px;
      border-bottom-right-radius: 30px;
    }

    #profileImage {
      width: 100px;
      height: 100px;
      border-radius: 50%;
      background: #fff;
      object-fit: cover;
      margin-bottom: 10px;
      cursor: pointer;
    }

    #imageInput {
      display: none;
    }

    .section {
      background-color: #fff;
      margin: 20px;
      padding: 20px;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    .section h3 {
      color: #333;
      border-bottom: 2px solid #eee;
      padding-bottom: 5px;
    }

    textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      resize: vertical;
    }
  </style>
</head>
<body>

<header>
  <strong>Mon Profil</strong>
  <div>
    <a href="#" class="btn btn-primary">Publier un projet</a>
    <a href="register.php" class="btn btn-success">Devenir Prestataire</a>
  </div>
</header>

<form method="POST" enctype="multipart/form-data" action="profil.php">
  <div class="profile">
    <img id="profileImage" src="https://via.placeholder.com/100" alt="Photo de profil" />
    <input type="file" id="imageInput" name="photo" accept="image/*" />
    <p><small>(Cliquez sur la photo pour la modifier)</small></p>

    <h2 id="fullName">Utilisateur</h2>
    <p id="emailField"></p>
    <p id="phoneField"></p>
  </div>

  <div class="section">
    <h3>Bio</h3>
    <textarea name="bio" rows="3" placeholder="Décrivez-vous ici..."></textarea>
  </div>

  <div class="section">
    <h3>Compétences</h3>
    <textarea name="competences" rows="3" placeholder="Listez vos compétences..."></textarea>
  </div>

  <div class="section">
    <h3>Expériences</h3>
    <textarea name="experiences" rows="3" placeholder="Décrivez vos expériences..."></textarea>
  </div>

  <div style="text-align:center; margin:20px;">
    <button type="submit" class="btn btn-success">Enregistrer</button>
  </div>
</form>

<script>
  // Récupération des infos depuis l’URL
  const params = new URLSearchParams(window.location.search);
  const nom = params.get("nom") || "Utilisateur";
  const prenom = params.get("prenom") || "";
  const email = params.get("email") || "";
  const telephone = params.get("telephone") || "";

  document.getElementById("fullName").textContent = `${prenom} ${nom}`;
  document.getElementById("emailField").textContent = email;
  document.getElementById("phoneField").textContent = telephone;

  // Photo de profil : upload
  const profileImage = document.getElementById("profileImage");
  const imageInput = document.getElementById("imageInput");

  profileImage.addEventListener("click", () => imageInput.click());

  imageInput.addEventListener("change", function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        profileImage.src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });
</script>

</body>
</html>
