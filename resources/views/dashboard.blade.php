@extends('layouts.app')

@section('title', 'Dashboard Quincaillerie')

@section('styles')
<style>
    :root {
        --primary: #2563eb;
        --primary-dark: #1e40af;
        --secondary: #64748b;
        --success: #10b981;
        --warning: #f59e0b;
        --danger: #ef4444;
        --light: #f8fafc;
        --dark: #1e293b;
        --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 1px 3px rgba(0, 0, 0, 0.1);
        --hover-shadow: 0 10px 15px rgba(0, 0, 0, 0.1), 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .dashboard-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 1.5rem;
    }

    /* Header */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .page-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--dark);
    }

    .page-subtitle {
        font-size: 1rem;
        color: var(--secondary);
        margin-top: 0.25rem;
    }

    .header-actions .btn {
        background: var(--primary);
        color: white;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.375rem;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: var(--card-shadow);
    }

    .header-actions .btn:hover {
        background: var(--primary-dark);
        box-shadow: var(--hover-shadow);
        transform: translateY(-1px);
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    @media (min-width: 640px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (min-width: 1024px) {
        .stats-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    .stat-card {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: var(--hover-shadow);
    }

    .stat-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
    }

    .stat-card.sales::after { background-color: var(--primary); }
    .stat-card.revenue::after { background-color: var(--success); }
    .stat-card.stock::after { background-color: var(--danger); }
    .stat-card.clients::after { background-color: var(--warning); }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
    }

    .stat-title {
        font-size: 0.875rem;
        font-weight: 500;
        color: var(--secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .stat-icon {
        width: 2.5rem;
        height: 2.5rem;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: rgba(37, 99, 235, 0.1);
        color: var(--primary);
    }

    .stat-card.sales .stat-icon { background-color: rgba(37, 99, 235, 0.1); color: var(--primary); }
    .stat-card.revenue .stat-icon { background-color: rgba(16, 185, 129, 0.1); color: var(--success); }
    .stat-card.stock .stat-icon { background-color: rgba(239, 68, 68, 0.1); color: var(--danger); }
    .stat-card.clients .stat-icon { background-color: rgba(245, 158, 11, 0.1); color: var(--warning); }

    .stat-value {
        font-size: 1.875rem;
        font-weight: 700;
        color: var(--dark);
        margin-bottom: 0.25rem;
    }

    .stat-description {
        font-size: 0.875rem;
        color: var(--secondary);
    }

    /* Main Content */
    .content-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (min-width: 1024px) {
        .content-grid {
            grid-template-columns: 2fr 1fr;
        }
    }

    /* Chart Section */
    .chart-container {
        background: white;
        border-radius: 0.75rem;
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }

    .card-header {
        padding: 1.5rem 1.5rem 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #e2e8f0;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--dark);
    }

    .card-actions select {
        padding: 0.5rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        background: white;
        font-size: 0.875rem;
        color: var(--secondary);
        outline: none;
        cursor: pointer;
    }

    .card-actions select:focus {
        border-color: var(--primary);
    }

    .chart-area {
        padding: 1.5rem;
        height: 350px;
    }

    /* Sidebar Cards */
    .sidebar-cards {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .sidebar-card {
        background: white;
        border-radius: 0.75rem;
        padding: 1.5rem;
        box-shadow: var(--card-shadow);
    }

    .sidebar-card-header {
        margin-bottom: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sidebar-card-title {
        font-size: 1rem;
        font-weight: 600;
        color: var(--dark);
    }

    .sidebar-card-value {
        font-size: 1.5rem;
        font-weight: 700;
        text-align: center;
        margin: 1rem 0;
        color: var(--dark);
    }

    .sidebar-card-footer {
        font-size: 0.875rem;
        color: var(--secondary);
        text-align: center;
    }

    .quick-links {
        list-style: none;
    }

    .quick-links li {
        margin-bottom: 0.5rem;
    }

    .quick-links a {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        color: var(--dark);
        text-decoration: none;
        border-radius: 0.375rem;
        transition: all 0.2s ease;
    }

    .quick-links a:hover {
        background-color: #f1f5f9;
    }

    .quick-links svg {
        width: 1.25rem;
        height: 1.25rem;
        margin-right: 0.75rem;
        color: var(--secondary);
    }

    /* Tables Section */
    .tables-section {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    @media (min-width: 1024px) {
        .tables-section {
            grid-template-columns: 1fr 1fr;
        }
    }

    .table-card {
        background: white;
        border-radius: 0.75rem;
        box-shadow: var(--card-shadow);
        overflow: hidden;
    }

    .table-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-title {
        font-size: 1.125rem;
        font-weight: 600;
        color: var(--dark);
    }

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th {
        padding: 1rem 1.5rem;
        text-align: left;
        font-weight: 500;
        font-size: 0.75rem;
        color: var(--secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e2e8f0;
    }

    td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.875rem;
    }

    tr:last-child td {
        border-bottom: none;
    }

    tr:hover {
        background-color: #f8fafc;
    }

    /* Status Badges */
    .badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .badge-low {
        background-color: #fee2e2;
        color: var(--danger);
    }

    .badge-normal {
        background-color: #d1fae5;
        color: var(--success);
    }

    /* Tabs */
    .tabs {
        display: flex;
        gap: 2rem;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .tab {
        padding: 1rem 0;
        color: var(--secondary);
        text-decoration: none;
        font-weight: 500;
        position: relative;
        transition: color 0.2s ease;
    }

    .tab:hover {
        color: var(--primary);
    }

    .tab.active {
        color: var(--primary);
        font-weight: 600;
    }

    .tab.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 2px;
        background-color: var(--primary);
    }

    /* Empty States */
    .empty-state {
        padding: 3rem 1.5rem;
        text-align: center;
        color: var(--secondary);
    }

    .empty-icon {
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-text {
        font-size: 0.875rem;
    }

    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .stat-card, .chart-container, .sidebar-card, .table-card {
        animation: fadeIn 0.5s ease-out forwards;
    }

    .stat-card:nth-child(2) { animation-delay: 0.1s; }
    .stat-card:nth-child(3) { animation-delay: 0.2s; }
    .stat-card:nth-child(4) { animation-delay: 0.3s; }
</style>
@endsection

@section('content')
<div class="dashboard-container">
    <!-- Header Section -->
    <div class="dashboard-header">
        <div>
            <h1 class="page-title">Tableau de Bord</h1>
            <p class="page-subtitle">Aperçu de votre quincaillerie</p>
        </div>
        <div class="header-actions">
            <!-- Bouton Nouvelle vente -->
            <a href="{{ route('sales.create') }}" class="btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                </svg>
                Nouvelle vente
            </a>

            <!-- Bouton Gestion des employés (visible seulement pour admin) -->
            @if(in_array(strtolower(auth()->user()->role), ['admin', 'super admin']))
                <a href="{{ route('users.index') }}" class="btn" style="margin-left: 10px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M3 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H3zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                    </svg>
                    Gestion des employés
                </a>
            @endif

        </div>
    </div>



    <!-- Stats Overview -->
    <div class="stats-grid">
        <div class="stat-card sales">
            <div class="stat-header">
                <div class="stat-title">Ventes aujourd'hui</div>
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M0 2a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1v7.5a2.5 2.5 0 0 1-2.5 2.5h-9A2.5 2.5 0 0 1 1 12.5V5a1 1 0 0 1-1-1V2zm2 3v7.5A1.5 1.5 0 0 0 3.5 14h9a1.5 1.5 0 0 0 1.5-1.5V5H2zm13-3H1v2h14V2zM5 7.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </div>
            </div>
            <div class="stat-value">{{ $salesToday ?? 0 }}</div>
            <div class="stat-description">Transactions aujourd'hui</div>
        </div>
        
        <div class="stat-card revenue">
            <div class="stat-header">
                <div class="stat-title">Chiffre d'affaires</div>
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"/>
                        <path d="M0 4a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1V4zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V6a2 2 0 0 1-2-2H3z"/>
                    </svg>
                </div>
            </div>
            <div class="stat-value">{{ number_format($totalRevenue ?? 0, 0, ',', ' ') }} FCFA</div>
            <div class="stat-description">Total des revenus</div>
        </div>
        
       <div class="stat-card stock p-5 rounded-xl shadow-md 
            {{ ($lowStockProducts->count() ?? 0) > 0 ? 'bg-red-100 border-2 border-red-500 animate-pulse' : 'bg-white' }}">
            
            <div class="stat-header flex justify-between items-center mb-4">
                <div class="stat-title text-gray-600 font-semibold uppercase text-sm">Alertes stock</div>
                <div class="stat-icon w-10 h-10 flex items-center justify-center rounded bg-red-200 text-red-600">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
            </div>

            <div class="stat-value text-3xl font-bold text-gray-800">
                {{ $lowStockProducts->count() ?? 0 }}
            </div>
            
            <div class="stat-description text-gray-500 mt-1">
                Produits à réapprovisionner
            </div>
        </div>


        
        <div class="stat-card clients">
            <div class="stat-header">
                <div class="stat-title">Clients actifs</div>
                <div class="stat-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                        <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816V4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v6.28c.32.246.603.522.846.816C13.623 10.958 14 12.01 14 13c0 .53-.134 1.023-.374 1.47A3.983 3.983 0 0 1 12 16H4a3.983 3.983 0 0 1-1.626-3.53A2.508 2.508 0 0 1 2 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816V4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v6.28c.32.246.603.522.846.816C10.623 10.958 11 12.01 11 13c0 .53-.134 1.023-.374 1.47A3.983 3.983 0 0 1 9 16H5.216z"/>
                    </svg>
                </div>
            </div>
            <div class="stat-value">{{ $activeClients ?? 0 }}</div>
            <div class="stat-description">Clients ce mois-ci</div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="content-grid">
        <!-- Chart Section -->
        <div class="chart-container">
            <div class="card-header">
                <h2 class="card-title">Évolution des ventes</h2>
                <div class="card-actions">
                    <select>
                        <option>7 derniers jours</option>
                        <option>30 derniers jours</option>
                        <option>3 derniers mois</option>
                    </select>
                </div>
            </div>
            <div class="chart-area">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Sidebar Cards -->
        <div class="sidebar-cards">
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <h3 class="sidebar-card-title">Vente moyenne</h3>
                </div>
                <div class="sidebar-card-value">
                    {{ $totalRevenue && $salesToday ? number_format($totalRevenue / max($salesToday, 1), 0, ',', ' ') : 0 }} FCFA
                </div>
                <div class="sidebar-card-footer">Par transaction aujourd'hui</div>
            </div>
            
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <h3 class="sidebar-card-title">Statut du stock</h3>
                </div>
                <div class="sidebar-card-value">
                    @if(($lowStockProducts->count() ?? 0) === 0)
                        <div style="color: var(--success); font-size: 2.5rem;">✓</div>
                        <div style="color: var(--success); font-weight: 600;">Tout va bien</div>
                    @else
                        <div style="color: var(--danger); font-size: 2.5rem;">!</div>
                        <div style="color: var(--danger); font-weight: 600;">Attention requise</div>
                    @endif
                </div>
            </div>

            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <h3 class="sidebar-card-title">Accès rapide</h3>
                </div>
                <ul class="quick-links">
                    <li>
                        <a href="{{ route('clients.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1H7zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                <path fill-rule="evenodd" d="M5.216 14A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816V4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v6.28c.32.246.603.522.846.816C13.623 10.958 14 12.01 14 13c0 .53-.134 1.023-.374 1.47A3.983 3.983 0 0 1 12 16H4a3.983 3.983 0 0 1-1.626-3.53A2.508 2.508 0 0 1 2 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816V4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v6.28c.32.246.603.522.846.816C10.623 10.958 11 12.01 11 13c0 .53-.134 1.023-.374 1.47A3.983 3.983 0 0 1 9 16H5.216z"/>
                            </svg>
                            Clients
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('suppliers.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M.5 1a.5.5 0 0 0 0 1h1.11l.401 1.607 1.498 7.985A.5.5 0 0 0 4 12h1a2 2 0 1 0 0 4 2 2 0 0 0 0-4h7a2 2 0 1 0 0 4 2 2 0 0 0 0-4h1a.5.5 0 0 0 .491-.408l1.5-8A.5.5 0 0 0 14.5 3H2.89l-.405-1.621A.5.5 0 0 0 2 1H.5zM6 14a1 1 0 1 1-2 0 1 1 0 0 1 2 0zm7 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/>
                            </svg>
                            Fournisseurs
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5 8.186 1.113zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z"/>
                            </svg>
                            Produits
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categories.index') }}">
                             <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                 fill="currentColor" viewBox="0 0 16 16">
                                 <path d="M2 3a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H7.5L6 1.5H2z"/>
                                 <path d="M15 6H1v6a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V6z"/>
                             </svg>
                             Catégories
                         </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="tabs">
        <a href="#recent-sales" class="tab active">Ventes récentes</a>
        <a href="#low-stock" class="tab">Stock faible</a>
    </div>

    <!-- Tables Section -->
    <div class="tables-section">
        <!-- Recent Sales Table -->
        <div class="table-card">
            <div class="table-header">
                <h3 class="table-title">Dernières transactions</h3>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Client</th>
                            <th>Montant</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentSales as $sale)
                            <tr>
                                <td><strong>{{ $sale->product->name }}</strong></td>
                                <td>{{ $sale->client ? $sale->client->name : 'Client inconnu' }}</td>
                                <td><strong>{{ number_format($sale->total_price, 0, ',', ' ') }} FCFA</strong></td>
                                <td>{{ $sale->created_at->format('d/m H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-icon">📝</div>
                                        <div class="empty-text">Aucune vente récente</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Low Stock Table -->
        <div class="table-card">
            <div class="table-header">
                <h3 class="table-title">Stock faible</h3>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Stock</th>
                            <th>Prix</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lowStockProducts as $product)
                            <tr>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td><span class="badge badge-low">{{ $product->stock }}</span></td>
                                <td>{{ number_format($product->price, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">
                                        <div class="empty-icon">✅</div>
                                        <div class="empty-text">Stock optimal</div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        // Configuration du graphique
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($dates ?? []),
                datasets: [{
                    label: 'Ventes (FCFA)',
                    data: @json($totals ?? []),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: '#f1f5f9',
                            borderDash: [5, 5]
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9',
                            borderDash: [5, 5]
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Navigation tabs functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                
                // Here you would typically show/hide the corresponding content
                // For simplicity, we're not implementing the full tab content switching
            });
        });
    });
</script>
@endsection