<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokemon Info</title>
</head>
<body>

    <h1>Pokemon escolhido</h1>

    @if (session('error'))
        <p style="color: red;">{{ session('error') }}</p>
    @endif

    @if ($pokemon ?? false)
        <h2>Informações do Pokémon</h2>
        <p>Nome: {{ ucfirst($pokemon->name) }}</p>
        <p>Altura: {{ $pokemon->height / 10 }} metros</p>
        <p>Peso: {{ $pokemon->weight / 10 }} kg</p>
        <p>Exp base: {{ $pokemon->base_experience }}</p>
        <p>Habilidades:</p>
        <ul>
            @foreach ($pokemon->abilities as $ability)
                <li>{{ $ability->ability->name }}</li>
            @endforeach
        </ul>
    @endif

    <!-- Botão para voltar ao diretório raiz ("/") -->
    <br>
    <a href="{{ url('/') }}">
        <button>Voltar</button>
    </a>

</body>
</html>
