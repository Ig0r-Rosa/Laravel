@if(!session('nexti_auth.access_token'))
<script>window.location = "{{ route('auth.form') }}";</script>
@endif
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Status da Autenticação</div>

                <div class="card-body">
                    <div class="alert alert-success">
                        Autenticado com sucesso!
                    </div>

                    <div class="mb-3">
                        <strong>Token de Acesso:</strong>
                        <div class="token-display p-2 bg-light rounded">
                            {{ $token }}
                        </div>
                    </div>

                    <div id="tokenTimer" class="mb-3">
                        <strong>Tempo restante:</strong>
                        <span id="timeRemaining">{{ $expiry - time() }}</span> segundos
                    </div>

                    <a href="{{ route('auth.form') }}" class="btn btn-secondary">
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateTimer() {
        const timeRemainingElement = document.getElementById('timeRemaining');
        let timeRemaining = parseInt(timeRemainingElement.textContent);
        
        if (timeRemaining <= 0)
        {
            // Token expirado, recarregar a página para forçar nova autenticação
            window.location.reload();
            return;
        }
        
        timeRemainingElement.textContent = timeRemaining - 1;
        
        // Se faltar 10 segundos ou menos, atualizar o token
        if (timeRemaining <= 10) 
        {
            fetch("{{ route('auth.refresh') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Atualizar o tempo restante com o novo tempo de expiração
                    timeRemainingElement.textContent = data.time_remaining;
                }
            });
        }
    }

    // Atualizar o timer a cada segundo
    setInterval(updateTimer, 1000);
</script>
@endsection