<?php 
use App\Http\Controllers\PokemonController;
use Illuminate\Support\Facades\Route;

// Rota para a página inicial (home)
Route::view('/', 'home')->name('home');

// Rota para a busca de Pokémon
Route::post('/search', [PokemonController::class, 'search'])->name('pokemon.search');
?>