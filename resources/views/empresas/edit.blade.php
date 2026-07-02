@extends('layouts.app')

@section('title', 'Configurações')
@section('page-title', 'Configurações')

@section('content')
<div style="max-width:560px;">
    <div style="background:#ffffff; border-radius:12px; padding:32px; box-shadow:0 1px 2px rgba(0,0,0,.05); border:1px solid #e5e7eb;">
        <form method="POST" action="{{ route('empresa.update') }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; font-weight:500; color:#6b7280; margin-bottom:6px;" for="whatsapp_numero">Número do WhatsApp</label>
                <input type="text" id="whatsapp_numero" name="whatsapp_numero" value="{{ old('whatsapp_numero', $empresa->whatsapp_numero) }}" maxlength="20" placeholder="+5511999999999"
                       style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; font-family:inherit; outline:none; color:#111111;"
                       onfocus="this.style.borderColor='#111111'" onblur="this.style.borderColor='#e5e7eb'">
                @error('whatsapp_numero') <div style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div style="margin-bottom:28px;">
                <label style="display:block; font-size:13px; font-weight:500; color:#6b7280; margin-bottom:6px;" for="kapso_sender_id">
                    Phone Number ID <span style="font-weight:400;">(WhatsApp Business, obtido no painel Kapso)</span>
                </label>
                <input type="text" id="kapso_sender_id" name="kapso_sender_id" value="{{ old('kapso_sender_id', $empresa->kapso_sender_id) }}" maxlength="255" placeholder="110987654321"
                       style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; font-family:monospace; outline:none; color:#111111;"
                       onfocus="this.style.borderColor='#111111'" onblur="this.style.borderColor='#e5e7eb'">
                @error('kapso_sender_id') <div style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
                <p style="font-size:12px; color:#6b7280; margin:6px 0 0;">Necessário para o envio de mensagens via Kapso.</p>
            </div>

            <button type="submit"
                    style="width:100%; padding:11px; background:#111111; color:#fff; border:none; border-radius:8px; font-size:14px; font-weight:600; font-family:inherit; cursor:pointer;">
                Salvar
            </button>
        </form>
    </div>
</div>
@endsection
