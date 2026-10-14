<x-guest-layout>
    <style>
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 22px;
            padding: 40px 36px 32px;
            box-shadow:
                0 30px 70px rgba(0, 0, 0, 0.35),
                0 0 0 1px rgba(255, 255, 255, 0.08);
            position: relative;
            overflow: hidden;
        }
        .login-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 5px;
            background: linear-gradient(90deg, #6366f1, #8b5cf6, #a855f7);
        }

        /* Brand / Logo */
        .brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 26px;
        }
        .brand-mark {
            width: 62px; height: 62px;
            border-radius: 18px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.7rem;
            box-shadow: 0 14px 32px rgba(99, 102, 241, 0.45);
            margin-bottom: 16px;
            animation: markPulse 3s ease-in-out infinite;
        }
        @keyframes markPulse {
            0%, 100% { box-shadow: 0 14px 32px rgba(99, 102, 241, 0.45); }
            50%      { box-shadow: 0 14px 40px rgba(139, 92, 246, 0.6); }
        }

        .brand h2 {
            font-size: 1.55rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin: 0 0 6px;
        }
        .brand p {
            font-size: 0.86rem;
            color: #64748b;
            margin: 0;
        }

        /* Session status */
        .session-status {
            background: #ecfdf5;
            color: #065f46;
            border-left: 4px solid #10b981;
            padding: 11px 15px;
            border-radius: 10px;
            font-size: 0.84rem;
            font-weight: 500;
            margin-bottom: 18px;
        }

        /* Inputs */
        .field { margin-bottom: 18px; }
        .field label {
            display: block;
            font-size: 0.82rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 7px;
        }
        .input-wrap { position: relative; }
        .input-wrap .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.95rem;
            pointer-events: none;
            transition: color 0.2s ease;
        }
        .input-wrap input {
            width: 100%;
            padding: 13px 44px 13px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.92rem;
            font-family: inherit;
            color: #0f172a;
            background: #f8fafc;
            transition: all 0.22s ease;
            outline: none;
        }
        .input-wrap input::placeholder { color: #94a3b8; }
        .input-wrap input:hover { border-color: #cbd5e1; }
        .input-wrap input:focus {
            border-color: #6366f1;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
        }
        .input-wrap input:focus ~ .input-icon,
        .input-wrap:focus-within .input-icon {
            color: #6366f1;
        }

        /* Password reveal button */
        .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
            transition: color 0.2s ease, background 0.2s ease;
            font-size: 0.9rem;
        }
        .toggle-password:hover { color: #6366f1; background: #eef2ff; }

        /* Error text */
        .field-error {
            display: block;
            font-size: 0.78rem;
            color: #dc2626;
            margin-top: 6px;
            font-weight: 500;
        }

        /* Forgot row */
        .row-between {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin: -4px 0 20px;
        }
        .row-between a {
            font-size: 0.83rem;
            color: #6366f1;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease;
        }
        .row-between a:hover { color: #4f46e5; text-decoration: underline; }

        /* Submit button */
        .btn-login {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 12px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            color: #fff;
            font-size: 0.95rem;
            font-weight: 700;
            font-family: inherit;
            letter-spacing: 0.01em;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;
            box-shadow: 0 12px 28px rgba(99, 102, 241, 0.4);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .btn-login::after {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.25), transparent);
            transition: left 0.5s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 18px 38px rgba(99, 102, 241, 0.55);
        }
        .btn-login:hover::after { left: 100%; }
        .btn-login:active { transform: translateY(0); }
        .btn-login i { font-size: 0.95rem; }

        /* Footer note */
        .login-footer {
            text-align: center;
            margin-top: 22px;
            font-size: 0.78rem;
            color: #94a3b8;
        }
        .login-footer strong { color: #64748b; font-weight: 600; }

        /* Responsive — iPhone SE (375px) */
        @media (max-width: 420px) {
            .login-card { padding: 32px 22px 26px; border-radius: 18px; }
            .brand h2 { font-size: 1.32rem; }
            .brand-mark { width: 54px; height: 54px; font-size: 1.5rem; }
            .input-wrap input { padding: 12px 40px 12px 40px; font-size: 0.9rem; }
            .btn-login { padding: 13px; font-size: 0.9rem; }
        }
    </style>

    <div class="login-card">
        {{-- Brand --}}
        <div class="brand">
            <div class="brand-mark">
                <i class="bi bi-grid-1x2-fill"></i>
            </div>
            <h2>Welcome Back</h2>
            <p>Sign in to your ERP workspace</p>
        </div>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="session-status">
                <i class="bi bi-check-circle-fill me-1"></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="field">
                <label for="email">Email Address</label>
                <div class="input-wrap">
                    <i class="bi bi-envelope-fill input-icon"></i>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="you@company.com"
                        required
                        autofocus
                        autocomplete="username">
                </div>
                @error('email')
                    <span class="field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span>
                @enderror
            </div>

            {{-- Password --}}
            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <i class="bi bi-lock-fill input-icon"></i>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        required
                        autocomplete="current-password">
                    <button type="button" class="toggle-password" onclick="togglePassword(this)" aria-label="Show password">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password')
                    <span class="field-error"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</span>
                @enderror
            </div>

            {{-- Forgot password --}}
            <div class="row-between">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Forgot your password?</a>
                @endif
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn-login">
                <i class="bi bi-box-arrow-in-right"></i>
                Sign In
            </button>
        </form>

        <div class="login-footer">
            <strong>{{ config('app.name', 'ERP') }}</strong> &nbsp;·&nbsp; Secure Access
        </div>
    </div>

    <script>
        function togglePassword(btn) {
            const input = btn.parentElement.querySelector('input');
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</x-guest-layout>