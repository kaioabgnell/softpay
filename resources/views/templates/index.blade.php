@extends('layouts.app')

@section('title', 'Templates')
@section('page-title', 'Templates')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <p style="font-size:14px; color:#6b7280; margin:0;">{{ $templates->total() }} template(s) cadastrado(s)</p>
    <a href="{{ route('templates.create') }}"
       style="display:inline-flex; align-items:center; gap:8px; background:#111111; color:#ffffff; padding:10px 18px; border-radius:8px; font-size:14px; font-weight:600;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        Novo Template
    </a>
</div>

<div style="background:#ffffff; border-radius:12px; box-shadow:0 1px 2px rgba(0,0,0,.05); border:1px solid #e5e7eb; overflow:hidden;">
    @if($templates->isEmpty())
        <div style="padding:48px; text-align:center; color:#6b7280;">
            <svg width="40" height="40" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
            <p style="font-size:14px; margin:0 0 8px;">Nenhum template cadastrado ainda.</p>
            <a href="{{ route('templates.create') }}" style="color:#111111; font-weight:500; font-size:14px;">Adicionar o primeiro</a>
        </div>
    @else
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8f9fa; border-bottom:1px solid #e5e7eb;">
                    <th style="text-align:left; padding:12px 20px; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Nome</th>
                    <th style="text-align:left; padding:12px 20px; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Meta ID</th>
                    <th style="text-align:left; padding:12px 20px; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Client Name</th>
                    <th style="text-align:left; padding:12px 20px; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Categoria</th>
                    <th style="text-align:right; padding:12px 20px; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($templates as $template)
                <tr style="border-bottom:1px solid #e5e7eb;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
                    <td style="padding:14px 20px; font-size:14px; color:#111111; font-weight:500;">{{ $template->nome }}</td>
                    <td style="padding:14px 20px; font-size:13px; color:#374151; font-family:monospace; background:#f8f9fa;">{{ $template->meta_id }}</td>
                    <td style="padding:14px 20px;">
                        @if($template->usa_client_name)
                            <span style="display:inline-block; background:#f0fdf4; color:#166534; font-size:11px; font-weight:500; padding:3px 10px; border-radius:9999px;">sim</span>
                        @else
                            <span style="display:inline-block; background:#f5f5f5; color:#6b7280; font-size:11px; font-weight:500; padding:3px 10px; border-radius:9999px;">não</span>
                        @endif
                    </td>
                    <td style="padding:14px 20px; font-size:13px; color:#6b7280;">{{ $template->categoria ?: '—' }}</td>
                    <td style="padding:14px 20px; text-align:right;">
                        <div style="display:inline-flex; gap:6px; align-items:center;">
                            <a href="{{ route('templates.edit', $template) }}"
                               title="Editar"
                               style="width:36px; height:36px; border-radius:50%; border:1px solid #e5e7eb; background:#fff; display:inline-flex; align-items:center; justify-content:center; color:#374151;"
                               onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='#fff'">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('templates.destroy', $template) }}" style="display:inline;" onsubmit="return confirmarRemocao(event, 'Remover template?', 'Deseja remover o template {{ addslashes($template->nome) }}? Esta ação não pode ser desfeita.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        title="Deletar"
                                        style="width:36px; height:36px; border-radius:50%; border:1px solid #e5e7eb; background:#fff; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; color:#6b7280;"
                                        onmouseover="this.style.color='#ef4444'; this.style.borderColor='#fecaca'; this.style.background='#fef2f2'" onmouseout="this.style.color='#6b7280'; this.style.borderColor='#e5e7eb'; this.style.background='#fff'">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @if($templates->hasPages())
            <div style="padding:16px 20px; border-top:1px solid #e5e7eb;">
                {{ $templates->links() }}
            </div>
        @endif
    @endif
</div>
@endsection
