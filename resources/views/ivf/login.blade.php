<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — AURA IVF Reproductive Medicine Clinical OS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-page: #ffffff;
            --text-primary: #000000;
            --text-muted: #52525b;
            --border: #000000;
            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-sans);
            background: var(--bg-page);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 440px;
            border: 2px solid var(--border);
            padding: 2.25rem 2rem;
            background: #ffffff;
        }

        .auth-brand {
            margin-bottom: 2rem;
            border-bottom: 1px solid var(--border);
            padding-bottom: 1.25rem;
        }

        .brand-badge {
            display: inline-block;
            background: #000000;
            color: #ffffff;
            font-family: var(--font-mono);
            font-weight: 700;
            font-size: 13px;
            padding: 2px 7px;
            margin-bottom: 8px;
            letter-spacing: 0.05em;
        }

        .brand-title {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.02em;
            text-transform: uppercase;
        }

        .brand-sub {
            font-size: 11px;
            font-family: var(--font-mono);
            color: var(--text-muted);
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            font-size: 11.5px;
            font-weight: 700;
            font-family: var(--font-mono);
            text-transform: uppercase;
            margin-bottom: 6px;
            letter-spacing: 0.03em;
        }

        .form-control {
            width: 100%;
            padding: 10px 12px;
            font-family: var(--font-mono);
            font-size: 13px;
            border: 1px solid var(--border);
            background: #ffffff;
            color: #000000;
            outline: none;
            transition: border-color 0.15s ease;
        }

        .form-control:focus {
            border: 2px solid #000000;
            padding: 9px 11px;
        }

        .btn-submit {
            width: 100%;
            padding: 11px 16px;
            background: #000000;
            color: #ffffff;
            border: 2px solid #000000;
            font-family: var(--font-mono);
            font-size: 12.5px;
            font-weight: 700;
            text-transform: uppercase;
            cursor: pointer;
            letter-spacing: 0.05em;
            transition: all 0.15s ease;
            margin-top: 0.5rem;
        }

        .btn-submit:hover {
            background: #ffffff;
            color: #000000;
        }

        .alert-box {
            border: 1px solid var(--border);
            padding: 10px 12px;
            margin-bottom: 1.25rem;
            font-size: 12px;
            background: #fafafa;
            font-family: var(--font-mono);
        }

        .quick-accounts {
            margin-top: 2rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--border);
        }

        .quick-accounts-title {
            font-size: 10.5px;
            font-family: var(--font-mono);
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 8px;
            color: var(--text-muted);
        }

        .quick-btn {
            display: block;
            width: 100%;
            text-align: left;
            padding: 6px 10px;
            margin-bottom: 6px;
            background: #ffffff;
            border: 1px solid #d4d4d8;
            font-family: var(--font-mono);
            font-size: 11px;
            color: #000000;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .quick-btn:hover {
            border-color: #000000;
            background: #f4f4f5;
        }

        .quick-btn strong {
            display: inline-block;
            min-width: 130px;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-brand">
            <span class="brand-badge">AURA CLINICAL OS</span>
            <div class="brand-title">AURA IVF SYSTEM</div>
            <div class="brand-sub">Reproductive Medicine &amp; Assisted Conception</div>
        </div>

        @if(session('success'))
            <div class="alert-box">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-box">
                <strong>AUTHENTICATION FAILED:</strong>
                <ul style="margin-left: 1rem; margin-top: 4px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Staff Email / Username</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', 'admin@aura-ivf.com') }}" required autofocus autocomplete="username">
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" value="admin123" required autocomplete="current-password">
            </div>

            <div class="form-group" style="display: flex; align-items: center; gap: 8px;">
                <input type="checkbox" id="remember" name="remember" value="1" checked style="accent-color: #000000; cursor: pointer;">
                <label for="remember" style="font-size: 12px; cursor: pointer; user-select: none;">Keep me signed in on this workstation</label>
            </div>

            <button type="submit" class="btn-submit">Sign In to AURA IVF &rarr;</button>
        </form>

        <div class="quick-accounts">
            <div class="quick-accounts-title">Quick Demo Login Shortcuts:</div>
            <button type="button" class="quick-btn" onclick="fillLogin('admin@aura-ivf.com', 'admin123')">
                <strong>Dr. Elena Vance, MD</strong> Super Admin / Medical Director
            </button>
            <button type="button" class="quick-btn" onclick="fillLogin('embryo@aura-ivf.com', 'admin123')">
                <strong>Dr. Sarah Chen, PhD</strong> Chief Embryologist
            </button>
            <button type="button" class="quick-btn" onclick="fillLogin('obgyn@aura-ivf.com', 'admin123')">
                <strong>Dr. Rachel Morgan</strong> Consultant Obstetrician
            </button>
            <button type="button" class="quick-btn" onclick="fillLogin('nurse@aura-ivf.com', 'admin123')">
                <strong>Claire Dupont, RN</strong> Senior Clinical Nurse
            </button>
        </div>
    </div>

    <script>
        function fillLogin(email, pass) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = pass;
        }
    </script>
</body>
</html>

