
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Create Account | Smart Child Health and Weight Monitoring System</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Segoe UI", Arial, sans-serif;
            min-height: 100vh;
            background: #f4f6f9;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px;
        }

        .register-container {
            width: 100%;
            max-width: 1100px;
            min-height: 650px;
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 15px 45px rgba(0, 0, 0, 0.12);
            display: grid;
            grid-template-columns: 45% 55%;
        }

        /* LEFT BRANDING SECTION */

        .branding {
            background: #071a3d;
            color: white;
            padding: 55px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo {
            width: 65px;
            height: 65px;
            border-radius: 14px;
            background: #d4af37;
            color: #071a3d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 25px;
        }

        .branding h1 {
            font-size: 31px;
            line-height: 1.2;
            margin-bottom: 18px;
        }

        .branding h1 span {
            color: #d4af37;
        }

        .branding p {
            color: #d8dfec;
            line-height: 1.7;
            font-size: 15px;
            margin-bottom: 30px;
        }

        .features {
            list-style: none;
        }

        .features li {
            margin-bottom: 17px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #e9edf5;
            font-size: 14px;
        }

        .feature-icon {
            width: 30px;
            height: 30px;
            flex-shrink: 0;
            background: rgba(212, 175, 55, 0.15);
            color: #d4af37;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* RIGHT FORM SECTION */

        .register-section {
            padding: 45px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .register-section h2 {
            color: #071a3d;
            font-size: 30px;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #777;
            margin-bottom: 25px;
            font-size: 14px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            font-size: 14px;
            font-weight: 600;
            color: #273142;
        }

        .form-group input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #d8dce3;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            transition: 0.2s;
            background: #fff;
        }

        .form-group input:focus {
            border-color: #d4af37;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.12);
        }

        .form-group input.is-invalid {
            border-color: #dc3545;
        }

        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 70px;
        }

        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: none;
            color: #071a3d;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .error-message {
            margin-top: 6px;
            color: #dc3545;
            font-size: 12px;
            line-height: 1.5;
        }

        .general-errors {
            background: #fff1f2;
            border: 1px solid #fecdd3;
            color: #b91c1c;
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 20px;
            font-size: 13px;
            line-height: 1.6;
        }

        .general-errors ul {
            margin: 8px 0 0 18px;
        }

        .register-button {
            width: 100%;
            border: none;
            background: #071a3d;
            color: white;
            padding: 14px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 5px;
        }

        .register-button:hover {
            background: #0d2c63;
        }

        .login-prompt {
            text-align: center;
            margin-top: 22px;
            color: #777;
            font-size: 14px;
        }

        .login-prompt a {
            color: #b18d20;
            text-decoration: none;
            font-weight: 700;
        }

        .login-prompt a:hover {
            text-decoration: underline;
        }

        .footer-note {
            text-align: center;
            margin-top: 25px;
            font-size: 12px;
            color: #999;
        }

        /* RESPONSIVE DESIGN */

        @media (max-width: 850px) {
            body {
                padding: 15px;
                align-items: flex-start;
            }

            .register-container {
                grid-template-columns: 1fr;
                max-width: 600px;
                margin: auto;
            }

            .branding {
                padding: 35px 30px;
            }

            .branding h1 {
                font-size: 25px;
            }

            .branding p {
                margin-bottom: 20px;
            }

            .features li {
                margin-bottom: 10px;
            }

            .register-section {
                padding: 40px 30px;
            }
        }

        @media (max-width: 500px) {
            body {
                padding: 10px;
            }

            .register-container {
                border-radius: 12px;
            }

            .branding {
                padding: 30px 22px;
            }

            .register-section {
                padding: 30px 22px;
            }

            .register-section h2 {
                font-size: 25px;
            }
        }
    </style>
</head>

<body>

<div class="register-container">

    <!-- LEFT BRANDING SECTION -->

    <div class="branding">

        <div class="logo">SC</div>

        <h1>
            Smart Child <span>Health</span> and Weight Monitoring System
        </h1>

        <p>
            Create your account to access a secure platform for
            child health records, growth monitoring and nutrition
            assessment.
        </p>

        <ul class="features">
            <li>
                <span class="feature-icon">✓</span>
                Secure child health records
            </li>

            <li>
                <span class="feature-icon">✓</span>
                Growth and weight monitoring
            </li>

            <li>
                <span class="feature-icon">✓</span>
                Nutrition assessment and reports
            </li>

            <li>
                <span class="feature-icon">✓</span>
                Easy access to health information
            </li>
        </ul>

    </div>

    <!-- RIGHT REGISTRATION FORM -->

    <div class="register-section">

        <h2>Create Account</h2>

        <p class="subtitle">
            Register to access the monitoring system.
            Your account will require administrator approval.
        </p>

        <!-- DISPLAY ALL VALIDATION ERRORS -->

        @if ($errors->any())
            <div class="general-errors">
                <strong>Please correct the following errors:</strong>

                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}">
            @csrf

            <!-- FULL NAME -->

            <div class="form-group">
                <label for="name">Full Name</label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Enter your full name"
                    class="@error('name') is-invalid @enderror"
                    autocomplete="name"
                    maxlength="255"
                    required
                    autofocus
                >

                @error('name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- EMAIL ADDRESS -->

            <div class="form-group">
                <label for="email">Email Address</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter your email address"
                    class="@error('email') is-invalid @enderror"
                    autocomplete="email"
                    maxlength="255"
                    required
                >

                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- POSITION: REQUIRED BY AUTH CONTROLLER -->

            <div class="form-group">
                <label for="position">Position</label>

                <input
                    type="text"
                    id="position"
                    name="position"
                    value="{{ old('position') }}"
                    placeholder="e.g. Health Worker, Nurse"
                    class="@error('position') is-invalid @enderror"
                    maxlength="255"
                    autocomplete="organization-title"
                    required
                >

                @error('position')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- PASSWORD -->

            <div class="form-group">
                <label for="password">Password</label>

                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Create a password"
                        class="@error('password') is-invalid @enderror"
                        autocomplete="new-password"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        onclick="togglePassword('password', this)"
                        aria-label="Show password"
                    >
                        Show
                    </button>
                </div>

                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <!-- CONFIRM PASSWORD -->

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>

                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Confirm your password"
                        autocomplete="new-password"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        onclick="togglePassword('password_confirmation', this)"
                        aria-label="Show password confirmation"
                    >
                        Show
                    </button>
                </div>
            </div>

            <!-- SUBMIT -->

            <button type="submit" class="register-button">
                Create Account
            </button>

        </form>

        <!-- LOGIN LINK -->

        <div class="login-prompt">
            Already have an account?

            <a href="{{ route('login') }}">
                Sign in →
            </a>
        </div>

        <div class="footer-note">
            Smart Child Health and Weight Monitoring System
        </div>

    </div>

</div>

<script>
    function togglePassword(fieldId, button) {
        const field = document.getElementById(fieldId);

        if (field.type === "password") {
            field.type = "text";
            button.textContent = "Hide";
            button.setAttribute("aria-label", "Hide password");
        } else {
            field.type = "password";
            button.textContent = "Show";
            button.setAttribute("aria-label", "Show password");
        }
    }
</script>

</body>
</html>
