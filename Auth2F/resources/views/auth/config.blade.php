<!DOCTYPE html>
<html>
<head>
    <title>Nexti Auth</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4 max-w-3xl">
        <h1 class="text-2xl font-bold mb-6">Nexti API Authentication</h1>
        
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white p-6 rounded shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">Credenciais</h2>
            <form method="POST" action="/configure">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Client ID</label>
                    <input type="text" name="client_id" value="{{ old('client_id') }}" 
                           class="w-full px-3 py-2 border rounded">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Client Secret</label>
                    <input type="password" name="client_secret" 
                           class="w-full px-3 py-2 border rounded">
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                    Salvar Credenciais
                </button>
            </form>
        </div>

        <div class="bg-white p-6 rounded shadow mb-6">
            <h2 class="text-xl font-semibold mb-4">Ações</h2>
            <div class="flex space-x-2">
                <form method="POST" action="/authenticate">
                    @csrf
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">
                        Autenticar
                    </button>
                </form>
                <form method="POST" action="/send-2fa">
                    @csrf
                    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">
                        Enviar 2FA
                    </button>
                </form>
            </div>
            
            @if(session('2fa_code'))
            <div class="mt-4">
                <form method="POST" action="/verify-2fa">
                    @csrf
                    <div class="flex items-center space-x-2">
                        <input type="text" name="code" placeholder="Código 2FA" 
                               class="px-3 py-2 border rounded">
                        <button type="submit" class="bg-purple-500 text-white px-4 py-2 rounded">
                            Verificar
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h2 class="text-xl font-semibold mb-4">Logs</h2>
            <div class="space-y-2 max-h-96 overflow-y-auto">
                @foreach($logs as $log)
                <div class="p-2 border-b @if($log['level'] === 'error') bg-red-50 @endif">
                    <div class="text-sm text-gray-500">{{ $log['timestamp'] }}</div>
                    <div>{{ $log['message'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</body>
</html>