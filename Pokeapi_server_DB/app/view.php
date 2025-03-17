<?php
    // Inicia a sessão para armazenar dados entre requisições
    session_start();

    // Inclui o arquivo da classe Pokemon para garantir que a classe esteja carregada antes de desserializar o objeto
    require_once 'pokemon.php';

    // Recupera a mensagem de erro armazenada na sessão, se existir
    $error = isset($_SESSION['error']) ? $_SESSION['error'] : '';

    // Recupera os dados do Pokémon armazenados na sessão e desserializa para um objeto
    $pokemonData = isset($_SESSION['pokemon']) ? unserialize($_SESSION['pokemon']) : null;
    
    // Em caso de erros, se o nome for null ele também descosidera
    if ($error == "Pokémon não encontrado!"
    || $error == "Campo vazio!" 
    || $pokemonData == null 
    || $pokemonData->name == "" 
    || $pokemonData->name == null) {
        $error = "Pokémon não encontrado!";
        $pokemonData = null;
    }

    // Limpa os dados da sessão para evitar que sejam exibidos novamente em uma nova requisição
    unset($_SESSION['error']);
    unset($_SESSION['pokemon']);
?>

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
    <form action="index.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>
        <input type="submit" value="Enviar">
    </form>

    <!-- Exibe a mensagem de erro, caso exista -->
    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <!-- Exibe as informações do Pokémon, se ele foi encontrado -->
    <?php if ($pokemonData): ?>
        <h2>Informações do Pokémon</h2>
        <p>Nome: <?php echo htmlspecialchars(ucfirst($pokemonData->name)); ?></p>
        <p>Altura: <?php echo htmlspecialchars($pokemonData->height / 10); ?> metros</p>
        <p>Peso: <?php echo htmlspecialchars($pokemonData->weight / 10); ?> kg</p>
        <p>Exp base: <?php echo htmlspecialchars($pokemonData->base_experience); ?></p>
        <p>Habilidades:</p>
        <ul>
            <!-- Percorre a lista de habilidades e exibe cada uma -->
            <?php foreach ($pokemonData->abilities as $ability): ?>
                <li><?php echo htmlspecialchars($ability->ability->name); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</body>
</html>
