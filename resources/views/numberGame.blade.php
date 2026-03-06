<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Number Game</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/game.js'])
</head>
<body>

<button class="mobile-toggle" onclick="document.querySelector('.sidebar').classList.toggle('open')">☰</button>

<div class="layout">

    {{-- ─── SIDEBAR ─── --}}
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="sidebar-logo">Number<span>Game.</span></div>
        </div>

        <div class="profile-card">
            <div class="avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div class="profile-info">
                <div class="profile-name">{{ $user->name }}</div>
                <div class="profile-email">{{ $user->email }}</div>
            </div>
        </div>

        <div class="stats-row">
            <div class="stat-cell">
                <div class="stat-value">{{ $allGames->count() }}</div>
                <div class="stat-label">Played</div>
            </div>
            <div class="stat-cell">
                <div class="stat-value green">{{ $allGames->where('status', 'won')->count() }}</div>
                <div class="stat-label">Won</div>
            </div>
            <div class="stat-cell">
                <div class="stat-value orange">{{ $allGames->where('status', 'lost')->count() }}</div>
                <div class="stat-label">Lost</div>
            </div>
        </div>

        <p class="sidebar-section-title">Game History</p>
        <div class="games-list">
            @forelse ($allGames->sortByDesc('created_at') as $g)
                <div class="game-item {{ isset($currentGame) && $currentGame->id === $g->id ? 'active' : '' }}">
                    <div class="game-status-dot {{ $g->status }}"></div>
                    <div class="game-item-info">
                        <div class="game-item-title">
                            Game #{{ $g->id }}
                            <span style="font-size:0.55rem; margin-left:0.3rem;
                                color: {{ $g->difficulty === 'medium' ? 'var(--orange)' : ($g->difficulty === 'hard' ? 'var(--red)' : 'var(--green)') }}">
                                {{ strtoupper($g->difficulty ?? 'EASY') }}
                            </span>
                        </div>
                        <div class="game-item-meta">
                            {{ $g->attempts->count() }} attempt{{ $g->attempts->count() !== 1 ? 's' : '' }}
                            · {{ $g->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <div class="game-item-badge {{ $g->status }}">{{ $g->status }}</div>
                </div>
            @empty
                <div style="padding:1rem 0.75rem; font-size:0.65rem; color:var(--muted);">No games yet.</div>
            @endforelse
        </div>

        <div class="sidebar-footer">
            <a href="/number/game/pick" class="sidebar-btn">↺ New</a>
            <a href="/logout" class="sidebar-btn logout">⏻ Logout</a>
        </div>
    </aside>

    {{-- ─── MAIN ─── --}}
    <main class="main">
        <div class="container">

            <div class="header">
                <div class="header-top">
                    <h1>Number<br><span>Game.</span></h1>
                    <div class="greeting">
                        Playing as<br>
                        <strong>{{ $user->name }}</strong>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════
                SCREEN 1 — DIFFICULTY PICKER
                Shown when no active game yet
            ══════════════════════════════════════ --}}
            @if (!isset($currentGame))

                <p class="pick-title">Choose your difficulty</p>

                <div class="diff-cards">
                    <a href="/number/game?difficulty=easy" class="diff-card easy">
                        <div class="diff-card-left">
                            <div class="diff-card-name">Easy</div>
                            <div class="diff-card-desc">5 digits · 5 attempts</div>
                        </div>
                        <div class="diff-card-right">5</div>
                    </a>
                    <a href="/number/game?difficulty=medium" class="diff-card medium">
                        <div class="diff-card-left">
                            <div class="diff-card-name">Medium</div>
                            <div class="diff-card-desc">7 digits · 7 attempts</div>
                        </div>
                        <div class="diff-card-right">7</div>
                    </a>
                    <a href="/number/game?difficulty=hard" class="diff-card hard">
                        <div class="diff-card-left">
                            <div class="diff-card-name">Hard</div>
                            <div class="diff-card-desc">10 digits · 10 attempts</div>
                        </div>
                        <div class="diff-card-right">10</div>
                    </a>
                </div>

            {{-- ══════════════════════════════════════
                SCREEN 2 — ACTIVE GAME
                Shown when a game is running
            ══════════════════════════════════════ --}}
            @else

                {{-- Subheader --}}
                <p style="font-size:0.75rem; color:var(--muted); letter-spacing:0.08em; text-transform:uppercase; margin-bottom:1.5rem;">
                    {{ count($listNumbers) }} digits · {{ strtoupper($difficulty) }} · guess the sequence
                </p>

                {{-- Legend --}}
                <div class="legend">
                    <div class="legend-item">
                        <div class="legend-dot" style="background:var(--green)"></div>
                        Correct position
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot" style="background:var(--orange)"></div>
                        Wrong position
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot" style="background:var(--gray)"></div>
                        Not in sequence
                    </div>
                </div>

                {{-- History rows --}}
                <div class="history">
                    @foreach ($history as $i => $item)
                        <div class="history-row">
                            <span class="attempt-label">#{{ $i + 1 }}</span>
                            @foreach ($item[0] as $j => $num)
                                <div class="tile {{ $item[1][$j] ?? 'none' }}">{{ $num }}</div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                {{-- Pip bar --}}
                <div class="attempts-bar">
                    @for ($p = 0; $p < $maxAttempts; $p++)
                        <div class="attempt-pip {{ $p < count($history) ? 'used' : '' }}"></div>
                    @endfor
                </div>

                {{-- Still playing --}}
                @if (count($history) < $maxAttempts && !($won ?? false))
                    <div class="form-wrapper">
                        <p class="form-title">
                            Attempt {{ count($history) + 1 }} of {{ $maxAttempts }}
                        </p>
                        <form action="/number/game" method="POST">
                            @csrf
                            @php
                                $inputNames = ['first','second','third','fourth','fifth','sixth','seventh','eighth','ninth','tenth'];
                                $cols = count($listNumbers);
                            @endphp
                            <div class="inputs-grid" style="grid-template-columns: repeat({{ $cols }}, 1fr);">
                                @for ($n = 0; $n < $cols; $n++)
                                    <input type="number"
                                        name="{{ $inputNames[$n] }}"
                                        placeholder="·"
                                        min="1" max="9"
                                        required>
                                @endfor
                            </div>
                            <button class="submit-btn">Submit Guess →</button>
                        </form>
                    </div>

                {{-- Game ended --}}
                @else
                    <div class="end-screen">
                        @if ($won ?? false)
                            <h2 class="won">You cracked it! 🎯</h2>
                            <p>Solved in {{ count($history) }} {{ count($history) === 1 ? 'attempt' : 'attempts' }} on {{ strtoupper($difficulty) }}</p>
                        @else
                            <h2 class="lost">Game Over.</h2>
                            <p>The sequence was:</p>
                            <div class="answer-reveal">
                                @foreach ($listNumbers as $n)
                                    <div class="answer-tile">{{ $n }}</div>
                                @endforeach
                            </div>
                        @endif
                        <a href="/number/game/pick" class="start-btn">Play Again</a>
                    </div>
                @endif

            @endif

        </div>
    </main>

</div>
<div style="position:fixed; bottom:1rem; right:1rem; font-size:0.6rem; color:var(--muted); letter-spacing:0.05em;">
    Developed by <a href="https://github.com/black-american-dev" target="_blank" style="color:var(--accent); text-decoration:none; font-weight:700;">@black-american-dev</a>
</div>

<script src="{{ asset('js/game.js') }}"></script>

</body>
</html>