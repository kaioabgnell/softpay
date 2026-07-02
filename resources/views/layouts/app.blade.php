<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SoftPay')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; margin: 0; background: #f8f9fa; color: #374151; }
        a { text-decoration: none; }
    </style>
</head>
<body>
<div style="display:flex; min-height:100vh;">

    {{-- SIDEBAR --}}
    <aside style="width:240px; min-height:100vh; background:#f8f9fa; border-right:1px solid #e5e7eb; display:flex; flex-direction:column; position:fixed; top:0; left:0; bottom:0; z-index:100;">
        <div style="padding:28px 24px 20px; border-bottom:1px solid #e5e7eb;">
            <div style="font-size:20px; font-weight:600; color:#111111; letter-spacing:-0.5px;">SoftPay</div>
            <div style="font-size:11px; color:#6b7280; margin-top:2px; font-weight:500;">WhatsApp Business</div>
        </div>
        <nav style="padding:16px 12px; flex:1;">
            <a href="{{ route('clientes.index') }}"
               style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:8px; font-size:14px; font-weight:500; margin-bottom:4px; color:{{ request()->routeIs('clientes.*') ? '#111111' : '#6b7280' }}; background:{{ request()->routeIs('clientes.*') ? '#ffffff' : 'transparent' }}; box-shadow:{{ request()->routeIs('clientes.*') ? '0 1px 2px rgba(0,0,0,.06)' : 'none' }};">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Clientes
            </a>
            <a href="{{ route('templates.index') }}"
               style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:8px; font-size:14px; font-weight:500; margin-bottom:4px; color:{{ request()->routeIs('templates.*') ? '#111111' : '#6b7280' }}; background:{{ request()->routeIs('templates.*') ? '#ffffff' : 'transparent' }}; box-shadow:{{ request()->routeIs('templates.*') ? '0 1px 2px rgba(0,0,0,.06)' : 'none' }};">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                Templates
            </a>
            <a href="{{ route('empresa.edit') }}"
               style="display:flex; align-items:center; gap:10px; padding:10px 12px; border-radius:8px; font-size:14px; font-weight:500; margin-bottom:4px; color:{{ request()->routeIs('empresa.*') ? '#111111' : '#6b7280' }}; background:{{ request()->routeIs('empresa.*') ? '#ffffff' : 'transparent' }}; box-shadow:{{ request()->routeIs('empresa.*') ? '0 1px 2px rgba(0,0,0,.06)' : 'none' }};">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                Configurações
            </a>
        </nav>
        <div style="padding:16px; border-top:1px solid #e5e7eb;">
            <a href="{{ route('profile.edit') }}" style="display:block;">
                <div style="font-size:13px; font-weight:500; color:#111111; margin-bottom:2px;">{{ auth()->user()->name }}</div>
                <div style="font-size:12px; color:#6b7280; margin-bottom:10px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ auth()->user()->empresa->nome ?? '—' }}</div>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="font-size:12px; color:#6b7280; background:none; border:none; cursor:pointer; padding:0; font-family:inherit;">Sair da conta</button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <main style="flex:1; margin-left:240px; min-height:100vh; display:flex; flex-direction:column;">
        <header style="height:64px; background:#ffffff; border-bottom:1px solid #e5e7eb; display:flex; align-items:center; padding:0 32px; position:sticky; top:0; z-index:50; gap:16px;">
            <h1 style="font-size:18px; font-weight:600; color:#111111; margin:0; letter-spacing:-0.3px;">@yield('page-title')</h1>
            <div style="margin-left:auto; display:flex; align-items:center; gap:12px;">
                <span style="font-size:13px; color:#6b7280;">{{ auth()->user()->empresa->nome ?? '' }}</span>
            </div>
        </header>

        <div style="padding:32px; flex:1;">
            @if(session('sucesso'))
                <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px; display:flex; align-items:center; gap:8px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                    {{ session('sucesso') }}
                </div>
            @endif
            @if(session('erro'))
                <div style="background:#fef2f2; border:1px solid #fecaca; color:#991b1b; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:14px; display:flex; align-items:center; gap:8px;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                    {{ session('erro') }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>

@stack('scripts')
</body>
</html>
