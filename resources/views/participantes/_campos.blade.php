{{-- Campos compartilhados entre cadastro e edição de perfil. Variáveis: $user (opcional), $podeAlterarTipo --}}
@php
    $tipoAtual = old('tipo_participante', $user->tipo_participante ?? 'externo');
    $travarTipo = isset($podeAlterarTipo) && ! $podeAlterarTipo;
@endphp

<div class="campo {{ $errors->has('name') ? 'campo--erro' : '' }}">
    <label for="name">Nome completo</label>
    <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" autocomplete="name" required>
    @error('name') <div class="campo__erro">{{ $message }}</div> @enderror
</div>

<div class="dois">
    <div class="campo {{ $errors->has('email') ? 'campo--erro' : '' }}">
        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" autocomplete="email" required>
        @error('email') <div class="campo__erro">{{ $message }}</div> @enderror
    </div>

    <div class="campo {{ $errors->has('telefone') ? 'campo--erro' : '' }}">
        <label for="telefone">Telefone com DDD</label>
        <input type="tel" id="telefone" name="telefone" value="{{ old('telefone', $user->telefone ?? '') }}" placeholder="(18) 99999-0000" autocomplete="tel" required>
        @error('telefone') <div class="campo__erro">{{ $message }}</div> @enderror
    </div>
</div>

<div class="dois">
    <div class="campo {{ $errors->has('tipo_participante') ? 'campo--erro' : '' }}">
        <label for="tipo_participante">Você é</label>
        <select id="tipo_participante" name="tipo_participante" {{ $travarTipo ? 'disabled' : '' }}>
            <option value="aluno_fatec" @selected($tipoAtual === 'aluno_fatec')>Aluno da Fatec</option>
            <option value="externo" @selected($tipoAtual === 'externo')>Público externo</option>
        </select>
        @if($travarTipo)
            <div class="campo__ajuda">Não pode ser alterado depois que você faz uma inscrição.</div>
        @endif
        @error('tipo_participante') <div class="campo__erro">{{ $message }}</div> @enderror
    </div>

    <div class="campo {{ $errors->has('ra') ? 'campo--erro' : '' }}" id="grupo-ra">
        <label for="ra">RA (registro acadêmico)</label>
        <input type="text" id="ra" name="ra" value="{{ old('ra', $user->ra ?? '') }}" {{ $travarTipo ? 'disabled' : '' }}>
        @error('ra') <div class="campo__erro">{{ $message }}</div> @enderror
    </div>
</div>

@push('scripts')
<script>
    // Mostra o campo RA somente para aluno da Fatec.
    (function () {
        const tipo = document.getElementById('tipo_participante');
        const grupo = document.getElementById('grupo-ra');
        function atualizar() { grupo.hidden = tipo.value !== 'aluno_fatec'; }
        tipo.addEventListener('change', atualizar);
        atualizar();
    })();
</script>
@endpush
