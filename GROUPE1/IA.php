<?php
// Clé API Google Gemini (Gardez-la secrète !)
// NE PAS LA LAISSER DIRECTEMENT DANS UN REPO PUBLIC.
// Idéalement, utilisez une variable d'environnement ou un fichier de config non accessible web.
define('GEMINI_API_KEY', 'AIzaSyAYxzeqGBdcyquD7OFDTGfEdvZ_3VIR7d4'); // <<< REMPLACER CECI !

$ai_response = ''; // Pour stocker la réponse de l'IA
$user_question = ''; // Pour stocker la question de l'utilisateur

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_question'])) {
    $user_question = htmlspecialchars(trim($_POST['user_question']));

    if (!empty($user_question)) {
        // Mots-clés pour détecter les questions liées aux services
        $service_keywords = ['prestataire', 'service', 'projet', 'freelance', 'expert', 'compétences', 'recruter', 'trouver', 'collaborer', 'développeur', 'designer', 'rédacteur', 'photographe', 'marketing', 'événement', 'planification', 'aide', 'conseil', 'informatique','travail','site','internet','application'];
        $is_service_related = false;
        foreach ($service_keywords as $keyword) {
            if (stripos($user_question, $keyword) !== false) {
                $is_service_related = true;
                break;
            }
        }

        if ($is_service_related) {
            $api_endpoint = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=' . GEMINI_API_KEY;

            $data = [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => "En tant qu'assistant de planification d'événements et de recherche de prestataires de services, je vais te donner des idées et des conseils sur des sujets liés aux services, aux projets et aux prestataires. " . $user_question]
                        ]
                    ]
                ]
            ];

            $ch = curl_init($api_endpoint);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                $ai_response = '<div class="ai-response-error">Erreur cURL : ' . curl_error($ch) . '</div>';
            } else {
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($http_code == 200) {
                    $decoded_response = json_decode($response, true);
                    if (isset($decoded_response['candidates'][0]['content']['parts'][0]['text'])) {
                        $ai_response = '<div class="ai-response-success">' . nl2br(htmlspecialchars($decoded_response['candidates'][0]['content']['parts'][0]['text'])) . '</div>';
                    } else {
                        $ai_response = '<div class="ai-response-error">Impossible d\'obtenir une réponse de l\'IA. Code: ' . $http_code . ' Réponse: ' . htmlspecialchars($response) . '</div>';
                    }
                } else {
                     $ai_response = '<div class="ai-response-error">Erreur API: Code HTTP ' . $http_code . ' Réponse: ' . htmlspecialchars($response) . '</div>';
                }
            }
            curl_close($ch);
        } else {
            $ai_response = '<div class="ai-response-error">Désolé, je ne peux répondre qu\'aux questions concernant les *prestations de service, les **projets* ou la recherche de *prestataires*. Veuillez reformuler votre question.</div>';
        }
    } else {
        $ai_response = '<div class="ai-response-info">Veuillez poser une question.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assistant IA - Work4All</title>
    <style>
        /* Reset et styles de base - Cohérent avec la page d'accueil */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }
        
        /* En-tête - Identique à la page d'accueil */
        header {
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .logo {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2c3e50;
            text-decoration: none;
        }
        
        .logo span {
            color: #3498db;
        }
        
        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }
        
        .nav-links a {
            text-decoration: none;
            color: #2c3e50;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .nav-links a:hover {
            color: #3498db;
        }
        
        /* Menu Burger - Version desktop et mobile */
        .menu-btn {
            cursor: pointer;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .menu-btn span {
            font-size: 1rem;
        }
        
        /* Conteneur principal */
        main {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        /* Section Assistant IA */
        .ai-container {
            background-color: white;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }
        
        .ai-container h2 {
            color: #2c3e50;
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.8rem;
        }
        
        .ai-form label {
            display: block;
            margin-bottom: 0.5rem;
            color: #2c3e50;
            font-weight: 500;
        }
        
        .ai-form textarea {
            width: 100%;
            padding: 1rem;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            margin-bottom: 1.5rem;
            min-height: 150px;
            resize: vertical;
        }
        
        .ai-form button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
        }
        
        .ai-form button:hover {
            background-color: #2980b9;
        }
        
        /* Réponses de l'IA */
        .ai-response-container {
            margin-top: 2rem;
            padding: 1.5rem;
            background-color: #f8f9fa;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }
        
        .ai-response-container h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }
        
        .ai-response-success {
            background-color: #e3f2fd;
            color: #1976d2;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }
        
        .ai-response-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }
        
        .ai-response-info {
            background-color: #fff8e1;
            color: #ff8f00;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 1rem;
        }
        
        /* Pied de page - Identique à la page d'accueil */
        footer {
            background-color: #2c3e50;
            color: white;
            padding: 2rem;
            text-align: center;
        }
        
        .copyright {
            color: #bdc3c7;
            font-size: 0.9rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .ai-container {
                padding: 1.5rem;
            }
            
            .ai-container h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <a href="index.html" class="logo">Work<span>4All</span></a>
            <div class="nav-links">
                <a href="index.html">Accueil</a>
                <a href="prestataires.html">Prestataires</a>
                <a href="projets.html">Projets</a>
                <a href="IA.php" class="active">ChatBoxIA</a>
                <button class="menu-btn">☰<span>Menu</span></button>
            </div>
        </nav>
    </header>

    <main>
        <div class="ai-container">
            <h2>Assistant IA Work4All</h2>
            <p>Obtenez des conseils personnalisés pour vos projets et trouvez les meilleurs prestataires.</p>
            
            <form action="IA.php" method="POST" class="ai-form">
                <label for="user_question">Posez votre question :</label>
                <textarea id="user_question" name="user_question" placeholder="Ex: Comment trouver un bon développeur web ? Quelles compétences chercher chez un photographe ?" required><?php echo htmlspecialchars($user_question); ?></textarea>
                <button type="submit">Demander à l'IA</button>
            </form>

            <?php if (!empty($ai_response)): ?>
                <div class="ai-response-container">
                    <h3>Réponse de l'IA :</h3>
                    <?php echo $ai_response; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <footer>
        <div class="copyright">
            &copy; <?php echo date("Y"); ?> Work4All/NO_MONEY_NO_WORK 2025. Tous droits réservés.
        </div>
    </footer>
</body>
</html>