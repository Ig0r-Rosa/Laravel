<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    if (!empty($_POST['nome']) && !str_contains($_POST['nome'], ' ')) 
    {

        require_once 'pokemon.php';

        $pokemon = new Pokemon($_POST['nome']);

        if ($pokemon) 
        {
            $_SESSION['pokemon'] = serialize($pokemon); // Serializa antes de armazenar
        } 
        else 
        {
            $_SESSION['error'] = "Pokémon não encontrado!";
        }

        header("Location: view.php");
        exit;
    } 
    else 
    {
        $_SESSION['error'] = "Campo vazio!";
        header("Location: view.php");
        exit;
    }
}
?>