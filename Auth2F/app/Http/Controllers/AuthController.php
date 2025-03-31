<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NextiAuthService;

class AuthController extends Controller
{
    protected NextiAuthService $authService;

    public function __construct(NextiAuthService $authServices)
    {
        $this->authService = $authServices;
    }

    public function showForm()
    {
        return view('auth.form');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
        ]);

        try {
            // Cria uma nova instância com as credenciais
            $authService = (new NextiAuthService())
                ->setCredentials($request->client_id, $request->client_secret);

            if ($authService->authenticate()) {
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
        } catch (\Exception $e) {
            \Log::error('Auth error: '.$e->getMessage());
        }

        return back()->withInput()->with('error', 'Falha na autenticação');
    }

    public function status()
    {
        if (!session('nexti_auth.access_token')) {
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

    public function refreshToken()
    {
        if ($this->authService->refreshAccessToken()) {
            return response()->json([
                'success' => true,
                'time_remaining' => $this->authService->getTimeRemaining(),
            ]);
        }

        return response()->json([
            'success' => false,
        ], 401);
    }
}