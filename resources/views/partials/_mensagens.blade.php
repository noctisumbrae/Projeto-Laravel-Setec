@if(session('sucesso'))
    <div class="alerta alerta--sucesso" role="status">{{ session('sucesso') }}</div>
@endif

@if(session('erro'))
    <div class="alerta alerta--erro" role="alert">{{ session('erro') }}</div>
@endif