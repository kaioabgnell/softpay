@extends('layouts.app')

@section('title', 'Meu Perfil')
@section('page-title', 'Meu Perfil')

@section('content')
<div style="max-width:560px; display:flex; flex-direction:column; gap:24px;">

    {{-- Dados pessoais --}}
    <div style="background:#ffffff; border-radius:12px; padding:32px; box-shadow:0 1px 2px rgba(0,0,0,.05); border:1px solid #e5e7eb;">
        <h2 style="font-size:16px; font-weight:600; color:#111111; margin:0 0 4px;">Dados pessoais</h2>
        <p style="font-size:13px; color:#6b7280; margin:0 0 20px;">Atualize seu nome e e-mail de acesso.</p>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')

            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; font-weight:500; color:#6b7280; margin-bottom:6px;" for="name">Nome</label>
                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required maxlength="255" autocomplete="name"
                       style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; font-family:inherit; outline:none; color:#111111;"
                       onfocus="this.style.borderColor='#111111'" onblur="this.style.borderColor='#e5e7eb'">
                @error('name') <div style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:13px; font-weight:500; color:#6b7280; margin-bottom:6px;" for="email">E-mail</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required maxlength="255" autocomplete="username"
                       style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; font-family:inherit; outline:none; color:#111111;"
                       onfocus="this.style.borderColor='#111111'" onblur="this.style.borderColor='#e5e7eb'">
                @error('email') <div style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <button type="submit"
                    style="padding:11px 24px; background:#111111; color:#fff; border:none; border-radius:8px; font-size:14px; font-weight:600; font-family:inherit; cursor:pointer;">
                Salvar
            </button>
            @if (session('status') === 'profile-updated')
                <span style="margin-left:12px; font-size:13px; color:#166534;">Salvo.</span>
            @endif
        </form>
    </div>

    {{-- Senha --}}
    <div style="background:#ffffff; border-radius:12px; padding:32px; box-shadow:0 1px 2px rgba(0,0,0,.05); border:1px solid #e5e7eb;">
        <h2 style="font-size:16px; font-weight:600; color:#111111; margin:0 0 4px;">Alterar senha</h2>
        <p style="font-size:13px; color:#6b7280; margin:0 0 20px;">Use uma senha longa e difícil de adivinhar.</p>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; font-weight:500; color:#6b7280; margin-bottom:6px;" for="current_password">Senha atual</label>
                <input type="password" id="current_password" name="current_password" autocomplete="current-password"
                       style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; font-family:inherit; outline:none; color:#111111;"
                       onfocus="this.style.borderColor='#111111'" onblur="this.style.borderColor='#e5e7eb'">
                @error('current_password', 'updatePassword') <div style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; font-weight:500; color:#6b7280; margin-bottom:6px;" for="password">Nova senha</label>
                <input type="password" id="password" name="password" autocomplete="new-password"
                       style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; font-family:inherit; outline:none; color:#111111;"
                       onfocus="this.style.borderColor='#111111'" onblur="this.style.borderColor='#e5e7eb'">
                @error('password', 'updatePassword') <div style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div style="margin-bottom:24px;">
                <label style="display:block; font-size:13px; font-weight:500; color:#6b7280; margin-bottom:6px;" for="password_confirmation">Confirmar nova senha</label>
                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password"
                       style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; font-family:inherit; outline:none; color:#111111;"
                       onfocus="this.style.borderColor='#111111'" onblur="this.style.borderColor='#e5e7eb'">
                @error('password_confirmation', 'updatePassword') <div style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <button type="submit"
                    style="padding:11px 24px; background:#111111; color:#fff; border:none; border-radius:8px; font-size:14px; font-weight:600; font-family:inherit; cursor:pointer;">
                Atualizar senha
            </button>
            @if (session('status') === 'password-updated')
                <span style="margin-left:12px; font-size:13px; color:#166534;">Senha atualizada.</span>
            @endif
        </form>
    </div>

</div>
@endsection
