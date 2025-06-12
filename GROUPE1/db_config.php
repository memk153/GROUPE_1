<?php
// backend/db_config.php

function getDbConnection() {
    $servername = "localhost"; // Généralement 'localhost' pour un serveur local
    $username = "root";        // Votre nom d'utilisateur phpMyAdmin (souvent 'root' par défaut pour WAMP/XAMPP)
    $password = "";            // Votre mot de passe phpMyAdmin (souvent vide par défaut pour WAMP/XAMPP)
    $dbname = "publier_un_projet"; // Le nom de votre base de données que vous avez créée

    // Créer une connexion
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Définir l'encodage des caractères (important pour les accents etc.)
    $conn->set_charset("utf8mb4");
    return $conn;
}

?>