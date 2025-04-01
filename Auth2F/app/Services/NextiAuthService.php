<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class NextiAuthService
{
    // Variáveis de configuração
    protected const BASE_URL = 'https://api.nexti.com';

    // Variáveis de cliente
    protected Client $client;
    protected ?string $clientId = null;
    protected ?string $clientSecret = null;
    protected ?string $accessToken = null;
    protected ?string $refreshToken = null;
    protected ?int $tokenExpiry = null;

    // Variáveis de logs
    protected array $logs = [];

    public function __construct(){}

    // Função para definir os credenciais do cliente
    public function setCredentials(string $clientId, string $clientSecret): self
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->initializeClient();
        return $this;
    }

    // Função para inicializar o cliente
    protected function initializeClient(): void
    {
        // Caso não tenha credenciais, lança uma exceção
        if (!$this->clientId || !$this->clientSecret) {
            throw new \RuntimeException('Credenciais não configuradas');
        }

        // Inicializa o cliente com seu ID e SECRET
        $this->client = new Client([
            'base_uri' => self::BASE_URL,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode($this->clientId . ':' . $this->clientSecret)
            ]
        ]);
    }

    // Função responsavel por autenticar e garantir o token
    public function authenticate(): bool
    {   
        try 
        {
            // Caso não tenha credenciais, lança uma exceção
            if (!$this->clientId || !$this->clientSecret) {
                throw new \RuntimeException('Credenciais não configuradas');
            }

            // Invoca o POST da autenticação
            $response = $this->client->post('/security/oauth/token', [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                ]
            ]);

            // Transforma a resposta em json
            $responseData = json_decode($response->getBody(), true);

            // Armazena os tokens e a data de expiração
            $this->accessToken = $responseData['access_token'] ?? null;
            $this->refreshToken = $responseData['refresh_token'] ?? null;
            $this->tokenExpiry = time() + ($responseData['expires_in'] ?? 0);

            return true;
        } 
        catch (RequestException $e) 
        {
            error_log('Erro ao obter token: ' . $e->getMessage());
            return false;
        }
    }

    // Função para atualizar o token
    public function refreshAccessToken(): bool
    {
        try {
            $response = $this->client->post('/security/oauth/token', [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $this->refreshToken
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);

            $this->accessToken = $responseData['access_token'] ?? null;
            $this->tokenExpiry = time() + ($responseData['expires_in'] ?? 0);

            return true;
        }
        catch (RequestException $e) 
        {
            error_log('Erro ao atualizar token: ' . $e->getMessage());
            return false;
        }
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    public function getTokenExpiry(): ?int
    {
        return $this->tokenExpiry;
    }

    public function getTimeRemaining(): ?int
    {
        if (!$this->tokenExpiry) {
            return null;
        }
        
        return $this->tokenExpiry - time();
    }

    // Função para verificar se o token precisa de atualização
    public function needsRefresh(): bool
    {
        $timeRemaining = $this->getTimeRemaining();
        return $timeRemaining !== null && $timeRemaining <= 10;
    }
}