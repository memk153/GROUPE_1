<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publier un projet</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header>
    <div class="logo">
        <img src="logo.png" alt="Work4all">
        <span>WORK <br> 4all</span>
    </div>
    <nav>
        <a href="connection.php">Connexion</a>
    </nav>
</header>

<main class="container">
    <h1>Publier un projet</h1>

    <div class="progress-bar">
        <span class="dot active" data-step="1"></span>
        <span class="dot" data-step="2"></span>
        <span class="dot" data-step="3"></span>
    </div>

    <form id="projectForm" method="POST" action="submit.php">
        <!-- Étape 1 -->
        <div class="form-step active" id="step1">
            <label for="title">Titre de votre besoin *</label>
            <input type="text" id="title" name="title" placeholder="Ex: Création d'un site web" required>
            <button type="button" class="next-btn">Suivant</button>
        </div>

        <!-- Étape 2 -->
        <div class="form-step" id="step2">
            <label for="description">Description *</label>
            <textarea id="description" name="description" rows="6" required></textarea>

            <div class="button-group">
                <button type="button" class="prev-btn">Précédent</button>
                <button type="button" class="next-btn">Suivant</button>
            </div>
        </div>

        <!-- Étape 3 -->
        <div class="form-step" id="step3">
            <label for="budget">Budget estimé *</label>
            <input type="text" id="budget" name="budget" placeholder="Ex: 100 000 XAF" required>

            <div class="button-group">
                <button type="button" class="prev-btn">Précédent</button>
                <button type="submit" class="submit-btn">Publier le projet</button>
            </div>
        </div>
    </form>
</main>

<script src="script.js"></script>
</body>
</html>


