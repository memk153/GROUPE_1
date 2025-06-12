<?php
$pdo = new PDO("mysql:host=localhost;dbname=freelance;charset=utf8", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM avis WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

header('Location: avis.php');
exit;
