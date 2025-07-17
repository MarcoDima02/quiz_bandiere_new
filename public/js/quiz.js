// Quiz Bandiere - JavaScript Functions

document.addEventListener('DOMContentLoaded', function() {
    initializeQuizForm();
});

function initializeQuizForm() {
    // Gestione selezione opzioni nelle card
    const optionCards = document.querySelectorAll('.option-card');
    
    optionCards.forEach(card => {
        card.addEventListener('click', function() {
            // Trova il radio button interno
            const radio = this.querySelector('input[type="radio"]');
            if (!radio) return;
            
            const name = radio.name;
            
            // Deseleziona tutti i card dello stesso gruppo
            document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
                r.closest('.option-card').classList.remove('selected');
            });
            
            // Seleziona il radio button e il card
            radio.checked = true;
            this.classList.add('selected');
        });
    });
}

// Funzioni di utilità per future estensioni
function showNotification(message, type = 'info') {
    // Placeholder per notifiche future
    console.log(`${type.toUpperCase()}: ${message}`);
}

function validateForm(formElement) {
    // Placeholder per validazione form future
    return true;
}
