<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NextiAuthService;

class AuthController extends Controller
{
    // Variável de serviço
    protected NextiAuthService $authService;

    // Construtor para injetar o serviço
    public function __construct(NextiAuthService $authServices)
    {
        $this->authService = $authServices;
    }

    // Invoca a view do formulário
    public function showForm()
    {
        return view('auth.form');
    }

    // Função para autenticar o cliente
    public function authenticate(Request $request)
    {
        // Valida os dados de entrada e verifica se o cliente já existe
        $request->validate([
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
        ]);

        try 
        {
            // Cria uma nova instância com as credenciais
            $authService = (new NextiAuthService())
                ->setCredentials($request->client_id, $request->client_secret);

            if ($authService->authenticate()) 
            {
                // Armazena tudo na sessão
                session([
                    'nexti_auth' => [
                        'access_token' => $authService->getAccessToken(),
                        'expiry' => $authService->getTokenExpiry(),
                        'credentials' => [
                            'client_id' => $request->client_id,
                            'client_secret' => $request->client_secret
                        ]
                    ]
                ]);
                
                return redirect()->route('auth.status');
            }
        } 
        catch (\Exception $e) 
        {
            \Log::error('Auth error: '.$e->getMessage());
        }

        return back()->withInput()->with('error', 'Falha na autenticação');
    }

    // Função para verificar o status do token
    public function status()
    {
        if (!session('nexti_auth.access_token')) 
        {
            return redirect()->route('auth.form')->with('error', 'Sessão expirada ou inválida');
        }

        // Recria o serviço a partir da sessão
        $authService = (new NextiAuthService())
            ->setCredentials(
                session('nexti_auth.credentials.client_id'),
                session('nexti_auth.credentials.client_secret')
            );

        return view('auth.status', [
            'token' => session('nexti_auth.access_token'),
            'expiry' => session('nexti_auth.expiry')
        ]);
    }

    // Função para verificar o tempo restante do token
    public function refreshToken()
    {
        if ($this->authService->refreshAccessToken()) 
        {
            return response()->json([
                'success' => true,
                'time_remaining' => $this->authService->getTimeRemaining(),
            ]);
        }

        return response()->json([
            'success' => false,
        ], 401);
    }

    // Função responsavel por invocar elementos para o notices
    public function notices(Request $request)
    {
        if (!session('nexti_auth.access_token')) 
        {
            return redirect()->route('auth.form')->with('error', 'Sessão expirada');
        }

        try 
        {
            $authService = app(NextiAuthService::class);
            $authService->setCredentials(
                session('nexti_auth.credentials.client_id'),
                session('nexti_auth.credentials.client_secret')
            );
            $authService->setAccessToken(session('nexti_auth.access_token'));

            $notices = $authService->getAllNotices();

            return view('auth.notices', [
                'notices' => $notices
            ]);

        } 
        catch (\RuntimeException $e) 
        {
            \Log::error('Erro específico: ' . $e->getMessage());
            return back()->with('error', 'Endpoint não encontrado. Verifique a URL da API.');
            
        } 
        catch (\Exception $e) 
        {
            \Log::error('Erro geral: ' . $e->getMessage());
            return back()->with('error', 'Erro ao carregar notices: ' . $e->getMessage());
        }
    }
}