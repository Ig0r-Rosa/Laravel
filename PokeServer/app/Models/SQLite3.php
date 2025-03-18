<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SQLite3 extends Model
{
    protected $connection = 'sqlite'; 
    protected $table = 'pokemons';
    protected $fillable = ['name', 'height', 'weight', 'base_experience', 'abilities'];

    /**
     * Verifica se um Pokémon já está no banco.
     */
    public static function existeNoBanco($nome)
    {
        return self::where('name', strtolower($nome))->first();
    }

    /**
     * Salva um Pokémon no banco de dados.
     */
    public static function salvar($dados)
    {
        return self::create([
            'name' => strtolower($dados['name']),
            'height' => $dados['height'],
            'weight' => $dados['weight'],
            'base_experience' => $dados['base_experience'],
            'abilities' => json_encode($dados['abilities']),
        ]);
    }
}
?>