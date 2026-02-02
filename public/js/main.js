/**
 * Médiathèque - JavaScript principal
 */

// Fermer automatiquement les alertes après 5 secondes
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});

// Confirmation de suppression
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-danger') && e.target.tagName === 'A') {
        if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
            e.preventDefault();
        }
    }
});

// Recherche en temps réel (optionnel)
const searchInput = document.querySelector('input[name="q"]');
if (searchInput) {
    let timeout = null;
    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        timeout = setTimeout(() => {
            // Soumettre le formulaire automatiquement après 500ms
            if (this.value.length >= 3) {
                this.form.submit();
            }
        }, 500);
    });
}