/**
 * Bonus Game JavaScript
 * Gestisce l'interattività del gioco bonus con domande speciali
 */

class BonusGame {
    constructor() {
        this.gameTimer = null;
        this.questionStartTime = null;
        this.isAnswering = false;
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.setupCSRF();
        
        // Se siamo nella pagina del gioco, inizializza il timer
        if (document.querySelector('.bonus-game-header')) {
            this.startGameTimer();
            this.questionStartTime = new Date();
        }
    }

    setupEventListeners() {
        // Form di configurazione (index page)
        const configForm = document.getElementById('bonus-config-form');
        if (configForm) {
            configForm.addEventListener('submit', (e) => this.handleGameStart(e));
        }

        // Pulsanti delle risposte (game page)
        const answerButtons = document.querySelectorAll('.answer-btn');
        answerButtons.forEach(btn => {
            btn.addEventListener('click', (e) => this.handleAnswer(e));
        });

        // Pulsante prossima domanda
        const nextBtn = document.getElementById('next-question-btn');
        if (nextBtn) {
            nextBtn.addEventListener('click', () => this.nextQuestion());
        }

        // Pulsante abbandona gioco
        const quitBtn = document.getElementById('quit-game');
        if (quitBtn) {
            quitBtn.addEventListener('click', () => this.quitGame());
        }

        // Modal info bonus
        const showInfoBtn = document.getElementById('show-bonus-info');
        const closeInfoBtn = document.getElementById('close-bonus-info');
        const infoModal = document.getElementById('bonus-info-modal');
        
        if (showInfoBtn && infoModal) {
            showInfoBtn.addEventListener('click', () => {
                infoModal.style.display = 'flex';
            });
        }
        
        if (closeInfoBtn && infoModal) {
            closeInfoBtn.addEventListener('click', () => {
                infoModal.style.display = 'none';
            });
            
            // Chiudi modal cliccando fuori
            infoModal.addEventListener('click', (e) => {
                if (e.target === infoModal) {
                    infoModal.style.display = 'none';
                }
            });
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => this.handleKeyboard(e));
    }

