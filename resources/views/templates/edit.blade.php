@extends('layouts.app')

@section('title', 'Editar Template')
@section('page-title', 'Editar Template')

@section('content')
<div style="max-width:560px;">
    <div style="background:#ffffff; border-radius:12px; padding:32px; box-shadow:0 1px 2px rgba(0,0,0,.05); border:1px solid #e5e7eb;">
        <form method="POST" action="{{ route('templates.update', $template) }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; font-weight:500; color:#6b7280; margin-bottom:6px;" for="nome">Nome interno</label>
                <input type="text" id="nome" name="nome" value="{{ old('nome', $template->nome) }}" required maxlength="72"
                       style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; font-family:inherit; outline:none; color:#111111;"
                       onfocus="this.style.borderColor='#111111'" onblur="this.style.borderColor='#e5e7eb'">
                @error('nome') <div style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; font-weight:500; color:#6b7280; margin-bottom:6px;" for="meta_id">
                    Nome do template na Meta <span style="font-weight:400;">(exatamente como aparece em "Name" no Kapso — não é o ID numérico)</span>
                </label>
                <input type="text" id="meta_id" name="meta_id" value="{{ old('meta_id', $template->meta_id) }}" required maxlength="64" placeholder="template1"
                       style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; font-family:monospace; outline:none; color:#111111;"
                       onfocus="this.style.borderColor='#111111'" onblur="this.style.borderColor='#e5e7eb'">
                @error('meta_id') <div style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; font-weight:500; color:#6b7280; margin-bottom:6px;" for="idioma">Idioma <span style="font-weight:400;">(código do template na Meta)</span></label>
                <select id="idioma" name="idioma" required
                        style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; font-family:inherit; outline:none; color:#111111; background:#fff;">
                    @php $idioma = old('idioma', $template->idioma); @endphp
                    <option value="pt_BR" {{ $idioma === 'pt_BR' ? 'selected' : '' }}>Português (BR)</option>
                    <option value="en_US" {{ $idioma === 'en_US' ? 'selected' : '' }}>English (US)</option>
                    <option value="es_ES" {{ $idioma === 'es_ES' ? 'selected' : '' }}>Español (ES)</option>
                    <option value="es_MX" {{ $idioma === 'es_MX' ? 'selected' : '' }}>Español (MX)</option>
                </select>
                @error('idioma') <div style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; font-weight:500; color:#6b7280; margin-bottom:6px;" for="categoria">Categoria</label>
                <select id="categoria" name="categoria"
                        style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; font-family:inherit; outline:none; color:#111111; background:#fff;">
                    <option value="">— Selecione —</option>
                    <option value="marketing" {{ old('categoria', $template->categoria) === 'marketing' ? 'selected' : '' }}>Marketing</option>
                    <option value="utility" {{ old('categoria', $template->categoria) === 'utility' ? 'selected' : '' }}>Utility</option>
                    <option value="authentication" {{ old('categoria', $template->categoria) === 'authentication' ? 'selected' : '' }}>Authentication</option>
                </select>
            </div>

            <div style="margin-bottom:28px; display:flex; align-items:center; gap:10px;">
                <input type="hidden" name="usa_client_name" value="0">
                <input type="checkbox" id="usa_client_name" name="usa_client_name" value="1" {{ old('usa_client_name', $template->usa_client_name) ? 'checked' : '' }}
                       style="width:16px; height:16px; accent-color:#111111; cursor:pointer;">
                <label for="usa_client_name" style="font-size:14px; color:#374151; cursor:pointer;">
                    Usar <code style="background:#f5f5f5; padding:2px 6px; border-radius:4px; font-size:12px;">client_name</code> como variável
                </label>
            </div>

            <div style="display:flex; gap:10px;">
                <a href="{{ route('templates.index') }}"
                   style="flex:1; padding:11px; background:#fff; color:#374151; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; font-weight:600; text-align:center;">
                    Cancelar
                </a>
                <button type="submit"
                        style="flex:1; padding:11px; background:#111111; color:#fff; border:none; border-radius:8px; font-size:14px; font-weight:600; font-family:inherit; cursor:pointer;">
                    Salvar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
