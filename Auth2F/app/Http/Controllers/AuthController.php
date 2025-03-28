<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\NextiAuthService;

class AuthController extends Controller
{
    public function showConfig()
    {
        return view('auth.config', [
            'logs' => app('nexti-auth')->getLogs()
        ]);
    }

    public function configure(Request $request)
    {
        $request->validate([
            'client_id' => 'required|string',
            'client_secret' => 'required|string'
        ]);

        app('nexti-auth')->setCredentials(
            $request->client_id,
            $request->client_secret
        );

        return redirect()->route('auth.config')
            ->with('success', 'Credenciais configuradas com sucesso!');
    }

    public function authenticate()
    {
        try {
            $result = app('nexti-auth')->authenticate();
            return redirect()->route('auth.config')
                ->with('success', 'Autenticado com sucesso!')
                ->with('auth_result', $result);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function send2FA(Request $request)
    {
        try {
            $code = app('nexti-auth')->send2FACode($request->method ?? 'email');
            return back()->with('success', 'Código enviado!')->with('show_verification', true);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function verify2FA(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);
        
        if (app('nexti-auth')->verify2FACode($request->code)) {
            return redirect()->route('auth.config')
                ->with('success', 'Código 2FA verificado!');
        }

        return back()->with('error', 'Código 2FA inválido');
    }
}