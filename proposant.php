<?php
// Connexion à la base de données (même configuration que précédemment)
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

// Requête pour récupérer les données des proposants avec leurs offres
$sql = "SELECT 
            u.id as user_id,
            u.nom, 
            u.prenom, 
            u.email, 
            u.tel, 
            p.description as proposant_desc,
            o.id as offre_id,
            o.titre as offre_titre,
            o.description as offre_desc,
            o.budget
        FROM utilisateurs u
        INNER JOIN proposant p ON u.id = p.utilisateur_id
        LEFT JOIN offre o ON u.id = o.utilisateur_id
        ORDER BY u.nom, u.prenom";

$stmt = $pdo->query($sql);
$proposants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Grouper les offres par proposant
$proposantsGroupes = [];
foreach ($proposants as $proposant) {
    $id = $proposant['user_id'];
    if (!isset($proposantsGroupes[$id])) {
        $proposantsGroupes[$id] = [
            'info' => [
                'nom' => $proposant['nom'],
                'prenom' => $proposant['prenom'],
                'email' => $proposant['email'],
                'tel' => $proposant['tel'],
                'description' => $proposant['proposant_desc']
            ],
            'offres' => []
        ];
    }
    
    if ($proposant['offre_id']) {
        $proposantsGroupes[$id]['offres'][] = [
            'titre' => $proposant['offre_titre'],
            'description' => $proposant['offre_desc'],
            'budget' => $proposant['budget']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Work4All - Découvrez les proposants et projets disponibles">
    <title>Work4All | Proposants & Projets</title>
    <link rel="stylesheet" href="proposant.css">
</head>
<body>
    <!-- En-tête identique à la page précédente -->
    <header>
        <nav class="navbar">
            <a href="Page_acceuil_w4a.html" class="logo">Work<span>4All</span></a>
            <div class="nav-links">
                <a href="Page_acceuil_w4a.html">Accueil</a>
                <a href="prestataires.php">Prestataires</a>
                <a href="avis.php">Avis</a>
            </div>
        </nav>
    </header>

    <!-- Section Hero pour les proposants -->
    <section class="clients-hero">
        <div class="hero-content">
            <h1>Trouvez des projets intéressants</h1>
            <p>Découvrez les proposants et les projets disponibles pour vos compétences.</p>
            
            <div class="search-bar">
                <input type="text" placeholder="Rechercher par type de projet ou compétence...">
                <button>🔍</button>
            </div>
        </div>
    </section>

    <!-- Section Proposants -->
    <section class="clients">
        <div class="section-title">
            <h2>Nos Proposants</h2>
            <p>Découvrez les clients qui recherchent des prestataires pour leurs projets</p>
        </div>
        
        <div class="clients-grid">
            <?php foreach ($proposantsGroupes as $proposant): ?>
                <div class="client-card">
                    <div class="client-header">
                        <h3 class="client-name"><?= htmlspecialchars($proposant['info']['prenom'] . ' ' . $proposant['info']['nom']) ?></h3>
                        <p class="client-type">Client</p>
                    </div>
                    <div class="client-content">
                        <p class="client-bio"><?= nl2br(htmlspecialchars($proposant['info']['description'])) ?></p>
                        
                        <div class="client-contact">
                            <div class="contact-item">
                                ✉ <?= htmlspecialchars($proposant['info']['email']) ?>
                            </div>
                            <?php if (!empty($proposant['info']['tel'])): ?>
                                <div class="contact-item">
                                    📞 <?= htmlspecialchars($proposant['info']['tel']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if (!empty($proposant['offres'])): ?>
                            <div class="offres-section">
                                <h4 class="offres-title">Projets proposés :</h4>
                                <?php foreach ($proposant['offres'] as $offre): ?>
                                    <div class="offre-item">
                                        <h5 class="offre-titre"><?= htmlspecialchars($offre['titre']) ?></h5>
                                        <p class="offre-desc"><?= nl2br(htmlspecialchars(substr($offre['description'], 0, 150) . '...')) ?></p>
                                        <p class="offre-budget">Budget: <?= number_format($offre['budget'], 0, ',', ' ') ?> FCFA</p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Pied de page identique à la page précédente -->
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

    <script>
        // Script pour la recherche (fonctionnalité de base)
        document.querySelector('.search-bar button').addEventListener('click', function() {
            const searchTerm = document.querySelector('.search-bar input').value.toLowerCase();
            const clientCards = document.querySelectorAll('.client-card');
            
            clientCards.forEach(card => {
                const cardText = card.textContent.toLowerCase();
                if (cardText.includes(searchTerm)) {
                    card.style.display = 'block';
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