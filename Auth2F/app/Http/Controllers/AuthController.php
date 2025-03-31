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
            $this->authService->setCredentials(
                $request->client_id,
                $request->client_secret
            );

            if ($this->authService->authenticate()) {
                return redirect()->route('auth.status')
                    ->with('token', $this->authService->getAccessToken())
                    ->with('expiry', $this->authService->getTokenExpiry());
            }
        } catch (\Exception $e) {
            \Log::error('Erro de autenticação: ' . $e->getMessage());
        }

        return back()
            ->withInput()
            ->withErrors(['error' => 'Credenciais inválidas']);
    }

    public function status()
    {
        if (!session('nexti_access_token')) {
            return redirect()->route('auth.form')
                ->with('error', 'Sessão expirada ou inválida');
        }

        return view('auth.status', [
            'token' => session('nexti_access_token'),
            'expiry' => session('nexti_token_expiry')
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