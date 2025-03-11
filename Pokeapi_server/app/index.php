<?php
// Verificando se o formulário foi enviado
$pokemon = ''; // Variável que armazenará o valor enviado do formulário

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    // Verificando se o campo 'nome' foi preenchido
    if (!empty($_POST['nome'])) 
    {
        // Atribuindo o valor enviado para a variável
        $pokemon = $_POST['nome'];
    } 
    else 
    {
        $pokemon = 'Campo vazio';
    }
}

if($pokemon)
{
    // URL da API
    $url = "https://pokeapi.co/api/v2/pokemon/" . strtolower($pokemon);

    // Realizando a requisição GET
    $response = @file_get_contents($url);

    // Verificando se a requisição foi bem-sucedida
    if ($response === FALSE) 
    {
        echo "Pokémon não encontrado!";
        exit;
    }

    // Decodificando o JSON
    $data = json_decode($response);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokemon info</title>
</head>
<body>

    <h1>Escolha o seu pokemon</h1>

    <!-- Formulário para enviar dados -->
    <form action="index.php" method="POST">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>

        <input type="submit" value="Enviar">
    </form>

    <h2>Pokemon</h2>
    <p>Nome: <?php echo htmlspecialchars(ucfirst($data->name)); ?></p>
    <p>Altura: <?php echo htmlspecialchars($data->height); ?></p>
    <p>Peso: <?php echo htmlspecialchars($data->weight); ?></p>
    <p>Exp base: <?php echo htmlspecialchars($data->base_experience); ?></p>
    <p>Habilidades:
        <?php 
        echo "* ";
            foreach ($data->abilities as $ability) 
            {
                echo $ability->ability->name . " * \n";
            }
        ?>
    </p>

</body>
</html>