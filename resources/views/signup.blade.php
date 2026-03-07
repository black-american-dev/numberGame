<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
@vite(['resources/css/signup.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container">

        {{-- Header --}}
        <div class="header">
            <h1>Create<br><span>Account.</span></h1>
            <p>Start playing in seconds</p>
        </div>

        {{-- Form card --}}
        <div class="form-wrapper">
            <p class="form-title">Your Details</p>

            <form action="/signup" method="POST">
                @csrf

                {{-- Name --}}
                <div class="field">
                    <label for="name">Full name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="John Doe"
                        value="{{ old('name') }}"
                        class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                        required
                        autofocus
                    >
                    @error('name')
                        <span class="error-msg">{{ $message }}</span>
                    @enderror
                </div>

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

                <button type="submit" class="submit-btn">Create Account →</button>
            </form>
        </div>

        {{-- Login link --}}
        <div class="footer-note">
            Already have an account?
            <a href="/">Log in</a>
        </div>

    </div>
    <div style="position:fixed; bottom:1rem; right:1rem; font-size:0.6rem; color:var(--muted); letter-spacing:0.05em;">
    Developed by <a href="https://github.com/black-american-dev" target="_blank" style="color:var(--accent); text-decoration:none; font-weight:700;">@black-american-dev</a>
</div>
</body>
</html>