// public/script.js

// Sélection des éléments du DOM
const form = document.getElementById('projectForm');
const formSteps = document.querySelectorAll('.form-step');
const nextButtons = document.querySelectorAll('.next-btn');
const prevButtons = document.querySelectorAll('.prev-btn');
const progressDots = document.querySelectorAll('.progress-bar .dot');
const categorySelect = document.getElementById('category');
const subCategoryContainer = document.getElementById('subCategoryContainer');
const subCategorySelect = document.getElementById('subCategory');

let currentStep = 0; // L'index de l'étape active (commence à 0 pour la première étape)

// Définir les sous-catégories
const subCategoriesData = {
    'Informatique & Digital': ['Développement Web', 'Développement Mobile', 'Marketing Digital', 'Maintenance Informatique', 'Autre (Info & Digital)'],
    'Bâtiment & Rénovation': ['Plomberie', 'Électricité', 'Maçonnerie', 'Peinture', 'Toiture', 'Menuiserie', 'Autre (Bâtiment)'],
    'Services à la personne': ['Ménage', 'Cours Particuliers', 'Garde d\'enfants', 'Aide à domicile', 'Jardinage', 'Autre (Services Perso)'],
    'Autre': ['Divers', 'Non spécifié']
};

// Fonction pour afficher l'étape actuelle
function showStep(stepIndex) {
    // Cacher toutes les étapes
    formSteps.forEach((step, index) => {
        step.classList.remove('active');
        if (index === stepIndex) {
            step.classList.add('active'); // Afficher l'étape courante
        }
    });

    // Mettre à jour les points de la barre de progression
    progressDots.forEach((dot, index) => {
        dot.classList.remove('active');
        if (index <= stepIndex) {
            dot.classList.add('active');
        }
    });
}

// Fonction de validation pour chaque étape
function validateStep(stepIndex) {
    let isValid = true;
    const currentActiveStep = formSteps[stepIndex];
    const requiredInputs = currentActiveStep.querySelectorAll('[required]');

    requiredInputs.forEach(input => {
        if (!input.value.trim()) { // .trim() pour enlever les espaces vides
            isValid = false;
            input.style.borderColor = 'red'; // Mettre une bordure rouge pour l'erreur
            input.focus(); // Mettre le focus sur le premier champ invalide
            alert(Veuillez remplir le champ obligatoire : ${input.previousElementSibling ? input.previousElementSibling.textContent.replace('*', '').trim() : input.placeholder});
            return; // Arrêter la boucle sur le premier champ manquant
        } else {
            input.style.borderColor = '#ccc'; // Réinitialiser la bordure si valide
        }
    });

    // Validations spécifiques
    if (stepIndex === 1) { // Validation de l'étape 2 (Catégorie)
        if (!categorySelect.value) {
            isValid = false;
            categorySelect.style.borderColor = 'red';
            alert('Veuillez sélectionner une catégorie.');
        } else {
            categorySelect.style.borderColor = '#ccc';
        }
    }

    // Vous pouvez ajouter d'autres validations spécifiques ici (ex: format email, numéro, etc.)

    return isValid;
}


// Gestion des boutons "Suivant"
nextButtons.forEach(button => {
    button.addEventListener('click', () => {
        if (validateStep(currentStep)) {
            currentStep++;
            showStep(currentStep);
        }
    });
});

// Gestion des boutons "Précédent"
prevButtons.forEach(button => {
    button.addEventListener('click', () => {
        currentStep--;
        showStep(currentStep);
    });
});

// Gestion de la sélection de catégorie pour les sous-catégories
categorySelect.addEventListener('change', () => {
    const selectedCategory = categorySelect.value;
    subCategorySelect.innerHTML = '<option value="">Sélectionnez une sous-catégorie</option>'; // Réinitialiser

    if (selectedCategory && subCategoriesData[selectedCategory]) {
        subCategoriesData[selectedCategory].forEach(subCat => {
            const option = document.createElement('option');
            option.value = subCat;
            option.textContent = subCat;
            subCategorySelect.appendChild(option);
        });
        subCategoryContainer.style.display = 'block'; // Afficher le conteneur des sous-catégories
    } else {
        subCategoryContainer.style.display = 'none'; // Cacher si pas de sous-catégories
    }
});

// Gestion de la soumission finale du formulaire
form.addEventListener('submit', async (e) => {
    e.preventDefault(); // Empêcher le rechargement de la page

    // Validation finale de la dernière étape
    if (!validateStep(currentStep)) {
        return; // Ne pas soumettre si la dernière étape n'est pas valide
    }

    const formData = new FormData(form); // Crée un objet FormData avec toutes les entrées du formulaire

    // Afficher les données dans la console (pour le debug)
    console.log("Données du formulaire avant envoi :");
    for (let [key, value] of formData.entries()) {
        console.log(${key}: ${value});
    }
    // Pour les fichiers, FormData.entries() ne montre pas le contenu du fichier, juste son nom.
    // Vous pouvez vérifier les fichiers via formData.getAll('photos')

    alert('Formulaire validé et prêt à être envoyé au serveur PHP ! Vérifiez la console pour les données.');

    // --- PARTIE ENVOI AU BACKEND (PHP) ---
    // Cette partie sera à décommenter et adapter quand votre script PHP sera prêt
    /*
    try {
        const response = await fetch('../backend/submit_project.php', { // Assurez-vous que le chemin est correct
            method: 'POST',
            body: formData // FormData est parfait pour envoyer texte et fichiers
        });

        const result = await response.json(); // Assurez-vous que votre PHP renvoie du JSON

        if (response.ok) { // Vérifie si la réponse HTTP est OK (2xx)
            alert('Projet publié avec succès !');
            form.reset(); // Réinitialiser le formulaire
            currentStep = 0; // Retour à la première étape
            showStep(currentStep);
        } else {
            alert('Erreur lors de la publication du projet : ' + result.message || 'Erreur inconnue');
            console.error('Erreur du serveur:', result);
        }
    } catch (error) {
        console.error('Erreur réseau ou du script JS:', error);
        alert('Une erreur est survenue lors de la connexion au serveur.');
    }
    */
});

// Initialiser l'affichage de la première étape au chargement de la page
showStep(currentStep);