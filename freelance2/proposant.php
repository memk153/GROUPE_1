<?php
session_start();

// Configuration anti-cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// Connexion BDD
$host = 'localhost';
$dbname = 'freelance';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Erreur connexion : " . $e->getMessage());
}

// Requête optimisée
$sql = "SELECT 
        u.id as user_id,
        u.nom, 
        u.prenom, 
        u.email, 
        u.tel,
        COALESCE(p.description, 'Description par défaut') as proposant_desc,
        o.id as offre_id,
        o.titre,
        o.description,
        o.budget,
        o.date_creation
        FROM utilisateurs u
        LEFT JOIN proposant p ON u.id = p.utilisateur_id
        LEFT JOIN offre o ON u.id = o.utilisateur_id
        WHERE o.id IS NOT NULL
        ORDER BY o.date_creation DESC";

$stmt = $pdo->query($sql);
$proposants = $stmt->fetchAll();

// Groupe par proposant
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
    'id' => $proposant['offre_id'],
    'titre' => $proposant['titre'], // Champ correct de la requête SQL
    'description' => $proposant['description'], // Champ correct de la requête SQL
    'budget' => $proposant['budget'],
    'date_creation' => $proposant['date_creation']
];
    }
}

$newProjectId = $_SESSION['new_project_id'] ?? null;
unset($_SESSION['new_project_id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work4All | Proposants</title>
    <style>
        /* Style de base */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        
        /* Grille des proposants */
        .clients-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .clients-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }
        
        /* Carte proposant */
        .client-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: transform 0.3s;
        }
        
        .client-card:hover {
            transform: translateY(-5px);
        }
        
        .client-header {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .client-name {
            margin: 0;
            color: #2c3e50;
        }
        
        .client-type {
            margin: 5px 0 0;
            color: #7f8c8d;
            font-size: 0.9em;
        }
        
        .client-content {
            padding: 15px;
        }
        
        .client-bio {
            color: #555;
            margin-bottom: 15px;
        }
        
        /* Section projets */
        .projects-section {
            margin-top: 15px;
        }
        
        .projects-title {
            font-size: 1.1em;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .project-item {
            padding: 12px;
            margin-bottom: 10px;
            background: #f9f9f9;
            border-radius: 5px;
            border-left: 3px solid #3498db;
        }
        
        .new-project {
            animation: highlight 1.5s;
            border-left-color: #2ecc71;
        }
        
        .project-title {
            margin: 0 0 5px;
            color: #2980b9;
            font-size: 1em;
        }
        
        .project-desc {
            margin: 5px 0;
            color: #555;
            font-size: 0.9em;
        }
        
        .project-meta {
            display: flex;
            justify-content: space-between;
            font-size: 0.85em;
            color: #666;
        }
        
        /* Animations */
        @keyframes highlight {
            0% { background-color: #e8f5e9; }
            100% { background-color: #f9f9f9; }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .clients-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="clients-container">
        <!-- Messages de notification -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert success">
                <?= htmlspecialchars($_SESSION['success_message']) ?>
                <?php unset($_SESSION['success_message']); ?>
            </div>
        <?php endif; ?>
        
        <!-- Titre section -->
        <div class="section-title">
            <h1>Nos Proposants</h1>
            <p>Clients recherchant des prestataires</p>
        </div>
        
        <!-- Grille des proposants -->
        <div class="clients-grid">
            <?php foreach ($proposantsGroupes as $proposant): ?>
            <div class="client-card">
                <div class="client-header">
                    <h2 class="client-name"><?= htmlspecialchars($proposant['info']['prenom'] . ' ' . $proposant['info']['nom']) ?></h2>
                    <p class="client-type">Client</p>
                </div>
                
                <div class="client-content">
                    <p class="client-bio"><?= nl2br(htmlspecialchars($proposant['info']['description'])) ?></p>
                    
                    <div class="client-contact">
                        <p>✉ <?= htmlspecialchars($proposant['info']['email']) ?></p>
                        <?php if (!empty($proposant['info']['tel'])): ?>
                            <p>📞 <?= htmlspecialchars($proposant['info']['tel']) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="projects-section">
                        <h3 class="projects-title">Projets proposés</h3>
                        <?php if (!empty($proposant['offres'])): ?>
                            <?php foreach ($proposant['offres'] as $offre): ?>
                                <div class="project-item <?= ($offre['id'] == $newProjectId) ? 'new-project' : '' ?>">
                                    <h4 class="project-title"><?= htmlspecialchars($offre['titre']) ?></h4>
                                    <p class="project-desc"><?= nl2br(htmlspecialchars(substr($offre['description'], 0, 100) . '...')) ?></p>
                                    <div class="project-meta">
                                        <span>Budget: <?= number_format($offre['budget'], 0, ',', ' ') ?> FCFA</span>
                                        <?php if (!empty($offre['date_creation'])): ?>
                                            <span><?= date('d/m/Y', strtotime($offre['date_creation'])) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="no-projects">Aucun projet actif</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>