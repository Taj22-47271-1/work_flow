<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login · WorkTrack</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #ffffff;
            color: #0f172a;
            height: 100vh;
            display: flex;
        }

        .login-wrapper {
            display: flex;
            width: 100%;
            height: 100%;
        }

        /* LEFT HERO BANNER */
        .login-hero {
            flex: 1;
            background: linear-gradient(135deg, #07152b 0%, #0f2c5a 50%, #153e7e 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            padding: 40px;
            position: relative;
            overflow: hidden;
            text-align: center;
        }

        /* Subtle office overlay effect */
        .login-hero::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: radial-gradient(circle at center, rgba(29, 104, 245, 0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero-logo-box {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            backdrop-filter: blur(10px);
        }

        .hero-logo-box svg {
            width: 44px;
            height: 44px;
            color: #ffffff;
        }

        .hero-title {
            font-size: 32px;
            font-weight: 800;
            margin: 0 0 8px;
            letter-spacing: -0.5px;
        }

        .hero-subtitle {
            font-size: 14.5px;
            color: #cbd5e1;
            margin: 0 0 16px;
            font-weight: 500;
        }

        .hero-slogan {
            font-size: 12.5px;
            color: #94a3b8;
            letter-spacing: 1.5px;
            font-weight: 700;
            text-transform: uppercase;
        }

        /* RIGHT FORM */
        .login-form-side {
            flex: 1.1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            padding: 50px 40px 30px;
            background: #ffffff;
        }

        .form-box {
            width: 100%;
            max-width: 380px;
            margin: auto 0;
        }

        .form-title {
            font-size: 26px;
            font-weight: 800;
            margin: 0 0 6px;
            color: #0f172a;
            letter-spacing: -0.4px;
        }

        .form-subtitle {
            font-size: 13.5px;
            color: #64748b;
            margin: 0 0 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 12.5px;
            font-weight: 700;
            color: #334155;
            margin-bottom: 7px;
        }

        .form-control-wrap {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px;
            font-family: inherit;
            font-size: 13.5px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            outline: none;
            transition: all 0.15s ease;
        }

        .form-control:focus {
            border-color: #1d68f5;
            box-shadow: 0 0 0 3px rgba(29, 104, 245, 0.12);
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 12px;
            color: #94a3b8;
            cursor: pointer;
            border: 0;
            background: none;
            padding: 0;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            margin-bottom: 24px;
        }

        .remember-wrap {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #475569;
            cursor: pointer;
        }

        .forgot-link {
            color: #1d68f5;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-link:hover { text-decoration: underline; }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: #1d68f5;
            color: #ffffff;
            border: 0;
            border-radius: 8px;
            font-size: 14.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 2px 6px rgba(29, 104, 245, 0.25);
        }

        .btn-login:hover {
            background: #1555cb;
            transform: translateY(-1.5px);
            box-shadow: 0 5px 15px rgba(29, 104, 245, 0.38);
        }

        .btn-login:active {
            transform: translateY(0) scale(0.98);
        }

        .login-footer {
            font-size: 12px;
            color: #94a3b8;
            text-align: center;
        }

        @media (max-width: 820px) {
            .login-hero { display: none; }
            .login-form-side { flex: 1; padding: 30px 20px; }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <!-- LEFT HERO BANNER -->
        <div class="login-hero">
            <div class="hero-logo-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </div>
            <h1 class="hero-title">WorkTrack</h1>
            <p class="hero-subtitle">Employee Attendance Management System</p>
            <div class="hero-slogan">Track • Manage • Grow</div>
        </div>

        <!-- RIGHT LOGIN FORM -->
        <div class="login-form-side">
            <div class="form-box">
                <h2 class="form-title">Welcome Back</h2>
                <p class="form-subtitle">Please login to your account</p>

                <form method="GET" action="{{ route('dashboard') }}">
                    <div class="form-group">
                        <label>Email or Username</label>
                        <input type="text" class="form-control" placeholder="Enter your email or username" value="admin@worktrack.com" required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <div class="form-control-wrap">
                            <input type="password" id="passwordInput" class="form-control" placeholder="Enter your password" value="password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <label class="remember-wrap">
                            <input type="checkbox" checked>
                            Remember me
                        </label>
                        <a href="#" class="forgot-link" onclick="alert('Password reset link has been simulated.')">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn-login">Login</button>
                </form>
            </div>

            <div class="login-footer">
                &copy; 2026 WorkTrack. All rights reserved.
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('passwordInput');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>

