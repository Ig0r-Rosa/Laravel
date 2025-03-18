<?php
namespace App\Models;

use App\Models\SQLite3;

class Pokemon
{
    public $name;
    public $height;
    public $weight;
    public $base_experience;
    public $abilities;

    public function __construct($name)
    {
        if (!$this->carregarDoBanco($name)) {
            $this->buscarNaApi($name);
        }
    }

    private function carregarDoBanco($name)
    {
        $pokemon = SQLite3::existeNoBanco($name);
        if ($pokemon) {
            $this->name = $pokemon->name;
            $this->height = $pokemon->height;
            $this->weight = $pokemon->weight;
            $this->base_experience = $pokemon->base_experience;
            $this->abilities = json_decode($pokemon->abilities);
            return true;
        }
        return false;
    }

    public function buscarNaApi($nome)
    {
        $url = "https://pokeapi.co/api/v2/pokemon/" . strtolower($nome);
        $response = @file_get_contents($url);

        if ($response === FALSE) {
            return false;
        }

        $data = json_decode($response);
        $this->name = $data->name;
        $this->height = $data->height;
        $this->weight = $data->weight;
        $this->base_experience = $data->base_experience;
        $this->abilities = $data->abilities;

        SQLite3::salvar([
            'name' => $this->name,
            'height' => $this->height,
            'weight' => $this->weight,
            'base_experience' => $this->base_experience,
            'abilities' => $this->abilities
        ]);

        return true;
    }
}
?>