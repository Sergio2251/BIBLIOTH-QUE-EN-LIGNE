// js/app.js
document.addEventListener('DOMContentLoaded', () => {

    // Confirmation suppression
    const deleteForms = document.querySelectorAll('.delete-form');
    deleteForms.forEach(form => {
        form.addEventListener('submit', (e) => {
            if (!confirm('Confirmer la suppression ?')) {
                e.preventDefault();
            }
        });
    });

    // Validation formulaire livre
    const livreForm = document.getElementById('livre-form');
    if (livreForm) {
        livreForm.addEventListener('submit', (e) => {
            const titre = livreForm.querySelector('[name="titre"]');
            const auteur = livreForm.querySelector('[name="auteur"]');
            if (titre && titre.value.trim().length < 2) {
                alert('Le titre doit contenir au moins 2 caractères.');
                e.preventDefault();
                return;
            }
            if (auteur && auteur.value.trim().length < 2) {
                alert('L\'auteur doit contenir au moins 2 caractères.');
                e.preventDefault();
            }
        });
    }
});