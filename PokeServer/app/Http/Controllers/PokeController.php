<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pokemon;
use App\Models\SQLite3;

class PokeController extends Controller
{
    // Método responsável por buscar um Pokémon com base no nome fornecido pelo usuário
    public function search(Request $request)
    {
        // Obtém o nome do Pokémon do input do usuário e remove espaços extras
        $nome = trim($request->input('nome'));

        // Verifica se o nome é inválido ou vazio
        if (empty($nome) || str_contains($nome, ' ')) 
        {
            return redirect()->back()->with('error', 'Campo inválido ou vazio!');
        }

        // Cria uma nova instância da classe Pokemon com o nome fornecido
        $pokemon = new Pokemon($nome);

        // Verifica se o Pokémon foi encontrado
        if ($pokemon->name) 
        {
            //session(['pokemon' => $pokemon]);
            return view('result', ['pokemon' => $pokemon]);
        }
        // Se não foi encontrado, exibe uma mensagem de erro 
        else 
        {
            return redirect()->back()->with('error', 'Pokémon não encontrado!');
        }
    }
}
?>