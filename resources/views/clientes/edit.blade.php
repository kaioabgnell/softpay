@extends('layouts.app')

@section('title', 'Editar Cliente')
@section('page-title', 'Editar Cliente')

@section('content')
<div style="max-width:560px;">
    <div style="background:#ffffff; border-radius:12px; padding:32px; box-shadow:0 1px 2px rgba(0,0,0,.05); border:1px solid #e5e7eb;">
        <form method="POST" action="{{ route('clientes.update', $cliente) }}">
            @csrf
            @method('PUT')

            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; font-weight:500; color:#6b7280; margin-bottom:6px;" for="nome">Nome completo</label>
                <input type="text" id="nome" name="nome" value="{{ old('nome', $cliente->nome) }}" required
                       style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; font-family:inherit; outline:none; color:#111111;"
                       onfocus="this.style.borderColor='#111111'" onblur="this.style.borderColor='#e5e7eb'">
                @error('nome') <div style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; font-weight:500; color:#6b7280; margin-bottom:6px;" for="telefone">Telefone (E.164)</label>
                <input type="text" id="telefone" name="telefone" value="{{ old('telefone', $cliente->telefone) }}" required
                       style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; font-family:monospace; outline:none; color:#111111;"
                       onfocus="this.style.borderColor='#111111'" onblur="this.style.borderColor='#e5e7eb'">
                @error('telefone') <div style="color:#ef4444; font-size:12px; margin-top:4px;">{{ $message }}</div> @enderror
            </div>

            <div style="margin-bottom:28px; display:flex; align-items:center; gap:10px;">
                <input type="hidden" name="opt_out" value="0">
                <input type="checkbox" id="opt_out" name="opt_out" value="1" {{ $cliente->opt_out ? 'checked' : '' }}
                       style="width:16px; height:16px; accent-color:#111111; cursor:pointer;">
                <label for="opt_out" style="font-size:14px; color:#374151; cursor:pointer;">Opt-out (não recebe mensagens)</label>
            </div>

            <div style="display:flex; gap:10px;">
                <a href="{{ route('clientes.index') }}"
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
