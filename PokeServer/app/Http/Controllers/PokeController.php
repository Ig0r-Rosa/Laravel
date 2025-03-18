<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pokemon;
use App\Models\SQLite3;

class PokeController extends Controller
{
    public function search(Request $request)
    {
        $nome = trim($request->input('nome'));

        if (empty($nome) || str_contains($nome, ' ')) {
            return redirect()->back()->with('error', 'Campo inválido ou vazio!');
        }

        $pokemon = new Pokemon($nome);

        if ($pokemon->name) {
            session(['pokemon' => $pokemon]);
            return view('result');
        } else {
            return redirect()->back()->with('error', 'Pokémon não encontrado!');
        }
    }
}
?>