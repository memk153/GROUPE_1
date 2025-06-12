<?php
// Connexion à la base de données
$pdo = new PDO("mysql:host=localhost;dbname=freelance;charset=utf8", 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Requête pour récupérer les avis avec le nom complet de l'utilisateur
$sql = "
    SELECT 
        a.id,
        a.utilisateur_id,
        a.note,
        a.commentaire,
        u.nom,
        u.prenom,
        CASE 
            WHEN p.utilisateur_id IS NOT NULL THEN 'Prestataire'
            WHEN pr.utilisateur_id IS NOT NULL THEN 'Proposant'
            ELSE 'Inconnu'
        END AS type_utilisateur
    FROM avis a
    LEFT JOIN utilisateurs u ON a.utilisateur_id = u.id
    LEFT JOIN prestataire p ON u.id = p.utilisateur_id
    LEFT JOIN proposant pr ON u.id = pr.utilisateur_id
";

$avis = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Work4All | Avis</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Work4All - Avis des utilisateurs">
    <link rel="stylesheet" href="proposant.css">
</head>
<body>
    <!-- En-tête commun -->
    <header>
        <nav class="navbar">
            <a href="Page_acceuil_w4a.html" class="logo">Work<span>4All</span></a>
            <div class="nav-links">
                <a href="Page_acceuil_w4a.html">Accueil</a>
                <a href="prestataires.php">Prestataires</a>
                <a href="proposant.php">Proposants</a>
            </div>
        </nav>
    </header>

    <!-- Section Hero -->
    <section class="clients-hero">
        <div class="hero-content">
            <h1>Ce que nos utilisateurs disent</h1>
            <p>Découvrez les avis et retours d’expérience de nos membres.</p>
        </div>
    </section>

    <!-- Section Avis -->
    <section class="clients">
        <div class="section-title">
            <h2>Derniers Avis</h2>
            <p>Les retours des prestataires et des clients</p>
        </div>

        <div class="clients-grid">
            <?php foreach ($avis as $a): ?>
                <div class="client-card">
                    <div class="client-header">
                        <h3 class="client-name"><?= htmlspecialchars($a['prenom'] . ' ' . $a['nom']) ?></h3>
                        <p class="client-type"><?= htmlspecialchars($a['type_utilisateur']) ?></p>
                    </div>
                    <div class="client-content">
                        <p class="client-bio"><?= nl2br(htmlspecialchars($a['commentaire'])) ?></p>
                        <div class="client-contact">
                            <div class="contact-item">Note : <?= htmlspecialchars($a['note']) ?>/5</div>
                        </div>
                        <div class="client-contact">
                            <a href="update_avis.php?id=<?= $a['id'] ?>" class="contact-item">Modifier</a>
                            <a href="delete_avis.php?id=<?= $a['id'] ?>" class="contact-item" onclick="return confirm('Supprimer cet avis ?')">Supprimer</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Pied de page -->
    <footer>
        <div class="footer-content">
            <div class="footer-about">
                <div class="footer-logo">Work4All</div>
                <p>La plateforme de référence pour trouver ou proposer des services professionnels au Cameroun.</p>
            </div>
            <div class="footer-links">
                <h3>Liens rapides</h3>
                <ul>
                    <li><a href="Page_acceuil_w4a.html">Accueil</a></li>
                    <li><a href="#">Projets</a></li>
                    <li><a href="prestataires.php">Prestataires</a></li>
                    <li><a href="proposant.php">Proposants</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h3>Entreprise</h3>
                <ul>
                    <li><a href="#">À propos</a></li>
                    <li><a href="#">Contact</a></li>
                    <li><a href="#">Conditions d'utilisation</a></li>
                    <li><a href="#">Politique de confidentialité</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            &copy; 2025 Work4All/NO_MONEY_NO_WORK. Tous droits réservés.
        </div>
    </footer>
</body>
</html>