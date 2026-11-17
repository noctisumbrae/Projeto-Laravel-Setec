{{-- Selo de vagas de uma atividade (espera $atividade carregada com comOcupacao()) --}}
@if($atividade->vagas_disponiveis <= 0)
    <span class="selo selo--vermelho">Lotada</span>
@elseif($atividade->vagas_disponiveis <= 5)
    <span class="selo selo--amarelo">{{ $atividade->vagas_disponiveis }} {{ $atividade->vagas_disponiveis === 1 ? 'vaga' : 'vagas' }}</span>
@else
    <span class="selo selo--verde">{{ $atividade->vagas_disponiveis }} vagas</span>
@endif
