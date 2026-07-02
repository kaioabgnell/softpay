<x-guest-layout>
    <h2 style="font-size:24px; font-weight:600; color:#111111; margin:0 0 4px; letter-spacing:-0.5px;">Entrar</h2>
    <p style="font-size:14px; color:#6b7280; margin:0 0 24px;">Acesse o painel da sua empresa</p>

    @if(session('status'))
        <div style="background:#f0fdf4; border:1px solid #bbf7d0; color:#166534; padding:12px 16px; border-radius:8px; margin-bottom:16px; font-size:14px;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div style="margin-bottom:16px;">
            <label style="display:block; font-size:13px; font-weight:500; color:#6b7280; margin-bottom:6px;" for="email">E-mail</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                   style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; font-family:inherit; outline:none; color:#111111; transition:border-color .15s;"
                   onfocus="this.style.borderColor='#111111'" onblur="this.style.borderColor='#e5e7eb'">
            @error('email')
                <div style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <div style="margin-bottom:24px;">
            <label style="display:block; font-size:13px; font-weight:500; color:#6b7280; margin-bottom:6px;" for="password">Senha</label>
            <input type="password" id="password" name="password" required
                   style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; font-family:inherit; outline:none; color:#111111; transition:border-color .15s;"
                   onfocus="this.style.borderColor='#111111'" onblur="this.style.borderColor='#e5e7eb'">
            @error('password')
                <div style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit"
                style="width:100%; padding:11px; background:#111111; color:#ffffff; border:none; border-radius:8px; font-size:14px; font-weight:600; font-family:inherit; cursor:pointer; letter-spacing:0.1px; transition:background .15s;"
                onmouseover="this.style.background='#242424'" onmouseout="this.style.background='#111111'">
            Entrar
        </button>

        <div style="text-align:center; margin-top:20px; font-size:13px; color:#6b7280;">
            Não tem conta? <a href="{{ route('register') }}" style="color:#111111; font-weight:500;">Cadastre sua empresa</a>
        </div>
    </form>
</x-guest-layout>
