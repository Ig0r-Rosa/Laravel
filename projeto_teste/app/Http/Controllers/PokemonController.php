<?php

namespace App\Http\Controllers;

use App\Models\Pokemon;
use Illuminate\Http\Request;

class PokemonController extends Controller
{
    // Método para exibir a página principal
    public function index()
    {
        return view('home');  // Retorna a view da página inicial
    }

    // Método para pesquisar pokémons
    public function search(Request $request)
    {
        // Valida o campo nome
        $request->validate([
            'nome' => 'required|string|alpha|min:1',
        ]);

        $nome = strtolower($request->nome);

        // Verifica se o Pokémon está no banco de dados
        $pokemon = Pokemon::where('name', $nome)->first();

        if (!$pokemon) {
            // Se não encontrado no banco, faz a requisição à API
            $pokemon = $this->getPokemonFromApi($nome);
        }

        // Se o Pokémon não for encontrado nem na API
        if (!$pokemon) {
            return redirect()->route('home')->with('error', 'Pokémon não encontrado!');
        }

        // Exibe os dados do Pokémon na view
        return view('search_results', compact('pokemon'));
    }

    // Método para fazer a requisição à API do Pokémon e salvar no banco de dados
    private function getPokemonFromApi($nome)
    {
        // Faz a requisição à API do Pokémon
        $url = "https://pokeapi.co/api/v2/pokemon/{$nome}";
        $response = file_get_contents($url);

        if (!$response) {
            return null;
        }

        // Converte o JSON da resposta para um objeto
        $data = json_decode($response);

        // Cria uma nova instância de Pokémon e salva no banco
        $pokemon = Pokemon::create([
            'name' => $data->name,
            'height' => $data->height,
            'weight' => $data->weight,
            'base_experience' => $data->base_experience,
            'abilities' => json_encode($data->abilities),
        ]);

        return $pokemon;
    }
}

?>