<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Entrar — {{ config('app.name', 'SoftPay') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        html, body { height: 100%; }
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            color: #374151;
        }

        .login-wrap {
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1.1fr 1fr;
        }

        /* ---------- Left: showcase ---------- */
        .showcase {
            position: relative;
            overflow: hidden;
            background: radial-gradient(circle at 20% 20%, #1a2332 0%, #0b0f19 55%, #05070c 100%);
            padding: 56px 64px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            color: #ffffff;
        }

        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.45;
            pointer-events: none;
        }
        .blob-1 { width: 380px; height: 380px; background: #22c55e; top: -120px; left: -100px; animation: float1 14s ease-in-out infinite; }
        .blob-2 { width: 300px; height: 300px; background: #3b82f6; bottom: -100px; right: -80px; animation: float2 16s ease-in-out infinite; }
        .blob-3 { width: 220px; height: 220px; background: #a855f7; top: 45%; left: 55%; animation: float3 18s ease-in-out infinite; opacity: 0.3; }

        @keyframes float1 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(40px, 60px) scale(1.1); }
        }
        @keyframes float2 {
            0%, 100% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-30px, -40px) scale(1.08); }
        }
        @keyframes float3 {
            0%, 100% { transform: translate(0, 0); }
            50% { transform: translate(-60px, 30px); }
        }

        .showcase-content { position: relative; z-index: 2; }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .brand-dot {
            width: 10px; height: 10px; border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 4px rgba(34,197,94,.18);
            animation: pulse 2.4s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { box-shadow: 0 0 0 4px rgba(34,197,94,.18); }
            50% { box-shadow: 0 0 0 8px rgba(34,197,94,.10); }
        }

        .headline {
            font-size: 34px;
            font-weight: 700;
            line-height: 1.25;
            letter-spacing: -0.8px;
            margin: 40px 0 12px;
            max-width: 460px;
        }
        .headline span { color: #22c55e; }

        .subhead {
            font-size: 15px;
            color: #9ca3af;
            max-width: 420px;
            line-height: 1.6;
            margin: 0 0 36px;
        }

        .features {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .feature {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            opacity: 0;
            animation: fadeInUp .6s ease forwards;
        }
        .feature:nth-child(1) { animation-delay: .15s; }
        .feature:nth-child(2) { animation-delay: .3s; }
        .feature:nth-child(3) { animation-delay: .45s; }
        .feature:nth-child(4) { animation-delay: .6s; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .feature-icon {
            flex-shrink: 0;
            width: 34px; height: 34px;
            border-radius: 9px;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.08);
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
        }
        .feature-title { font-size: 14px; font-weight: 600; color: #f3f4f6; margin: 0 0 2px; }
        .feature-desc { font-size: 13px; color: #9ca3af; margin: 0; line-height: 1.5; }

        .chat-mock {
            position: relative;
            z-index: 2;
            margin-top: 40px;
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 14px;
            padding: 16px;
            max-width: 320px;
            backdrop-filter: blur(6px);
            animation: floatCard 5s ease-in-out infinite;
        }
        @keyframes floatCard {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .chat-bubble {
            font-size: 12.5px;
            padding: 8px 12px;
            border-radius: 10px;
            margin-bottom: 8px;
            max-width: 85%;
            line-height: 1.4;
        }
        .chat-bubble.in { background: rgba(255,255,255,.08); color: #e5e7eb; border-bottom-left-radius: 2px; }
        .chat-bubble.out { background: #22c55e; color: #06210f; margin-left: auto; border-bottom-right-radius: 2px; font-weight: 500; }
        .chat-status { font-size: 11px; color: #6ee7b7; display: flex; align-items: center; gap: 6px; margin-top: 4px; }

        .showcase-footer {
            position: relative;
            z-index: 2;
            font-size: 12px;
            color: #6b7280;
        }

        /* ---------- Right: login form ---------- */
        .form-panel {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding: 28px 48px;
            background: #f8f9fa;
        }

        .form-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .mobile-brand {
            display: none;
            align-items: center;
            gap: 8px;
            font-size: 17px;
            font-weight: 700;
            color: #111111;
            letter-spacing: -0.4px;
        }
        .mobile-brand .brand-dot { width: 8px; height: 8px; border-radius: 50%; background: #22c55e; }
        .topbar-cta { font-size: 13px; color: #6b7280; }
        .topbar-cta a { color: #111111; font-weight: 600; text-decoration: none; }
        .topbar-cta a:hover { text-decoration: underline; }

        .form-center {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .form-box { width: 100%; max-width: 380px; }

        .login-card {
            background: #ffffff;
            border-radius: 14px;
            padding: 36px 32px;
            box-shadow: 0 4px 12px rgba(0,0,0,.06);
            border: 1px solid #e5e7eb;
        }

        .field { margin-bottom: 18px; }
        .field-label-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px; }
        .field label { font-size: 13px; font-weight: 500; color: #374151; }
        .field-link { font-size: 12.5px; color: #6b7280; text-decoration: none; }
        .field-link:hover { color: #111111; text-decoration: underline; }

        .input-wrap { position: relative; }
        .input-wrap svg.input-icon {
            position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
            width: 17px; height: 17px; color: #9ca3af; pointer-events: none;
        }
        .input-wrap input {
            width: 100%; padding: 10px 12px 10px 38px;
            border: 1px solid #e5e7eb; border-radius: 8px;
            font-size: 14px; font-family: inherit; outline: none; color: #111111;
            transition: border-color .15s, box-shadow .15s;
        }
        .input-wrap input:focus { border-color: #111111; box-shadow: 0 0 0 3px rgba(17,17,17,.06); }
        .input-wrap .toggle-pass {
            position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
            background: none; border: none; cursor: pointer; padding: 4px;
            color: #9ca3af; display: flex; line-height: 0;
        }
        .input-wrap .toggle-pass:hover { color: #6b7280; }

        .remember-row { display: flex; align-items: center; gap: 8px; margin-bottom: 22px; }
        .remember-row input[type="checkbox"] { width: 15px; height: 15px; accent-color: #111111; cursor: pointer; }
        .remember-row label { font-size: 13px; color: #6b7280; cursor: pointer; }

        .form-footer {
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            display: flex;
            justify-content: center;
            gap: 18px;
        }

        @media (max-width: 900px) {
            .login-wrap { grid-template-columns: 1fr; }
            .showcase { display: none; }
            .mobile-brand { display: flex; }
            .form-panel { padding: 20px 20px; }
        }
    </style>
</head>
<body>
    <div class="login-wrap">
        <div class="showcase">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            <div class="blob blob-3"></div>

            <div class="showcase-content">
                <div class="brand"><span class="brand-dot"></span> SoftPay</div>

                <h1 class="headline">Mensageria no WhatsApp <span>sem risco de bloqueio</span>.</h1>
                <p class="subhead">Plataforma multiempresa que envia mensagens ativas usando a API oficial do WhatsApp, com templates aprovados e total isolamento de dados por empresa.</p>

                <div class="features">
                    <div class="feature">
                        <div class="feature-icon">✅</div>
                        <div>
                            <p class="feature-title">API oficial via Kapso</p>
                            <p class="feature-desc">Sem números banidos, sem gambiarra — integração homologada com a Meta.</p>
                        </div>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">🏢</div>
                        <div>
                            <p class="feature-title">Multiempresa por padrão</p>
                            <p class="feature-desc">Cada empresa enxerga apenas seus próprios clientes, templates e mensagens.</p>
                        </div>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">📋</div>
                        <div>
                            <p class="feature-title">Templates aprovados pela Meta</p>
                            <p class="feature-desc">Envie campanhas ativas com modelos já validados, sem esperar aprovação manual.</p>
                        </div>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">🔒</div>
                        <div>
                            <p class="feature-title">Confiável e seguro</p>
                            <p class="feature-desc">Dados isolados por empresa e histórico completo de envios.</p>
                        </div>
                    </div>
                </div>

                <div class="chat-mock">
                    <div class="chat-bubble out">Seu pedido #4821 foi confirmado ✅</div>
                    <div class="chat-bubble in">Perfeito, obrigado!</div>
                    <div class="chat-status">🟢 Entregue via API oficial</div>
                </div>
            </div>

            <div class="showcase-footer">© {{ date('Y') }} SoftPay — WhatsApp Business Platform</div>
        </div>

        <div class="form-panel">
            <div class="form-topbar">
                <div class="mobile-brand"><span class="brand-dot"></span> SoftPay</div>
                <div class="topbar-cta" style="margin-left:auto;">
                    Não tem conta? <a href="{{ route('register') }}">Cadastre sua empresa</a>
                </div>
            </div>

            <div class="form-center">
                <div class="form-box">
                    <h2 style="font-size:26px; font-weight:700; color:#111111; margin:0 0 4px; letter-spacing:-0.6px;">Bem-vindo de volta</h2>
                    <p style="font-size:14px; color:#6b7280; margin:0 0 24px;">Acesse o painel da sua empresa</p>

                    @if(session('status'))
                        <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:14px;">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="login-card">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="field">
                                <div class="field-label-row">
                                    <label for="email">E-mail</label>
                                </div>
                                <div class="input-wrap">
                                    <svg class="input-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 5.5A1.5 1.5 0 0 1 4.5 4h11A1.5 1.5 0 0 1 17 5.5v9a1.5 1.5 0 0 1-1.5 1.5h-11A1.5 1.5 0 0 1 3 14.5v-9Z" stroke-linecap="round" stroke-linejoin="round"/><path d="m4 5.5 6 5 6-5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="voce@empresa.com">
                                </div>
                                @error('email')
                                    <div style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="field">
                                <div class="field-label-row">
                                    <label for="password">Senha</label>
                                    <a class="field-link" href="{{ route('password.request') }}">Esqueceu a senha?</a>
                                </div>
                                <div class="input-wrap">
                                    <svg class="input-icon" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="4" y="8.5" width="12" height="8" rx="1.5" stroke-linecap="round" stroke-linejoin="round"/><path d="M6.5 8.5V6a3.5 3.5 0 0 1 7 0v2.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    <input type="password" id="password" name="password" required placeholder="••••••••">
                                    <button type="button" class="toggle-pass" onclick="const p=document.getElementById('password'); const hidden=p.type==='password'; p.type=hidden?'text':'password'; document.getElementById('eye-open').style.display=hidden?'none':'block'; document.getElementById('eye-closed').style.display=hidden?'block':'none';">
                                        <svg id="eye-open" width="17" height="17" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M2.5 10S5.5 4.5 10 4.5 17.5 10 17.5 10 14.5 15.5 10 15.5 2.5 10 2.5 10Z" stroke-linecap="round" stroke-linejoin="round"/><circle cx="10" cy="10" r="2.2"/></svg>
                                        <svg id="eye-closed" style="display:none;" width="17" height="17" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M2.5 10S5.5 4.5 10 4.5 17.5 10 17.5 10 14.5 15.5 10 15.5 2.5 10 2.5 10Z" stroke-linecap="round" stroke-linejoin="round"/><circle cx="10" cy="10" r="2.2"/><line x1="3" y1="17" x2="17" y2="3" stroke-linecap="round"/></svg>
                                    </button>
                                </div>
                                @error('password')
                                    <div style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="remember-row">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">Lembrar de mim neste dispositivo</label>
                            </div>

                            <button type="submit"
                                    style="width:100%; padding:12px; background:#111111; color:#ffffff; border:none; border-radius:8px; font-size:14px; font-weight:600; font-family:inherit; cursor:pointer; letter-spacing:0.1px; transition:background .15s;"
                                    onmouseover="this.style.background='#242424'" onmouseout="this.style.background='#111111'">
                                Entrar
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="form-footer">
                <span>© {{ date('Y') }} SoftPay</span>
                <a href="#" class="field-link">Termos</a>
                <a href="#" class="field-link">Privacidade</a>
                <a href="#" class="field-link">Suporte</a>
            </div>
        </div>
    </div>
</body>
</html>
