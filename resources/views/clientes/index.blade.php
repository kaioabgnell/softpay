@extends('layouts.app')

@section('title', 'Clientes')
@section('page-title', 'Clientes')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
    <div>
        <p style="font-size:14px; color:#6b7280; margin:0;">{{ $clientes->total() }} cliente(s) cadastrado(s)</p>
    </div>
    <a href="{{ route('clientes.create') }}"
       style="display:inline-flex; align-items:center; gap:8px; background:#111111; color:#ffffff; padding:10px 18px; border-radius:8px; font-size:14px; font-weight:600;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
        Novo Cliente
    </a>
</div>

<div style="background:#ffffff; border-radius:12px; box-shadow:0 1px 2px rgba(0,0,0,.05); border:1px solid #e5e7eb; overflow:hidden;">
    @if($clientes->isEmpty())
        <div style="padding:48px; text-align:center; color:#6b7280;">
            <svg width="40" height="40" fill="none" stroke="#d1d5db" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 12px;"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            <p style="font-size:14px; margin:0;">Nenhum cliente cadastrado ainda.</p>
            <a href="{{ route('clientes.create') }}" style="color:#111111; font-weight:500; font-size:14px;">Adicionar o primeiro</a>
        </div>
    @else
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8f9fa; border-bottom:1px solid #e5e7eb;">
                    <th style="text-align:left; padding:12px 20px; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Nome</th>
                    <th style="text-align:left; padding:12px 20px; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Telefone</th>
                    <th style="text-align:left; padding:12px 20px; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Status</th>
                    <th style="text-align:right; padding:12px 20px; font-size:12px; font-weight:600; color:#6b7280; text-transform:uppercase; letter-spacing:0.5px;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clientes as $cliente)
                <tr style="border-bottom:1px solid #e5e7eb;" onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='transparent'">
                    <td style="padding:14px 20px; font-size:14px; color:#111111; font-weight:500;">{{ $cliente->nome }}</td>
                    <td style="padding:14px 20px; font-size:14px; color:#374151; font-family:monospace;">{{ $cliente->telefone }}</td>
                    <td style="padding:14px 20px;">
                        @if($cliente->opt_out)
                            <span style="display:inline-block; background:#fef2f2; color:#991b1b; font-size:11px; font-weight:500; padding:3px 10px; border-radius:9999px;">opt-out</span>
                        @else
                            <span style="display:inline-block; background:#f0fdf4; color:#166534; font-size:11px; font-weight:500; padding:3px 10px; border-radius:9999px;">ativo</span>
                        @endif
                    </td>
                    <td style="padding:14px 20px; text-align:right;">
                        <div style="display:inline-flex; gap:6px; align-items:center;">
                            {{-- WhatsApp --}}
                            @if(!$cliente->opt_out && $templates->isNotEmpty())
                            <button onclick="abrirModalEnvio({{ $cliente->id }}, '{{ addslashes($cliente->nome) }}')"
                                    title="Enviar WhatsApp"
                                    style="width:36px; height:36px; border-radius:50%; border:1px solid #e5e7eb; background:#fff; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; color:#1FA855; transition:background .15s;"
                                    onmouseover="this.style.background='#f0fdf4'; this.style.borderColor='#1FA855'" onmouseout="this.style.background='#fff'; this.style.borderColor='#e5e7eb'">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </button>
                            @endif

                            {{-- Editar --}}
                            <a href="{{ route('clientes.edit', $cliente) }}"
                               title="Editar"
                               style="width:36px; height:36px; border-radius:50%; border:1px solid #e5e7eb; background:#fff; display:inline-flex; align-items:center; justify-content:center; color:#374151; transition:background .15s;"
                               onmouseover="this.style.background='#f5f5f5'" onmouseout="this.style.background='#fff'">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                            </a>

                            {{-- Deletar --}}
                            <form method="POST" action="{{ route('clientes.destroy', $cliente) }}" style="display:inline;" onsubmit="return confirmarRemocao(event, 'Remover cliente?', 'Deseja remover {{ addslashes($cliente->nome) }}? Esta ação não pode ser desfeita.')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        title="Deletar"
                                        style="width:36px; height:36px; border-radius:50%; border:1px solid #e5e7eb; background:#fff; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; color:#6b7280; transition:all .15s;"
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
        @if($clientes->hasPages())
            <div style="padding:16px 20px; border-top:1px solid #e5e7eb;">
                {{ $clientes->links() }}
            </div>
        @endif
    @endif
</div>

{{-- Modal Enviar Template --}}
<div id="modal-envio" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,.5); z-index:200; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:12px; padding:32px; width:100%; max-width:420px; box-shadow:0 20px 60px rgba(0,0,0,.15);">
        <h2 style="font-size:20px; font-weight:600; color:#111111; margin:0 0 6px; letter-spacing:-0.5px;">Enviar Template</h2>
        <p id="modal-cliente-nome" style="font-size:14px; color:#6b7280; margin:0 0 24px;"></p>

        <form id="form-envio" method="POST">
            @csrf
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; font-weight:500; color:#6b7280; margin-bottom:6px;">Selecione o template</label>
                <select name="template_id" required
                        style="width:100%; padding:10px 12px; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; font-family:inherit; outline:none; color:#111111; background:#fff; cursor:pointer;"
                        onfocus="this.style.borderColor='#111111'" onblur="this.style.borderColor='#e5e7eb'">
                    <option value="">— Escolha um template —</option>
                    @foreach($templates as $tpl)
                        <option value="{{ $tpl->id }}">{{ $tpl->nome }} ({{ $tpl->meta_id }})</option>
                    @endforeach
                </select>
            </div>

            <div style="display:flex; gap:10px;">
                <button type="button" onclick="fecharModal()"
                        style="flex:1; padding:11px; background:#fff; color:#374151; border:1px solid #e5e7eb; border-radius:8px; font-size:14px; font-weight:600; font-family:inherit; cursor:pointer;">
                    Cancelar
                </button>
                <button type="submit"
                        style="flex:1; padding:11px; background:#1FA855; color:#fff; border:none; border-radius:8px; font-size:14px; font-weight:600; font-family:inherit; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Enviar
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function abrirModalEnvio(clienteId, clienteNome) {
    document.getElementById('modal-cliente-nome').textContent = 'Para: ' + clienteNome;
    document.getElementById('form-envio').action = '/clientes/' + clienteId + '/enviar';
    var modal = document.getElementById('modal-envio');
    modal.style.display = 'flex';
}
function fecharModal() {
    document.getElementById('modal-envio').style.display = 'none';
}
document.getElementById('modal-envio').addEventListener('click', function(e) {
    if (e.target === this) fecharModal();
});
</script>
@endpush
