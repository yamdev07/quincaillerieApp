<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @yield('styles')
</head>
<body>
    <div class="admin-sidebar">
        <!-- Ici, tu peux mettre le menu admin (Dashboard, Employés, Produits, Clients, etc.) -->
        <ul>
            <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('users.index') }}">Gestion des employés</a></li>
            <li><a href="{{ route('products.index') }}">Produits</a></li>
            <li><a href="{{ route('clients.index') }}">Clients</a></li>
        </ul>
    </div>

    <div class="admin-content">
        @yield('content')
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
