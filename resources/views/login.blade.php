<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
@vite(['resources/css/login.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container">

        {{-- Header --}}
        <div class="header">
            <h1>Welcome<br><span>Back.</span></h1>
            <p>Sign in to your account</p>
        </div>

        {{-- Form card --}}
        <div class="form-wrapper">
            <p class="form-title">Credentials</p>

            <form action="/login/submit" method="POST">
                @csrf

                {{-- Email --}}
                <div class="field">
                    <label for="email">Email address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="you@example.com"
                        value="{{ old('email') }}"
                        class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                        required
                        autofocus
                    >
                    @error('email')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="field">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="············"
                        class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                        required
                    >
                    @error('password')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="divider"></div>

                <button type="submit" class="submit-btn">Log In →</button>
            </form>
        </div>

        {{-- Sign-up link --}}
        <div class="footer-note">
            Don't have an account?
            <a href="/signup">Sign up</a><br>
        </div>
        <div class="footer-note">
            <a href="/number/game/guest">Play as a Guest</a>
        </div>

    </div>
    <div style="position:fixed; bottom:1rem; right:1rem; font-size:0.6rem; color:var(--muted); letter-spacing:0.05em;">
    Developed by <a href="https://github.com/black-american-dev" target="_blank" style="color:var(--accent); text-decoration:none; font-weight:700;">@black-american-dev</a>
</div>
</body>
</html>