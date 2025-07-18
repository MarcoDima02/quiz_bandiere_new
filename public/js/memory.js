// Memory Game JavaScript

class MemoryGame {
    constructor() {
        this.gameConfig = {
            csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
            flipUrl: document.querySelector('body').dataset.flipUrl || '/memory/flip',
            resultsUrl: document.querySelector('body').dataset.resultsUrl || '/memory/results',
            startTime: null
        };
        
        this.flippedCards = [];
        this.canFlip = true;
        this.gameComplete = false;
        
        this.init();
    }
    
    init() {
        this.setupEventListeners();
        this.startTimer();
        this.addCardClickEffects();
    }
    
    setupEventListeners() {
        const cards = document.querySelectorAll('.memory-card:not(.matched)');
        
        cards.forEach(card => {
            card.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleCardClick(card);
            });
            
            // Effetto hover
            card.addEventListener('mouseenter', () => {
                if (!card.classList.contains('matched') && !card.classList.contains('flipped')) {
                    card.style.transform = 'translateY(-5px) scale(1.05)';
                }
            });
            
            card.addEventListener('mouseleave', () => {
                if (!card.classList.contains('matched') && !card.classList.contains('flipped')) {
                    card.style.transform = '';
                }
            });
        });
        
        // Restart button
        const restartBtn = document.getElementById('restart-btn');
        if (restartBtn) {
            restartBtn.addEventListener('click', this.confirmRestart.bind(this));
        }
    }
    
    handleCardClick(cardElement) {
        if (!this.canFlip || 
            cardElement.classList.contains('flipped') || 
            cardElement.classList.contains('matched') ||
            this.gameComplete) {
            return;
        }
        
        if (this.flippedCards.length >= 2) {
            return;
        }
        
        this.flipCardVisual(cardElement);
        const cardIndex = parseInt(cardElement.dataset.index);
        
        // Chiamata AJAX per aggiornare lo stato del gioco
        this.sendFlipRequest(cardIndex, cardElement);
    }
    
    flipCardVisual(cardElement) {
        cardElement.classList.add('flipped');
        this.flippedCards.push(cardElement);
        
        // Aggiungi effetto sonoro (opzionale)
        this.playFlipSound();
    }
    
    async sendFlipRequest(cardIndex, cardElement) {
        try {
            const response = await fetch(this.gameConfig.flipUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.gameConfig.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    card_index: cardIndex
                })
            });
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            
            const data = await response.json();
            
            if (data.success) {
                this.updateGameState(data.game);
                
                if (data.is_complete) {
                    this.handleGameComplete();
                }
            } else {
                throw new Error(data.message || 'Errore nel gioco');
            }
            
        } catch (error) {
            console.error('Errore nella chiamata AJAX:', error);
            cardElement.classList.remove('flipped');
            this.flippedCards = this.flippedCards.filter(card => card !== cardElement);
            this.showError('Errore di connessione. Riprova.');
        }
    }
    
    updateGameState(game) {
        // Aggiorna statistiche
        this.updateStats(game);
        
        // Gestisci lo stato delle carte
        this.updateCards(game);
        
        // Se ci sono due carte girate e non combaciano, girale di nuovo
        if (game.flipped_cards.length === 0 && this.flippedCards.length === 2) {
            setTimeout(() => {
                this.handleNoMatch();
            }, 1500);
        }
    }
    
    updateStats(game) {
        const elements = {
            moves: document.getElementById('moves-count'),
            pairs: document.getElementById('pairs-found'),
            score: document.getElementById('score')
        };
        
        if (elements.moves) elements.moves.textContent = game.moves;
        if (elements.pairs) elements.pairs.textContent = game.matched_pairs;
        if (elements.score) elements.score.textContent = game.score;
    }
    
    updateCards(game) {
        game.cards.forEach((card, index) => {
            const cardElement = document.querySelector(`[data-index="${index}"]`);
            if (!cardElement) return;
            
            // Gestisci carte accoppiate
            if (card.is_matched && !cardElement.classList.contains('matched')) {
                this.handleMatch(cardElement);
            }
            
            // Aggiorna stato flipped
            if (card.is_flipped && !cardElement.classList.contains('flipped')) {
                cardElement.classList.add('flipped');
            } else if (!card.is_flipped && cardElement.classList.contains('flipped') && !card.is_matched) {
                // Non rimuovere subito, lascia che handleNoMatch se ne occupi
            }
        });
    }
    
    handleMatch(cardElement) {
        cardElement.classList.add('matched');
        
        // Animazione di match
        cardElement.style.animation = 'matchFound 0.8s ease-in-out';
        
        // Rimuovi dalla lista delle carte girate
        this.flippedCards = this.flippedCards.filter(card => card !== cardElement);
        
        // Effetto particelle (opzionale)
        this.createMatchParticles(cardElement);
        
        // Suono di successo
        this.playMatchSound();
        
        setTimeout(() => {
            cardElement.style.animation = '';
        }, 800);
    }
    
    handleNoMatch() {
        this.flippedCards.forEach(card => {
            if (!card.classList.contains('matched')) {
                card.classList.remove('flipped');
                card.style.animation = 'shake 0.5s ease-in-out';
                setTimeout(() => {
                    card.style.animation = '';
                }, 500);
            }
        });
        
        this.flippedCards = [];
        this.playNoMatchSound();
    }
    
    handleGameComplete() {
        this.gameComplete = true;
        this.canFlip = false;
        
        // Mostra messaggio di completamento
        this.showCompletionMessage();
        
        // Redirect dopo un delay
        setTimeout(() => {
            window.location.href = this.gameConfig.resultsUrl;
        }, 2000);
    }
    
    showCompletionMessage() {
        const message = document.createElement('div');
        message.className = 'game-complete-popup';
        message.innerHTML = `
            <div class="popup-content">
                <h2>🎉 Complimenti!</h2>
                <p>Hai completato il Memory Game!</p>
                <div class="loading-spinner"></div>
            </div>
        `;
        
        document.body.appendChild(message);
        
        // Stile del popup
        message.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            animation: fadeIn 0.3s ease-out;
        `;
        
        const popupContent = message.querySelector('.popup-content');
        popupContent.style.cssText = `
            background: white;
            padding: 3rem;
            border-radius: 15px;
            text-align: center;
            animation: slideInUp 0.5s ease-out;
        `;
    }
    
    startTimer() {
        const timerElement = document.getElementById('timer');
        if (!timerElement) return;
        
        const startTime = new Date().getTime();
        
        setInterval(() => {
            if (this.gameComplete) return;
            
            const now = new Date().getTime();
            const elapsed = Math.floor((now - startTime) / 1000);
            const minutes = Math.floor(elapsed / 60).toString().padStart(2, '0');
            const seconds = (elapsed % 60).toString().padStart(2, '0');
            timerElement.textContent = `${minutes}:${seconds}`;
        }, 1000);
    }
    
    addCardClickEffects() {
        const style = document.createElement('style');
        style.textContent = `
            @keyframes shake {
                0%, 100% { transform: translateX(0); }
                25% { transform: translateX(-5px); }
                75% { transform: translateX(5px); }
            }
            
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            @keyframes slideInUp {
                from { transform: translateY(30px); opacity: 0; }
                to { transform: translateY(0); opacity: 1; }
            }
            
            .loading-spinner {
                width: 40px;
                height: 40px;
                border: 4px solid #f3f3f3;
                border-top: 4px solid #3498db;
                border-radius: 50%;
                animation: spin 1s linear infinite;
                margin: 1rem auto;
            }
            
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }
    
    createMatchParticles(cardElement) {
        // Effetto particelle semplice
        const rect = cardElement.getBoundingClientRect();
        const colors = ['#3498db', '#e74c3c', '#f39c12', '#2ecc71', '#9b59b6'];
        
        for (let i = 0; i < 10; i++) {
            const particle = document.createElement('div');
            particle.style.cssText = `
                position: fixed;
                width: 6px;
                height: 6px;
                background: ${colors[Math.floor(Math.random() * colors.length)]};
                border-radius: 50%;
                left: ${rect.left + rect.width/2}px;
                top: ${rect.top + rect.height/2}px;
                pointer-events: none;
                z-index: 1000;
                animation: particleFloat 1s ease-out forwards;
            `;
            
            const randomX = (Math.random() - 0.5) * 100;
            const randomY = (Math.random() - 0.5) * 100;
            
            particle.style.setProperty('--random-x', randomX + 'px');
            particle.style.setProperty('--random-y', randomY + 'px');
            
            document.body.appendChild(particle);
            
            setTimeout(() => {
                particle.remove();
            }, 1000);
        }
        
        // Aggiungi keyframes per le particelle
        if (!document.getElementById('particle-styles')) {
            const particleStyle = document.createElement('style');
            particleStyle.id = 'particle-styles';
            particleStyle.textContent = `
                @keyframes particleFloat {
                    to {
                        transform: translate(var(--random-x), var(--random-y));
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(particleStyle);
        }
    }
    
    // Funzioni audio (opzionali)
    playFlipSound() {
        // Implementazione opzionale per effetti sonori
    }
    
    playMatchSound() {
        // Implementazione opzionale per effetti sonori
    }
    
    playNoMatchSound() {
        // Implementazione opzionale per effetti sonori
    }
    
    confirmRestart() {
        if (confirm('Sei sicuro di voler ricominciare? Perderai il progresso attuale.')) {
            window.location.href = '/memory';
        }
    }
    
    showError(message) {
        const errorDiv = document.createElement('div');
        errorDiv.textContent = message;
        errorDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #e74c3c;
            color: white;
            padding: 1rem 2rem;
            border-radius: 5px;
            z-index: 1000;
        `;
        
        document.body.appendChild(errorDiv);
        
        setTimeout(() => {
            errorDiv.remove();
        }, 3000);
    }
}

// Inizializza il gioco quando il DOM è pronto
document.addEventListener('DOMContentLoaded', () => {
    // Verifica se siamo nella pagina del gioco
    if (document.querySelector('.memory-grid')) {
        new MemoryGame();
    }
});

// Funzioni globali per compatibilità
function restartGame() {
    if (confirm('Sei sicuro di voler ricominciare? Perderai il progresso attuale.')) {
        window.location.href = '/memory';
    }
}
