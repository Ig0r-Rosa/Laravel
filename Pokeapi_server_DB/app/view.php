<?php
session_start();

// Carrega a classe antes de desserializar o objeto
require_once 'pokemon.php';

$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$pokemonData = isset($_SESSION['pokemon']) ? unserialize($_SESSION['pokemon']) : null;

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

    <form action="index.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>
        <input type="submit" value="Enviar">
    </form>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <?php if ($pokemonData): ?>
        <h2>Informações do Pokémon</h2>
        <p>Nome: <?php echo htmlspecialchars(ucfirst($pokemonData->name)); ?></p>
        <p>Altura: <?php echo htmlspecialchars($pokemonData->height / 10); ?> metros</p>
        <p>Peso: <?php echo htmlspecialchars($pokemonData->weight / 10); ?> kg</p>
        <p>Exp base: <?php echo htmlspecialchars($pokemonData->base_experience); ?></p>
        <p>Habilidades:</p>
        <ul>
            <?php foreach ($pokemonData->abilities as $ability): ?>
                <li><?php echo htmlspecialchars($ability->ability->name); ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</body>
</html>