<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Bandiere del Mondo</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/quiz.css') }}">
</head>
<body>
    <div class="container">
        <div class="text-center">
            <h1>🌍 Database Bandiere del Mondo</h1>
            <p>Esplora tutti i paesi del nostro database</p>
        </div>
        
        
        <!-- Statistiche -->
        <div class="stats-grid" style="margin-bottom: 30px;">
            <div class="stat-card">
                <div class="stat-icon">🌍</div>
                <div class="stat-number">{{ $countries->count() }}</div>
                <div class="stat-label">Paesi Totali</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⭐</div>
                <div class="stat-number">{{ $countries->where('difficulty_level', 1)->count() }}</div>
                <div class="stat-label">Livello Facile</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⭐⭐</div>
                <div class="stat-number">{{ $countries->where('difficulty_level', 2)->count() }}</div>
                <div class="stat-label">Livello Medio</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⭐⭐⭐</div>
                <div class="stat-number">{{ $countries->where('difficulty_level', 3)->count() }}</div>
                <div class="stat-label">Livello Difficile</div>
            </div>
        </div>
        
        <div class="quick-actions">
            <a href="{{ route('quiz.index') }}" class="btn btn-success">🎯 Inizia Quiz</a>
            <a href="{{ route('leaderboard.index') }}" class="btn btn-primary">🏆 Classifica</a>
        </div>
        <!-- Filtri -->
        <div class="form-group">
            <h3>🔍 Filtra per Continente</h3>
            <div class="options-grid">
                <button class="btn continent-filter active" data-continent="all">Tutti</button>
                @foreach($countries->groupBy('continent')->keys() as $continent)
                    <button class="btn continent-filter" data-continent="{{ $continent }}">
                        {{ $continent }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Tabella Paesi -->
        <div style="overflow-x: auto;">
            <table class="countries-table">
                <thead>
                    <tr>
                        <th>Bandiera</th>
                        <th>Nome</th>
                        <th>Capitale</th>
                        <th>Continente</th>
                        <th>Difficoltà</th>
                        <th>Codice</th>
                    </tr>
                </thead>
                <tbody id="countries-tbody">
                    @foreach($countries as $country)
                        <tr class="country-row" data-continent="{{ $country->continent }}">
                            <td>
                                <img src="{{ $country->flag_url }}" 
                                     alt="Bandiera {{ $country->name }}" 
                                     class="country-flag"
                                     onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iMjUiIHZpZXdCb3g9IjAgMCA0MCAyNSIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHJlY3Qgd2lkdGg9IjQwIiBoZWlnaHQ9IjI1IiBmaWxsPSIjZGRkIi8+Cjx0ZXh0IHg9IjIwIiB5PSIxNSIgZm9udC1zaXplPSI4IiBmaWxsPSIjOTk5IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIj5OL0E8L3RleHQ+Cjwvc3ZnPg=='">
                            </td>
                            <td><strong>{{ $country->name }}</strong></td>
                            <td>{{ $country->capital }}</td>
                            <td>{{ $country->continent }}</td>
                            <td>
                                <span class="difficulty-badge difficulty-{{ $country->difficulty_level }}">
                                    @for($i = 1; $i <= $country->difficulty_level; $i++)⭐@endfor
                                </span>
                            </td>
                            <td>{{ $country->code }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Azioni -->
    </div>

    <script>
        // Filtro per continente
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.continent-filter');
            const countryRows = document.querySelectorAll('.country-row');

            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const continent = this.getAttribute('data-continent');

                    // Aggiorna bottoni attivi
                    filterButtons.forEach(btn => btn.classList.remove('active'));
                    this.classList.add('active');

                    // Filtra righe
                    countryRows.forEach(row => {
                        if (continent === 'all' || row.getAttribute('data-continent') === continent) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>
