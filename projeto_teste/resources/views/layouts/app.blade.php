<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Meu Aplicativo Laravel')</title>
    <!-- Adicionar seus links para CSS ou outras dependências -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles') <!-- Se houver estilos adicionais -->
</head>
<body>

    <!-- Menu de navegação -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Laravel App</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>
                <!-- Outras links de navegação -->
            </ul>
        </div>
    </nav>

    <!-- Conteúdo da página -->
    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Rodapé -->
    <footer class="bg-light py-4 mt-4">
        <div class="container text-center">
            <p>© {{ date('Y') }} Meu Aplicativo Laravel</p>
        </div>
    </footer>

    <!-- Scripts JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    @stack('scripts') <!-- Se houver scripts adicionais -->
</body>
</html>
