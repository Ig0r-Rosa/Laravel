<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokemon Info</title>
</head>
<body>

    <h1>Escolha o seu Pokémon</h1>

    <!-- Formulário para entrada do nome do Pokémon -->
    <form action="/search" method="POST">
        @csrf
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>
        <input type="submit" value="Enviar">
    </form>

</body>
</html>
