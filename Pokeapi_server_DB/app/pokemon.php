<?php

class Pokemon
{
    public $name;
    public $height;
    public $weight;
    public $base_experience;
    public $abilities;

    public function __construct($name)
    {
        if (!$this->checkDb($name)) // Se não está no banco, busca na API
        {
            $this->pokeapi($name);
        }
    }

    private function checkDb($name)
    {
        try 
        {
            $db = $this->tableIfNotExist();    

            // Buscar se já existe no banco
            $stmt = $db->prepare("SELECT * FROM pokemons WHERE name = :name");
            $stmt->bindValue(':name', strtolower($name), SQLITE3_TEXT);
            $result = $stmt->execute();
            $pokemon = $result->fetchArray(SQLITE3_ASSOC);

            if ($pokemon) 
            {
                // Se já existe, preenche os atributos com os dados do banco
                $this->name = $pokemon['name'];
                $this->height = $pokemon['height'];
                $this->weight = $pokemon['weight'];
                $this->base_experience = $pokemon['base_experience'];
                $this->abilities = json_decode($pokemon['abilities']);
                return true;
            }

            return false;
        } 
        catch (Exception $e) 
        {
            return false;
        }
    }

    public function pokeapi($nome)
    {
        $url = "https://pokeapi.co/api/v2/pokemon/" .strtolower($nome);
        $response = @file_get_contents($url);

        if ($response === FALSE) 
        {
            return false;
        }

        $data = json_decode($response);

        
        $this->name = $data->name;
        $this->height = $data->height;
        $this->weight = $data->weight;
        $this->base_experience = $data->base_experience;
        $this->abilities = $data->abilities;

        $this->saveToDb();

        return true;
    }

    private function tableIfNotExist()
    {
        $db = new SQLite3('db/banco.db');

        // Criar tabela se não existir
        $db->exec("CREATE TABLE IF NOT EXISTS pokemons (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE,
            height INTEGER NOT NULL,
            weight INTEGER NOT NULL,
            base_experience INTEGER NOT NULL,
            abilities TEXT NOT NULL
        )");

        return $db;
    }

    private function saveToDb()
    {
        try
        {
            $db = $this->tableIfNotExist();

            $stmt = $db->prepare("INSERT INTO pokemons (name, height, weight, base_experience, abilities) 
                VALUES (:name, :height, :weight, :base_experience, :abilities)");

            $stmt->bindValue(':name', $this->name, SQLITE3_TEXT);
            $stmt->bindValue(':height', $this->height, SQLITE3_INTEGER);
            $stmt->bindValue(':weight', $this->weight, SQLITE3_INTEGER);
            $stmt->bindValue(':base_experience', $this->base_experience, SQLITE3_INTEGER);
            $stmt->bindValue(':abilities', json_encode($this->abilities), SQLITE3_TEXT);

            $stmt->execute();
            $db->close();
        }
        catch(Exception $e)
        {}
    }
}
?>