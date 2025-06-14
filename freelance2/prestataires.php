<?php
// Connexion à la base de données (à adapter avec tes identifiants)
$host = 'localhost';
$dbname = 'freelance';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

$sql = "SELECT u.nom, u.prenom, u.email, u.tel, p.photo, p.bio, p.competences, p.experiences, p.utilisateur_id
        FROM utilisateurs u
        INNER JOIN prestataire p ON u.id = p.utilisateur_id";

$stmt = $pdo->query($sql);
$prestataires = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Work4All - Découvrez nos prestataires de services qualifiés au Cameroun">
    <title>Work4All | Nos Prestataires</title>
    <link rel="stylesheet" href="prestataires.css">
</head>
<body>
    <!-- En-tête identique à la page d'accueil -->
    <header>
        <nav class="navbar">
            <a href="Page_acceuil_w4a.html" class="logo">Work<span>4All</span></a>
            <div class="nav-links">
                <a href="Page_acceuil_w4a.html">Accueil</a>
                <a href="proposant.php">Proposants</a>
                <a href="avis.php">Avis</a>
            </div>
        </nav>
    </header>

    <!-- Section Hero pour la page prestataires -->
    <section class="providers-hero">
        <div class="hero-content">
            <h1>Découvrez nos professionnels talentueux</h1>
            <p>Trouvez le prestataire idéal pour concrétiser votre projet parmi nos experts qualifiés.</p>
            
            <div class="search-bar">
                <input type="text" placeholder="Rechercher par compétence ou service...">
                <button>🔍</button>
            </div>
        </div>
    </section>

    <!-- Section Prestataires -->
    <section class="providers">
        <div class="section-title">
            <h2>Nos Prestataires</h2>
            <p>Parcourez notre sélection de professionnels vérifiés et évalués par la communauté</p>
        </div>
        
        <div class="providers-grid">
    <?php foreach ($prestataires as $prestataire): ?>
        <div class="provider-card">
            <div class="provider-header">
                <h3 class="provider-name"><?= htmlspecialchars($prestataire['prenom'] . ' ' . $prestataire['nom']) ?></h3>
                <!-- On peut afficher ici une spécialité / expérience courte -->
                <p class="provider-specialty"><?= htmlspecialchars(substr($prestataire['experiences'], 0, 50)) ?>...</p>
            </div>
            <div class="provider-content">
                <!-- Photo -->
                <?php if (!empty($prestataire['photo'])): ?>
                    <div style="text-align:center; margin-bottom: 1rem;">
                        <img src="<?= htmlspecialchars($prestataire['photo']) ?>" alt="Photo de <?= htmlspecialchars($prestataire['prenom']) ?>" style="max-width: 120px; border-radius: 50%;">
                    </div>
                <?php endif; ?>

                <p class="provider-bio"><?= nl2br(htmlspecialchars($prestataire['bio'])) ?></p>
                
                <div class="provider-skills">
                    <p class="skills-title">Compétences clés :</p>
                    <div class="skills-list">
                        <?php
                        $competences = array_map('trim', explode(',', $prestataire['competences']));
                        foreach ($competences as $competence):
                        ?>
                            <span class="skill-tag"><?= htmlspecialchars($competence) ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="provider-contact">
                    <a href="mailto:<?= htmlspecialchars($prestataire['email']) ?>" class="contact-email">
                        ✉ <?= htmlspecialchars($prestataire['email']) ?>
                    </a>
                    <a href="connection.php?redirect=portfolio.php&id=<?= urlencode($prestataire['utilisateur_id']) ?>" class="portfolio-btn">Voir Portfolio</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
                    
</section>

    <!-- Pied de page identique à la page d'accueil -->
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
                    <li><a href="prestataires.html">Prestataires</a></li>
                    <li><a href="prestataires.html">Devenir prestataire</a></li>
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

    <script>
        // Script pour la recherche (fonctionnalité de base)
        document.querySelector('.search-bar button').addEventListener('click', function() {
            const searchTerm = document.querySelector('.search-bar input').value.toLowerCase();
            const providerCards = document.querySelectorAll('.provider-card');
            
            providerCards.forEach(card => {
                const cardText = card.textContent.toLowerCase();
                if (cardText.includes(searchTerm)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
        
        // Permettre la recherche avec la touche Entrée
        document.querySelector('.search-bar input').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                document.querySelector('.search-bar button').click();
            }
        });
    </script>
</body>
</html>