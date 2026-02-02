/**
 * Validation des formulaires côté client
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Validation email
    const emailInputs = document.querySelectorAll('input[type="email"]');
    emailInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.style.borderColor = '#dc3545';
                showError(this, 'Email invalide');
            } else {
                this.style.borderColor = '#ddd';
                removeError(this);
            }
        });
    });
    
    // Validation mot de passe
    const passwordInputs = document.querySelectorAll('input[type="password"]');
    passwordInputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && this.value.length < 6) {
                this.style.borderColor = '#dc3545';
                showError(this, 'Le mot de passe doit contenir au moins 6 caractères');
            } else {
                this.style.borderColor = '#ddd';
                removeError(this);
            }
        });
    });
    
    // Validation téléphone
    const telInputs = document.querySelectorAll('input[type="tel"]');
    telInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const telRegex = /^[0-9\s\-\+\$\$]{10,}$/;
            if (this.value && !telRegex.test(this.value)) {
                this.style.borderColor = '#dc3545';
                showError(this, 'Numéro de téléphone invalide');
            } else {
                this.style.borderColor = '#ddd';
                removeError(this);
            }
        });
    });
    
    // Validation année
    const yearInputs = document.querySelectorAll('input[name="annee_parution"]');
    yearInputs.forEach(input => {
        input.addEventListener('blur', function() {
            const year = parseInt(this.value);
            const currentYear = new Date().getFullYear();
            if (this.value && (year < 1000 || year > currentYear)) {
                this.style.borderColor = '#dc3545';
                showError(this, 'Année invalide');
            } else {
                this.style.borderColor = '#ddd';
                removeError(this);
            }
        });
    });
    
});

function showError(input, message) {
    removeError(input);
    const error = document.createElement('small');
    error.className = 'form-text text-danger error-message';
    error.textContent = message;
    input.parentNode.appendChild(error);
}

function removeError(input) {
    const existingError = input.parentNode.querySelector('.error-message');
    if (existingError) {
        existingError.remove();
    }
}