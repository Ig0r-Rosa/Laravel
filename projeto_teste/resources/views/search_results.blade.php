<?php 
@extends('layouts.app')

@section('title', 'Detalhes do Pokémon')

@section('content')
    <h1>Detalhes do Pokémon</h1>

    <p>Nome: {{ ucfirst($pokemon->name) }}</p>
    <p>Altura: {{ $pokemon->height / 10 }} metros</p>
    <p>Peso: {{ $pokemon->weight / 10 }} kg</p>
    <p>Experiência base: {{ $pokemon->base_experience }}</p>

    <h3>Habilidades:</h3>
    <ul>
        @foreach($pokemon->abilities as $ability)
            <li>{{ $ability->ability->name }}</li>
        @endforeach
    </ul>
    
    <a href="{{ route('home') }}">Voltar</a>
@endsection

?>