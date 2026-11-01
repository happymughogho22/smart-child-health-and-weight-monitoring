
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | Smart Child Health Monitoring System</title>

    <style>
        :root {
            --navy: #062746;
            --navy-dark: #041d35;
            --navy-light: #0c3b60;

            --gold: #e7b923;
            --gold-dark: #c79612;

            --white: #ffffff;
            --cream: #fcfbf7;

            --text: #092c4c;
            --muted: #71839a;
            --border: #ccd7e3;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            min-height: 100%;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 35px 20px;

            font-family: "Segoe UI", Arial, Helvetica, sans-serif;
            color: var(--text);

            background:
                radial-gradient(
                    circle at 10% 10%,
                    rgba(231, 185, 35, 0.08),
                    transparent 28%
                ),
                radial-gradient(
                    circle at 90% 90%,
                    rgba(6, 39, 70, 0.07),
                    transparent 30%
                ),
                #eef2f6;
        }

        /* Main login card */

        .login-container {
            width: 100%;
            max-width: 560px;
            padding: 46px 52px;

            background: var(--cream);
            border: 1px solid #e8edf2;
            border-radius: 18px;

            box-shadow:
                0 25px 65px rgba(6, 39, 70, 0.12),
                0 5px 18px rgba(6, 39, 70, 0.04);
        }

        /* System branding */

        .system-brand {
            text-align: center;
            margin-bottom: 36px;
        }

        .brand-logo {
            width: 64px;
            height: 58px;
            margin: 0 auto 15px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: var(--white);
            border: 2px solid var(--gold);
            border-radius: 15px;

            color: var(--navy);
            font-size: 21px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .system-name {
            color: var(--navy);
            font-size: 14px;
            font-weight: 750;
            letter-spacing: 1.2px;
            line-height: 1.7;
            text-transform: uppercase;
        }

        .brand-description {
            margin-top: 5px;
            color: var(--muted);
            font-size: 12px;
        }

        .brand-line {
            width: 58px;
            height: 3px;
            margin: 20px auto 0;
            border-radius: 5px;
            background: var(--gold);
        }

        /* Login heading */

        .heading {
            margin-bottom: 9px;
            color: var(--navy);
            font-size: 38px;
            font-weight: 750;
            letter-spacing: -1.3px;
            line-height: 1.15;
        }

        .subtitle {
            margin-bottom: 32px;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.6;
        }

        /* Alerts */

        .alert {
            padding: 13px 15px;
            margin-bottom: 20px;
            border-radius: 9px;
            font-size: 13px;
            line-height: 1.6;
        }

        .alert-success {
            background: #edf9f2;
            border: 1px solid #ccebd8;
            color: #24784b;
        }

        .alert-error {
            background: #fff1f1;
            border: 1px solid #f1cccc;
            color: #a43c3c;
        }

        .error-list {
            margin: 0;
            padding-left: 18px;
        }

        /* Form */

        .form-group {
            margin-bottom: 23px;
        }

        .form-label {
            display: block;
            margin-bottom: 9px;

            color: var(--text);
            font-size: 13px;
            font-weight: 650;
        }

        .input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            height: 56px;
            padding: 0 48px 0 16px;

            border: 1px solid var(--border);
            border-radius: 10px;
            background: #ffffff;

            color: var(--text);
            font-family: inherit;
            font-size: 14px;

            outline: none;
            transition: all 0.2s ease;
        }

        .form-input::placeholder {
            color: #9aabba;
        }

        .form-input:focus {
            border-color: var(--navy);
            box-shadow: 0 0 0 3px rgba(6, 39, 70, 0.08);
        }

        /* Password visibility */

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 13px;
            transform: translateY(-50%);

            padding: 6px;
            border: none;
            background: transparent;

            color: var(--navy);
            font-size: 12px;
            font-weight: 650;
            cursor: pointer;
        }

        .password-toggle:hover {
            color: var(--gold-dark);
        }

        /* Remember me and security */

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;

            margin-bottom: 25px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 9px;

            color: #687e94;
            font-size: 12px;
            cursor: pointer;
        }

        .remember input {
            width: 17px;
            height: 17px;
            accent-color: var(--navy);
            cursor: pointer;
        }

        .protected {
            display: flex;
            align-items: center;
            gap: 7px;

            color: var(--gold-dark);
            font-size: 11px;
            font-weight: 650;
            white-space: nowrap;
        }

        /* Sign in button */

        .login-button {
            width: 100%;
            height: 56px;

            border: none;
            border-bottom: 4px solid var(--gold);
            border-radius: 10px;

            background: var(--navy);
            color: var(--white);

            font-family: inherit;
            font-size: 14px;
            font-weight: 700;

            cursor: pointer;
            transition: all 0.2s ease;
        }

        .login-button:hover {
            background: var(--navy-light);
            transform: translateY(-1px);

            box-shadow: 0 8px 20px rgba(6, 39, 70, 0.12);
        }

        .login-button:active {
            transform: translateY(0);
        }

        .button-arrow {
            margin-left: 8px;
            color: var(--gold);
            font-size: 18px;
        }

        /* Registration */

        .register-area {
            margin-top: 30px;
            padding-top: 23px;

            border-top: 1px solid #dce3e9;

            text-align: center;
            color: #74879a;
            font-size: 12px;
            line-height: 1.8;
        }

        .register-link {
            margin-left: 4px;
            color: var(--gold-dark);
            font-weight: 700;
            text-decoration: none;
        }

        .register-link:hover {
            color: #9c750d;
            text-decoration: underline;
        }

        /* Footer */

        .footer-note {
            margin-top: 24px;
            text-align: center;
            color: #91a0b1;
            font-size: 11px;
        }

        /* Responsive layout */

        @media (max-width: 600px) {
            body {
                padding: 18px 14px;
            }

            .login-container {
                max-width: 100%;
                padding: 34px 25px;
                border-radius: 15px;
            }

            .system-brand {
                margin-bottom: 30px;
            }

            .heading {
                font-size: 32px;
            }

            .subtitle {
                margin-bottom: 27px;
            }
        }

        @media (max-width: 360px) {
            .login-container {
                padding: 28px 18px;
            }

            .heading {
                font-size: 28px;
            }

            .form-options {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

    <main class="login-container">

        {{-- System branding --}}

        <header class="system-brand">

           <div class="brand-logo">
    SCMS
</div>
            <div class="system-name">
                Smart Child Health<br>
                Monitoring System
            </div>

            <p class="brand-description">
                Child health and growth monitoring
            </p>

            <div class="brand-line"></div>

        </header>

        {{-- Login section --}}

        <section>

            <h1 class="heading">
                Welcome back
            </h1>

            <p class="subtitle">
                Sign in to continue.
            </p>

            {{-- Registration success message --}}

            @if(session('registration_success'))
                <div class="alert alert-success" role="status">
                    {{ session('registration_success') }}
                </div>
            @endif

            {{-- Validation and authentication errors --}}

            @if($errors->any())
                <div class="alert alert-error" role="alert">
                    <ul class="error-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Login form --}}

            <form
                method="POST"
                action="{{ route('login.submit') }}"
            >
                @csrf

                {{-- Email address --}}

                <div class="form-group">

                    <label for="email" class="form-label">
                        Email Address
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-input"
                        value="{{ old('email') }}"
                        placeholder="Enter your email"
                        autocomplete="email"
                        required
                        autofocus
                    >

                </div>

                {{-- Password --}}

                <div class="form-group">

                    <label for="password" class="form-label">
                        Password
                    </label>

                    <div class="input-wrapper">

                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="form-input"
                            placeholder="Enter your password"
                            autocomplete="current-password"
                            required
                        >

                        <button
                            type="button"
                            class="password-toggle"
                            id="passwordToggle"
                            onclick="togglePassword()"
                            aria-label="Show password"
                            aria-pressed="false"
                        >
                            Show
                        </button>

                    </div>

                </div>

                {{-- Remember me and security notice --}}

                <div class="form-options">

                    <label class="remember">

                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            {{ old('remember') ? 'checked' : '' }}
                        >

                        <span>Remember me</span>

                    </label>

                    <div class="protected">
                        <span aria-hidden="true">&#9671;</span>
                        <span>Protected access</span>
                    </div>

                </div>

                {{-- Sign in button --}}

                <button type="submit" class="login-button">
                    Sign In
                    <span class="button-arrow" aria-hidden="true">
                        &rarr;
                    </span>
                </button>

            </form>

            {{-- Registration link --}}

            <div class="register-area">

                Don't have an account?

                <a
                    href="{{ route('register') }}"
                    class="register-link"
                >
                    Create an account &rarr;
                </a>

            </div>

        </section>

        <footer class="footer-note">
            Smart Child Health Monitoring System
        </footer>

    </main>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const button = document.getElementById('passwordToggle');

            const isHidden = password.type === 'password';

            password.type = isHidden ? 'text' : 'password';
            button.textContent = isHidden ? 'Hide' : 'Show';

            button.setAttribute(
                'aria-label',
                isHidden ? 'Hide password' : 'Show password'
            );

            button.setAttribute(
                'aria-pressed',
                isHidden ? 'true' : 'false'
            );
        }
    </script>

</body>
</html>