    setupCSRF() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (token) {
            this.csrfToken = token.getAttribute('content');
            console.log('CSRF Token found:', this.csrfToken ? 'Yes' : 'No');
        } else {
            console.error('CSRF token meta tag not found');
        }
    }

    async handleGameStart(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const difficulty = formData.get('difficulty') || 'medium';
        const questionCount = parseInt(formData.get('question_count')) || 10;
        
        // Mostra loading
        this.showLoading('Preparazione del gioco...');
        
        try {
            console.log('Starting game with:', { difficulty, questionCount, csrfToken: this.csrfToken });
            
            const response = await fetch(document.body.dataset.startUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    difficulty: difficulty,
                    question_count: questionCount
                })
            });

            console.log('Response status:', response.status);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Response data:', data);
            
            if (data.success) {
                // Reindirizza alla pagina del gioco
                window.location.href = data.redirect_url;
            } else {
                this.showError(data.error || 'Errore nell\'avvio del gioco');
            }
        } catch (error) {
            console.error('Errore avvio gioco:', error);
            this.showError('Errore di connessione. Riprova.');
        } finally {
            this.hideLoading();
        }
    }

    async handleAnswer(e) {
        if (this.isAnswering) return;
        
        this.isAnswering = true;
        const answer = e.currentTarget.dataset.answer;
        
        // Disabilita tutti i pulsanti
        this.disableAnswerButtons();
        
        // Mostra loading sulla risposta selezionata
        e.currentTarget.innerHTML += ' <span class="loading-spinner">⏳</span>';
        
        try {
            const response = await fetch(document.body.dataset.answerUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({ answer: answer })
            });

            const data = await response.json();
            
            if (data.success) {
                this.showAnswerFeedback(data.result, answer);
                this.updateStats(data.stats);
                
                // Se il gioco è completo, mostra pulsante risultati
                if (data.result.is_complete) {
                    this.showGameComplete(data.results);
                } else {
                    // Prepara la prossima domanda
                    this.prepareNextQuestion(data.next_question);
                }
            } else {
                this.showError(data.error || 'Errore nel processare la risposta');
            }
        } catch (error) {
            console.error('Errore risposta:', error);
            this.showError('Errore di connessione. Riprova.');
        } finally {
            this.isAnswering = false;
        }
    }

    showAnswerFeedback(result, userAnswer) {
        // Evidenzia la risposta corretta e quella sbagliata
        const answerButtons = document.querySelectorAll('.answer-btn');
        
        answerButtons.forEach(btn => {
            const btnAnswer = btn.dataset.answer;
            
            if (btnAnswer === result.correct_answer) {
                btn.classList.add('correct');
            } else if (btnAnswer === userAnswer && !result.is_correct) {
                btn.classList.add('wrong');
            }
            
            // Rimuovi loading spinner
            const spinner = btn.querySelector('.loading-spinner');
            if (spinner) {
                spinner.remove();
            }
        });

        // Mostra feedback dettagliato
        const feedbackContainer = document.getElementById('feedback-container');
        const resultIcon = document.getElementById('result-icon');
        const resultText = document.getElementById('result-text');
        const correctAnswer = document.getElementById('correct-answer');
        const explanation = document.getElementById('explanation');
        const pointsEarned = document.getElementById('points-earned');

        if (feedbackContainer) {
            feedbackContainer.style.display = 'block';
            
            if (result.is_correct) {
                resultIcon.textContent = '✅';
                resultText.textContent = 'Risposta Corretta!';
                resultText.style.color = '#28a745';
            } else {
                resultIcon.textContent = '❌';
                resultText.textContent = 'Risposta Sbagliata';
                resultText.style.color = '#dc3545';
            }
            
            if (correctAnswer) {
                correctAnswer.innerHTML = `<strong>Risposta corretta:</strong> ${result.correct_answer}`;
            }
            
            if (explanation) {
                explanation.innerHTML = `<strong>Spiegazione:</strong> ${result.explanation}`;
            }
            
            if (pointsEarned) {
                pointsEarned.innerHTML = `<strong>Punti guadagnati:</strong> ${result.points_earned} (+${result.streak} streak)`;
            }
        }

        // Effetti speciali per risposta corretta
        if (result.is_correct) {
            this.createParticleEffect();
            this.playSuccessSound();
        }
    }

    updateStats(stats) {
        // Aggiorna le statistiche nell'header
        const elements = {
            score: stats.score,
            correct_answers: stats.correct_answers,
            streak: stats.streak
        };

        Object.entries(elements).forEach(([key, value]) => {
            const element = document.querySelector(`.stat-box .stat-value`);
            if (element && element.closest('.stat-box').querySelector('.stat-label').textContent.toLowerCase().includes(key.replace('_', ''))) {
                element.textContent = value;
            }
        });

        // Aggiorna progress bar se c'è
        const progressFill = document.querySelector('.progress-fill');
        if (progressFill && stats.progress !== undefined) {
            progressFill.style.width = `${stats.progress}%`;
        }
    }

    prepareNextQuestion(nextQuestion) {
        // Salva la prossima domanda per quando l'utente clicca "Prossima"
        this.nextQuestionData = nextQuestion;
    }

    nextQuestion() {
        if (this.nextQuestionData) {
            // Ricarica la pagina con la prossima domanda
            window.location.reload();
        } else {
            // Fallback: richiedi la domanda corrente
            window.location.href = document.body.dataset.gameUrl || '/bonus/game';
        }
    }

    showGameComplete(results) {
        const nextBtn = document.getElementById('next-question-btn');
        if (nextBtn) {
            nextBtn.textContent = '🏆 Visualizza Risultati';
            nextBtn.onclick = () => {
                window.location.href = document.body.dataset.resultsUrl || '/bonus/results';
            };
        }

        // Mostra messaggio di completamento
        const feedbackContainer = document.getElementById('feedback-container');
        if (feedbackContainer) {
            const gameCompleteMessage = document.createElement('div');
            gameCompleteMessage.className = 'game-complete-message';
            gameCompleteMessage.innerHTML = `
                <div class="completion-celebration">
                    <h3>🎉 Gioco Completato!</h3>
                    <p>Punteggio finale: <strong>${results.score}</strong></p>
                    <p>Accuratezza: <strong>${results.accuracy}%</strong></p>
                    <p>Rank: <strong style="color: ${results.rank.color}">${results.rank.icon} ${results.rank.name}</strong></p>
                </div>
            `;
            feedbackContainer.appendChild(gameCompleteMessage);
        }

        // Effetti celebrativi
        this.createCelebrationEffect();
    }

    async quitGame() {
        if (confirm('Sei sicuro di voler abbandonare il gioco? Il progresso andrà perso.')) {
            try {
                const response = await fetch(document.body.dataset.quitUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    window.location.href = data.redirect_url;
                }
            } catch (error) {
                console.error('Errore abbandono:', error);
                // Fallback
                window.location.href = '/bonus';
            }
        }
    }

    handleKeyboard(e) {
        // Solo nella pagina del gioco
        if (!document.querySelector('.answers-container')) return;
        
        // A, B, C, D per le risposte
        if (e.key >= 'A' && e.key <= 'D') {
            const answerIndex = e.key.charCodeAt(0) - 65; // A=0, B=1, C=2, D=3
            const answerButton = document.querySelector(`.answer-btn[data-index="${answerIndex}"]`);
            if (answerButton && !this.isAnswering) {
                answerButton.click();
            }
        }
        
        // Spazio per prossima domanda
        if (e.code === 'Space' && document.getElementById('next-question-btn').style.display !== 'none') {
            e.preventDefault();
            this.nextQuestion();
        }
        
        // Escape per abbandonare
        if (e.key === 'Escape') {
            this.quitGame();
        }
    }

    disableAnswerButtons() {
        const answerButtons = document.querySelectorAll('.answer-btn');
        answerButtons.forEach(btn => {
            btn.classList.add('disabled');
            btn.style.pointerEvents = 'none';
        });
    }

    startGameTimer() {
        let startTime = new Date();
        
        this.gameTimer = setInterval(() => {
            const now = new Date();
            const elapsed = Math.floor((now - startTime) / 1000);
            
            const minutes = Math.floor(elapsed / 60).toString().padStart(2, '0');
            const seconds = (elapsed % 60).toString().padStart(2, '0');
            
            const timerElement = document.getElementById('timer');
            if (timerElement) {
                timerElement.textContent = `${minutes}:${seconds}`;
            }
        }, 1000);
    }

    createParticleEffect() {
        const particles = document.getElementById('particles');
        if (!particles) return;

        const colors = ['🌟', '⭐', '✨', '💫', '🎉'];
        
        for (let i = 0; i < 10; i++) {
            setTimeout(() => {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.textContent = colors[Math.floor(Math.random() * colors.length)];
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                
                particles.appendChild(particle);
                
                // Rimuovi particella dopo l'animazione
                setTimeout(() => {
                    if (particle.parentNode) {
                        particle.parentNode.removeChild(particle);
                    }
                }, 2000);
            }, i * 100);
        }
    }

    createCelebrationEffect() {
        const particles = document.getElementById('particles');
        if (!particles) return;

        const celebrationEmojis = ['🎉', '🎊', '🏆', '🥇', '🌟', '⭐', '✨', '💫'];
        
        for (let i = 0; i < 20; i++) {
            setTimeout(() => {
                const particle = document.createElement('div');
                particle.className = 'particle celebration';
                particle.textContent = celebrationEmojis[Math.floor(Math.random() * celebrationEmojis.length)];
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = '100%';
                particle.style.animation = 'celebration 3s ease-out forwards';
                
                particles.appendChild(particle);
                
                setTimeout(() => {
                    if (particle.parentNode) {
                        particle.parentNode.removeChild(particle);
                    }
                }, 3000);
            }, i * 200);
        }
    }

    playSuccessSound() {
        // Suono di successo (se disponibile)
        try {
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFApGn+DyvmUcBhGT1/LNeSsFJHfH8N+OPwgTXrTp66hVFA==');
            audio.volume = 0.3;
            audio.play().catch(() => {}); // Ignora errori audio
        } catch (e) {
            // Audio non supportato
        }
    }

    showLoading(message = 'Caricamento...') {
        // Implementa un overlay di caricamento
        const loading = document.createElement('div');
        loading.id = 'bonus-loading';
        loading.innerHTML = `
            <div class="loading-overlay">
                <div class="loading-content">
                    <div class="loading-spinner">⏳</div>
                    <div class="loading-text">${message}</div>
                </div>
            </div>
        `;
        loading.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
        `;
        document.body.appendChild(loading);
    }

    hideLoading() {
        const loading = document.getElementById('bonus-loading');
        if (loading) {
            loading.remove();
        }
    }

    showError(message) {
        alert(`❌ Errore: ${message}`);
    }
}

// Inizializza il gioco quando il DOM è pronto
document.addEventListener('DOMContentLoaded', function() {
    window.bonusGame = new BonusGame();
});

// Aggiungi animazioni CSS per le particelle celebrative
const style = document.createElement('style');
style.textContent = `
    @keyframes celebration {
        0% {
            opacity: 1;
            transform: translateY(0) scale(1) rotate(0deg);
        }
        100% {
            opacity: 0;
            transform: translateY(-200px) scale(0.5) rotate(360deg);
        }
    }
    
    .particle.celebration {
        font-size: 2rem;
        position: absolute;
        pointer-events: none;
        z-index: 1000;
    }
    
    .loading-overlay .loading-content {
        background: white;
        padding: 2rem;
        border-radius: 15px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    
    .loading-spinner {
        font-size: 2rem;
        margin-bottom: 1rem;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .game-complete-message {
        margin-top: 2rem;
        padding: 2rem;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-radius: 15px;
        text-align: center;
    }
    
    .completion-celebration h3 {
        margin-bottom: 1rem;
        font-size: 1.8rem;
    }
    
    .completion-celebration p {
        margin: 0.5rem 0;
        font-size: 1.1rem;
    }
`;
document.head.appendChild(style);
