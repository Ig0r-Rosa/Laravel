<?php

// Define a classe Pokemon
class Pokemon
{
    // Propriedades do Pokémon
    public $name;
    public $height;
    public $weight;
    public $base_experience;
    public $abilities;

    // Construtor da classe que recebe o nome do Pokémon
    public function __construct($name)
    {
        // Verifica se o Pokémon já está no banco de dados
        if (!$this->checkDb($name)) // Se não está no banco, busca na API
        {
            // Se não encontrado no banco, busca as informações na API
            $this->pokeapi($name);
        }
    }

    // Método privado para verificar se o Pokémon já existe no banco de dados
    private function checkDb($name)
    {
        try 
        {
            // Chama a função que garante que a tabela existe
            $db = $this->tableIfNotExist();    

            // Prepara a consulta para verificar se o Pokémon já está no banco
            $stmt = $db->prepare("SELECT * FROM pokemons WHERE name = :name");
            $stmt->bindValue(':name', strtolower($name), SQLITE3_TEXT);  // Passa o nome para a consulta (em minúsculo)
            $result = $stmt->execute();
            $pokemon = $result->fetchArray(SQLITE3_ASSOC);

            // Se o Pokémon for encontrado no banco
            if ($pokemon) 
            {
                // Preenche os atributos da classe com os dados do banco
                $this->name = $pokemon['name'];
                $this->height = $pokemon['height'];
                $this->weight = $pokemon['weight'];
                $this->base_experience = $pokemon['base_experience'];
                $this->abilities = json_decode($pokemon['abilities']);  // Converte o campo de habilidades de JSON para array
                return true;  // Retorna true indicando que o Pokémon foi encontrado no banco
            }

            // Se o Pokémon não for encontrado, retorna false
            return false;
        } 
        catch (Exception $e) 
        {
            // Se ocorrer algum erro (ex: erro de banco de dados), retorna false
            return false;
        }
    }

    // Método público que busca os dados do Pokémon na API
    public function pokeapi($nome)
    {
        // Constrói a URL para acessar a API com o nome do Pokémon
        $url = "https://pokeapi.co/api/v2/pokemon/" .strtolower($nome);
        
        // Faz a requisição para a API
        $response = @file_get_contents($url);

        // Verifica se a requisição falhou
        if ($response === FALSE) 
        {
            return false;  // Se falhou, retorna false
        }

        // Converte a resposta JSON da API para um objeto PHP
        $data = json_decode($response);

        // Preenche os atributos do Pokémon com os dados obtidos da API
        $this->name = $data->name;
        $this->height = $data->height;
        $this->weight = $data->weight;
        $this->base_experience = $data->base_experience;
        $this->abilities = $data->abilities;  // Habilidades são um array de objetos, que é mantido assim

        // Salva os dados obtidos no banco de dados
        $this->saveToDb();

        return true;  // Retorna true indicando que os dados foram obtidos e salvos
    }

    // Método privado que garante que a tabela "pokemons" exista no banco de dados
    private function tableIfNotExist()
    {
        // Cria ou abre o banco de dados SQLite
        $db = new SQLite3('db/banco.db');

        // Cria a tabela "pokemons" caso ela não exista
        $db->exec("CREATE TABLE IF NOT EXISTS pokemons (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL UNIQUE,
            height INTEGER NOT NULL,
            weight INTEGER NOT NULL,
            base_experience INTEGER NOT NULL,
            abilities TEXT NOT NULL
        )");

        return $db;  // Retorna o objeto de conexão com o banco de dados
    }

    // Método privado que salva os dados do Pokémon no banco de dados
    private function saveToDb()
    {
        try
        {
            // Chama a função que garante que a tabela existe
            $db = $this->tableIfNotExist();

            // Prepara a consulta para inserir um novo Pokémon no banco
            $stmt = $db->prepare("INSERT INTO pokemons (name, height, weight, base_experience, abilities) 
                VALUES (:name, :height, :weight, :base_experience, :abilities)");

            // Vincula os valores dos atributos do Pokémon aos parâmetros da consulta
            $stmt->bindValue(':name', $this->name, SQLITE3_TEXT);
            $stmt->bindValue(':height', $this->height, SQLITE3_INTEGER);
            $stmt->bindValue(':weight', $this->weight, SQLITE3_INTEGER);
            $stmt->bindValue(':base_experience', $this->base_experience, SQLITE3_INTEGER);
            $stmt->bindValue(':abilities', json_encode($this->abilities), SQLITE3_TEXT);  // Converte as habilidades para JSON

            // Executa a consulta para inserir os dados no banco
            $stmt->execute();

            // Fecha a conexão com o banco
            $db->close();
        }
        catch(Exception $e)
        {
            // Se ocorrer um erro ao salvar no banco, não faz nada (mas seria bom tratar isso adequadamente)
        }
    }
}
?>
