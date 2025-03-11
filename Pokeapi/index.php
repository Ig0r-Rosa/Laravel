<?php

echo "Digite o nome de um Pokémon: ";
$pokemon = trim(fgets(STDIN));  // Captura o input do usuário

$link = "https://pokeapi.co/api/v2/pokemon/" . strtolower($pokemon); // Corrigido: Concatenação com "."

$response = @file_get_contents($link); // Usa @ para evitar warnings caso o Pokémon não exista

if ($response === FALSE) {
    echo "Pokémon não encontrado!\n";
    exit;
}

$data = json_decode($response);

echo "Pokémon encontrado!\n";
echo "Nome: " . ucfirst($data->name) . "\n";
echo "Altura: " . $data->height . "\n";
echo "Peso: " . $data->weight . "\n";
echo "Experiência Base: " . $data->base_experience . "\n";
echo "Habilidades:\n";
foreach ($data->abilities as $ability) {
    echo "- " . $ability->ability->name . "\n";
}

echo "Tipos:\n";
foreach ($data->types as $type) {
    echo "- " . $type->type->name . "\n";
}

echo "Estatísticas:\n";
foreach ($data->stats as $stat) {
    echo "- " . $stat->stat->name . ": " . $stat->base_stat . "\n";
}

echo "Movimentos:\n";
foreach (array_slice($data->moves, 0, 5) as $move) { // Limita a 5 movimentos para não imprimir muitos
    echo "- " . $move->move->name . "\n";
}

?>