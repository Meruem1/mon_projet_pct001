document.addEventListener('DOMContentLoaded', function() {
    console.log('Le script JavaScript est chargé.');

    // === Gestion des formulaires ===
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            // Validation des mots de passe pour le formulaire d'inscription
            if (form.id === 'form-inscription') {
                const motDePasse = document.getElementById('mot_de_passe').value;
                const confirmerMotDePasse = document.getElementById('confirmer_mot_de_passe').value;

                if (motDePasse !== confirmerMotDePasse) {
                    event.preventDefault(); // Empêche la soumission du formulaire
                    alert("Les mots de passe ne correspondent pas.");
                }
            }
        });
    });

    // === Modification dynamique du contenu ===
    const welcomeMessage = document.querySelector('header h1');
    if (welcomeMessage) {
        // Exemple : Modifier le titre de la page d'accueil
        if (document.body.classList.contains('page-accueil')) {
            welcomeMessage.textContent = 'Bienvenue à la MAIRIE DE BOTRO';
        }
    }

    // === Gestion des interactions dynamiques ===
    const toggleButtons = document.querySelectorAll('.toggle-button');
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetId = button.getAttribute('data-target');
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                targetElement.classList.toggle('hidden'); // Affiche ou cache l'élément
            }
        });
    });

    // === Appels API asynchrones ===
    async function fetchData(url) {
        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error('Erreur lors de la récupération des données.');
            }
            const data = await response.json();
            console.log('Données récupérées :', data);
        } catch (error) {
            console.error('Erreur :', error);
        }
    }

    // Exemple d'appel API (à adapter selon vos besoins)
    // fetchData('/api/some-endpoint');

    // === Gestion des erreurs côté client ===
    window.addEventListener('error', function(event) {
        console.error('Erreur capturée :', event.message);
    });

    // === Fonctionnalités supplémentaires ===
    // Ajoutez ici d'autres fonctionnalités spécifiques à votre site :
    // - Gestion des animations
    // - Affichage de notifications dynamiques
    // - Validation en temps réel des champs de formulaire
    // - Gestion des événements personnalisés
});

// === Fonctions globales ===

// Fonction pour afficher un message d'alerte
function afficherMessage(message) {
    alert(message);
}

// Fonction pour valider un email
function validerEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

// Exemple d'utilisation de la validation d'email
// console.log(validerEmail('test@example.com')); // true ou false

// Fonction pour afficher ou cacher un élément
function toggleElement(id) {
    const element = document.getElementById(id);
    if (element) {
        element.classList.toggle('hidden');
    }
}