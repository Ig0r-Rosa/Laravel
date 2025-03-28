<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class NextiAuthService
{
    protected const BASE_URL = 'https://api.nexti.com';
    protected Client $client;
    protected ?string $accessToken = null;
    protected ?string $refreshToken = null;
    protected ?int $tokenExpiry = null;
    protected array $logs = [];

    public function __construct()
    {
        $this->initializeClient();
    }

    protected function initializeClient(): void
    {
        $this->client = new Client([
            'base_uri' => self::BASE_URL,
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => 'Basic ' . base64_encode(env('CLIENT_ID') . ':' . env('CLIENT_SECRET'))
            ]
        ]);
    }

    /**
     * Realiza a autenticação e obtém o access Token e Refresh Token da API
     *
     * @return bool Retorna true se a autenticação foi bem-sucedida, falso caso contrário
     */
    public function authenticate(): bool
    {

        $this->addLog('Iniciando processo de autenticação');
        try {
            $response = $this->client->post('/security/oauth/token', [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                ]
            ]);

            $responseData = json_decode($response->getBody(), true);

            // Armazena os tokens e a data de expiração
            $this->accessToken = $responseData['access_token'] ?? null;
            $this->refreshToken = $responseData['refresh_token'] ?? null;
            $this->tokenExpiry = time() + ($responseData['expires_in'] ?? 0);

            return true;
        } catch (RequestException $e) {
            error_log('Erro ao obter token: ' . $e->getMessage());
            return false;
        }
    }

    protected function addLog(string $message, string $type = 'info'): void
    {
        $this->logs[] = [
            'timestamp' => now()->toDateTimeString(),
            'type' => $type,
            'message' => $message
        ];
        
        // Também registra no log do Laravel
        Log::$type($message);
    }

    public function clearLogs(): void
    {
        $this->logs = [];
    }

    /**
     * Atualiza o Access Token usando o Refresh Token
     *
     * @return bool Retorna true se a atualização foi bem-sucedida, falso caso contrário
     */
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
        } catch (RequestException $e) {
            error_log('Erro ao atualizar token: ' . $e->getMessage());
            return false;
        }
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }
}