<?php
// Inicia a sessão para armazenar e recuperar dados entre requisições
session_start();

// Verifica se a requisição foi feita via método POST
if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    // Verifica se o campo "nome" não está vazio e se não contém espaços
    if (!empty($_POST['nome']) && !str_contains($_POST['nome'], ' ')) 
    {
        // Inclui o arquivo da classe Pokemon para utilizar sua funcionalidade
        require_once 'pokemon.php';

        // Cria uma nova instância da classe Pokemon com o nome fornecido
        $pokemon = new Pokemon($_POST['nome']);

        // Se o Pokémon foi encontrado e o objeto foi criado corretamente
        if ($pokemon) 
        {
            // Serializa o objeto para armazená-lo na sessão
            $_SESSION['pokemon'] = serialize($pokemon);
        } 
        else 
        {
            // Caso o Pokémon não seja encontrado, define uma mensagem de erro na sessão
            $_SESSION['error'] = "Pokémon não encontrado!";
        }

        // Redireciona o usuário para a página de visualização (view.php)
        header("Location: view.php");
        exit;
    } 
    else 
    {
        // Caso o campo esteja vazio ou contenha espaços, define uma mensagem de erro
        $_SESSION['error'] = "Campo vazio!";
    }
    // Redireciona o usuário para a página de visualização (view.php)
    header("Location: view.php");
    exit;
}
?>
