<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Number Game — Guest</title>
    @vite(['resources/css/guessedGame.css', 'resources/js/guessedGame.js'])
</head>
<body>
<div class="container">

    {{-- Login banner --}}
    <div class="login-banner">
        <p>Playing as <strong>Guest</strong> — your history won't be saved.</p>
        <div class="banner-btns">
            <a href="/"  class="banner-btn login">Log in</a>
            <a href="/signup" class="banner-btn signup">Sign up</a>
        </div>
    </div>

    {{-- Header --}}
    <div class="header">
        <div class="header-top">
            <h1>Number<br><span>Game.</span></h1>
            <span class="guest-tag">Guest Mode</span>
        </div>
        <p>Guess the 4-digit sequence</p>
        <a href="/number/game/guest" class="new-btn">↺ New Game</a>
    </div>

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

    {{-- History --}}
    <div class="history">
        @foreach ($history as $i => $item)
            <div class="history-row">
                <span class="attempt-label">#{{ $i + 1 }}</span>
                <div class="tile {{ $item[1][0] ?? 'none' }}">{{ $item[0][0] }}</div>
                <div class="tile {{ $item[1][1] ?? 'none' }}">{{ $item[0][1] }}</div>
                <div class="tile {{ $item[1][2] ?? 'none' }}">{{ $item[0][2] }}</div>
                <div class="tile {{ $item[1][3] ?? 'none' }}">{{ $item[0][3] }}</div>
            </div>
        @endforeach
    </div>

    @if (isset($history))

        {{-- Pip bar --}}
        <div class="attempts-bar">
            @for ($p = 0; $p < 5; $p++)
                <div class="attempt-pip {{ $p < count($history) ? 'used' : '' }}"></div>
            @endfor
        </div>

        @if (count($history) < 5 && !($won ?? false))

            {{-- Guess form --}}
            <div class="form-wrapper">
                <p class="form-title">Attempt {{ count($history) + 1 }} of 5</p>
                <form action="/number/game/guest" method="POST">
                    @csrf
                    <div class="inputs-grid">
                        <input type="number" name="first"  placeholder="·" min="1" max="9" required>
                        <input type="number" name="second" placeholder="·" min="1" max="9" required>
                        <input type="number" name="third"  placeholder="·" min="1" max="9" required>
                        <input type="number" name="fourth" placeholder="·" min="1" max="9" required>
                    </div>
                    <button class="submit-btn">Submit Guess →</button>
                </form>
            </div>

        @else

            {{-- End screen --}}
            <div class="end-screen">
                @if ($won ?? false)
                    <h2 class="won">You cracked it! 🎯</h2>
                    <p>Solved in {{ count($history) }} {{ count($history) === 1 ? 'attempt' : 'attempts' }}</p>
                @else
                    <h2 class="lost">Game Over.</h2>
                    <p>The sequence was:</p>
                    <div class="answer-reveal">
                        @foreach ($listNumbers as $n)
                            <div class="answer-tile">{{ $n }}</div>
                        @endforeach
                    </div>
                @endif
                <div class="end-btns">
                    <a href="/number/game/guest" class="start-btn">Play Again</a>
                    <a href="/"      class="login-btn">Log In →</a>
                </div>
            </div>

        @endif

        {{-- Save progress note --}}
        @if (count($history) > 0 && !($won ?? false) && count($history) < 5)
            <div class="save-note">
                Want to save your progress and track your games?
                <a href="/signup">Create a free account</a> or <a href="/">log in</a>.
            </div>
        @endif

    @endif

</div>
<div style="position:fixed; bottom:1rem; right:1rem; font-size:0.6rem; color:var(--muted); letter-spacing:0.05em;">
    Developed by <a href="https://github.com/black-american-dev" target="_blank" style="color:var(--accent); text-decoration:none; font-weight:700;">@black-american-dev</a>
</div>
</body>
</html>