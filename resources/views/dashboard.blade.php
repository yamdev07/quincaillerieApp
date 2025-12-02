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
        min-height: 400px;
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
        position: relative;
    }

    .chart-loading {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 1rem;
        z-index: 10;
    }

    .spinner {
        width: 40px;
        height: 40px;
        border: 3px solid #f3f3f3;
        border-top: 3px solid var(--primary);
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .chart-error {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 1rem;
        padding: 2rem;
        text-align: center;
        color: var(--danger);
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
        cursor: pointer;
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

    /* Loading states */
    .loading-shimmer {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200% 100%;
        animation: shimmer 1.5s infinite;
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    .skeleton {
        background: #f0f0f0;
        border-radius: 4px;
        height: 20px;
        margin-bottom: 10px;
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
            <a href="{{ route('sales.create') }}" class="btn">
                <i class="bi bi-plus-circle"></i>
                Nouvelle vente
            </a>
            @if(in_array(strtolower(auth()->user()->role), ['admin', 'super admin']))
                <a href="{{ route('users.index') }}" class="btn" style="margin-left: 10px;">
                    <i class="bi bi-people"></i>
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
                    <i class="bi bi-cart"></i>
                </div>
            </div>
            <div class="stat-value" id="salesToday">{{ $salesToday ?? 0 }}</div>
            <div class="stat-description">Transactions aujourd'hui</div>
        </div>
        
        <div class="stat-card revenue">
            <div class="stat-header">
                <div class="stat-title">Chiffre d'affaires</div>
                <div class="stat-icon">
                    <i class="bi bi-currency-dollar"></i>
                </div>
            </div>
            <div class="stat-value" id="totalRevenue">{{ number_format($totalRevenue ?? 0, 0, ',', ' ') }} FCFA</div>
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
            <div class="stat-value text-3xl font-bold text-gray-800" id="lowStockCount">
                {{ $lowStockProducts->count() ?? 0 }}
            </div>
            <div class="stat-description text-gray-500 mt-1">Produits à réapprovisionner</div>
        </div>
        
        <div class="stat-card clients">
            <div class="stat-header">
                <div class="stat-title">Clients actifs</div>
                <div class="stat-icon">
                    <i class="bi bi-people"></i>
                </div>
            </div>
            <div class="stat-value" id="activeClients">{{ $activeClients ?? 0 }}</div>
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
                    <select id="chartPeriod">
                        <option value="7">7 derniers jours</option>
                        <option value="30">30 derniers jours</option>
                        <option value="90">3 derniers mois</option>
                    </select>
                </div>
            </div>
            <div class="chart-area">
                <!-- Loading State -->
                <div class="chart-loading" id="chartLoading">
                    <div class="spinner"></div>
                    <p>Chargement des données...</p>
                </div>
                
                <!-- Error State -->
                <div class="chart-error" id="chartError" style="display: none;">
                    <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
                    <p>Impossible de charger les données du graphique</p>
                    <button onclick="loadChartData()" class="btn" style="background: var(--primary); color: white; padding: 0.5rem 1rem; border-radius: 0.375rem;">
                        Réessayer
                    </button>
                </div>
                
                <!-- Chart Canvas -->
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Sidebar Cards -->
        <div class="sidebar-cards">
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <h3 class="sidebar-card-title">Vente moyenne</h3>
                </div>
                <div class="sidebar-card-value" id="averageSale">
                    {{ $totalRevenue && $salesToday ? number_format($totalRevenue / max($salesToday, 1), 0, ',', ' ') : 0 }} FCFA
                </div>
                <div class="sidebar-card-footer">Par transaction aujourd'hui</div>
            </div>
            
            <div class="sidebar-card">
                <div class="sidebar-card-header">
                    <h3 class="sidebar-card-title">Statut du stock</h3>
                </div>
                <div class="sidebar-card-value" id="stockStatus">
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
                            <i class="bi bi-people"></i>
                            Clients
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('suppliers.index') }}">
                            <i class="bi bi-truck"></i>
                            Fournisseurs
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}">
                            <i class="bi bi-box-seam"></i>
                            Produits
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categories.index') }}">
                            <i class="bi bi-folder"></i>
                            Catégories
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="tabs">
        <a href="#" class="tab active" onclick="switchTab('recent-sales')">Ventes récentes</a>
        <a href="#" class="tab" onclick="switchTab('low-stock')">Stock faible</a>
    </div>

    <!-- Tables Section -->
    <div class="tables-section">
        <!-- Recent Sales Table -->
        <div class="table-card">
            <div class="table-header">
                <h3 class="table-title">Dernières transactions</h3>
            </div>
            <div class="table-container">
                <table id="recentSalesTable">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Client</th>
                            <th>Montant</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via AJAX -->
                        @forelse($recentSales as $sale)
                            <tr>
                                <td><strong>{{ $sale->product->name }}</strong></td>
                                <td>{{ $sale->client ? $sale->client->name : 'Client inconnu' }}</td>
                                <td><strong>{{ number_format($sale->total_price, 0, ',', ' ') }} FCFA</strong></td>
                                <td>{{ $sale->created_at->format('d/m H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    Aucune vente récente
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
                <table id="lowStockTable">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Stock</th>
                            <th>Prix</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via AJAX -->
                        @forelse($lowStockProducts as $product)
                            <tr>
                                <td><strong>{{ $product->name }}</strong></td>
                                <td><span class="badge badge-low">{{ $product->stock }}</span></td>
                                <td>{{ number_format($product->price, 0, ',', ' ') }} FCFA</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">
                                    Stock optimal
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Variables globales
let salesChart = null;
let currentPeriod = 7;
let isLoading = false;

// Fonction pour charger les données du graphique via AJAX
async function loadChartData(period = currentPeriod) {
    if (isLoading) return;
    
    isLoading = true;
    currentPeriod = period;
    
    const loadingElement = document.getElementById('chartLoading');
    const errorElement = document.getElementById('chartError');
    const canvas = document.getElementById('salesChart');
    
    // Afficher le loader
    loadingElement.style.display = 'flex';
    errorElement.style.display = 'none';
    
    try {
        // Récupérer le token CSRF
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // Faire la requête AJAX - NOTE: URL mise à jour pour web.php
        const response = await fetch(`/ajax/dashboard/chart-data?period=${period}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token
            }
        });
        
        if (!response.ok) {
            throw new Error('Erreur réseau: ' + response.status);
        }
        
        const data = await response.json();
        
        // Masquer le loader
        loadingElement.style.display = 'none';
        
        // Créer ou mettre à jour le graphique
        if (salesChart) {
            salesChart.destroy();
        }
        
        createChart(data);
        
    } catch (error) {
        console.error('Erreur lors du chargement du graphique:', error);
        loadingElement.style.display = 'none';
        errorElement.style.display = 'flex';
    } finally {
        isLoading = false;
    }
}

// Fonction pour créer le graphique
function createChart(data) {
    const ctx = document.getElementById('salesChart').getContext('2d');
    
    // Configuration optimisée pour les performances
    salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.dates || [],
            datasets: [{
                label: 'Ventes (FCFA)',
                data: data.totals || [],
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#2563eb',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 1,
                pointRadius: 3,
                pointHoverRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 300,
                easing: 'easeOutQuart'
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: 'rgba(0, 0, 0, 0.7)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 10,
                    cornerRadius: 4,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('fr-FR').format(context.parsed.y) + ' FCFA';
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxRotation: 0,
                        autoSkip: true,
                        maxTicksLimit: 10
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        callback: function(value) {
                            if (value >= 1000000) {
                                return (value / 1000000).toFixed(1) + 'M';
                            }
                            if (value >= 1000) {
                                return (value / 1000).toFixed(0) + 'k';
                            }
                            return value;
                        }
                    }
                }
            }
        }
    });
}

// Fonction pour charger les statistiques via AJAX
async function loadStats() {
    try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // URL mise à jour
        const response = await fetch('/ajax/dashboard/stats', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            
            // Mettre à jour les statistiques
            document.getElementById('salesToday').textContent = data.salesToday || 0;
            
            const revenueElement = document.getElementById('totalRevenue');
            if (data.totalRevenue) {
                revenueElement.textContent = data.totalRevenue.toLocaleString('fr-FR') + ' FCFA';
            } else {
                revenueElement.textContent = '0 FCFA';
            }
            
            document.getElementById('lowStockCount').textContent = data.lowStockCount || 0;
            document.getElementById('activeClients').textContent = data.activeClients || 0;
            
            const averageSaleElement = document.getElementById('averageSale');
            if (data.averageSale) {
                averageSaleElement.textContent = Math.round(data.averageSale).toLocaleString('fr-FR') + ' FCFA';
            } else {
                averageSaleElement.textContent = '0 FCFA';
            }
            
            // Mettre à jour le statut du stock
            const stockStatusElement = document.getElementById('stockStatus');
            if (data.lowStockCount === 0) {
                stockStatusElement.innerHTML = `
                    <div style="color: #10b981; font-size: 2.5rem;">✓</div>
                    <div style="color: #10b981; font-weight: 600;">Tout va bien</div>
                `;
            } else {
                stockStatusElement.innerHTML = `
                    <div style="color: #ef4444; font-size: 2.5rem;">!</div>
                    <div style="color: #ef4444; font-weight: 600;">Attention requise</div>
                `;
            }
        } else {
            console.error('Statut HTTP:', response.status);
        }
    } catch (error) {
        console.error('Erreur lors du chargement des statistiques:', error);
    }
}

// Fonction pour charger les ventes récentes via AJAX
async function loadRecentSales() {
    try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // URL mise à jour
        const response = await fetch('/ajax/dashboard/recent-sales', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            const tbody = document.querySelector('#recentSalesTable tbody');
            
            if (!data || data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            Aucune vente récente
                        </td>
                    </tr>
                `;
            } else {
                tbody.innerHTML = data.map(sale => `
                    <tr>
                        <td><strong>${sale.product_name || 'Produit inconnu'}</strong></td>
                        <td>${sale.client_name || 'Client inconnu'}</td>
                        <td><strong>${parseInt(sale.total_price || 0).toLocaleString('fr-FR')} FCFA</strong></td>
                        <td>${formatDateTime(sale.created_at)}</td>
                    </tr>
                `).join('');
            }
        }
    } catch (error) {
        console.error('Erreur lors du chargement des ventes récentes:', error);
    }
}

// Fonction pour charger le stock faible via AJAX
async function loadLowStock() {
    try {
        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        // URL mise à jour
        const response = await fetch('/ajax/dashboard/low-stock', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': token
            }
        });
        
        if (response.ok) {
            const data = await response.json();
            const tbody = document.querySelector('#lowStockTable tbody');
            
            if (!data || data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="3" class="text-center py-4">
                            Stock optimal
                        </td>
                    </tr>
                `;
            } else {
                tbody.innerHTML = data.map(product => `
                    <tr>
                        <td><strong>${product.name || 'Produit inconnu'}</strong></td>
                        <td><span class="badge badge-low">${product.stock || 0}</span></td>
                        <td>${parseInt(product.price || 0).toLocaleString('fr-FR')} FCFA</td>
                    </tr>
                `).join('');
            }
        }
    } catch (error) {
        console.error('Erreur lors du chargement du stock faible:', error);
    }
}

// Fonction utilitaire pour formater la date
function formatDateTime(dateString) {
    if (!dateString) return 'Date inconnue';
    
    try {
        const date = new Date(dateString);
        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        
        return `${day}/${month} ${hours}:${minutes}`;
    } catch (error) {
        return 'Date invalide';
    }
}

// Fonction pour switcher entre les onglets
function switchTab(tabName) {
    // Mettre à jour les onglets actifs
    document.querySelectorAll('.tab').forEach(tab => {
        tab.classList.remove('active');
    });
    event.target.classList.add('active');
    
    // Optionnel: Charger des données spécifiques à l'onglet
    if (tabName === 'recent-sales') {
        loadRecentSales();
    } else if (tabName === 'low-stock') {
        loadLowStock();
    }
}

// Fonction pour tester les URLs avant de charger
async function testRoutes() {
    const routes = [
        '/ajax/dashboard/chart-data',
        '/ajax/dashboard/stats',
        '/ajax/dashboard/recent-sales',
        '/ajax/dashboard/low-stock'
    ];
    
    console.log('Test des routes AJAX...');
    
    for (const route of routes) {
        try {
            const response = await fetch(route, { 
                method: 'HEAD',
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            console.log(`${route}: ${response.status} ${response.statusText}`);
        } catch (error) {
            console.error(`${route}: ${error.message}`);
        }
    }
}

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    // Tester les routes (optionnel - pour débogage)
    // testRoutes();
    
    // Charger le graphique via AJAX
    loadChartData();
    
    // Charger les statistiques en arrière-plan
    setTimeout(() => {
        loadStats();
        loadRecentSales();
        loadLowStock();
    }, 500); // Petit délai pour éviter le blocage
    
    // Gestionnaire d'événement pour le changement de période
    document.getElementById('chartPeriod').addEventListener('change', function() {
        loadChartData(parseInt(this.value));
    });
    
    // Rafraîchissement automatique toutes les 5 minutes
    setInterval(() => {
        loadChartData(currentPeriod);
        loadStats();
        loadRecentSales();
        loadLowStock();
    }, 300000); // 5 minutes
    
    // Ajouter des gestionnaires d'erreur globaux
    window.addEventListener('error', function(e) {
        console.error('Erreur globale:', e.error);
    });
    
    window.addEventListener('unhandledrejection', function(e) {
        console.error('Promesse rejetée non gérée:', e.reason);
    });
});

// Optimisation des performances
window.addEventListener('load', function() {
    // Chargement différé des images
    const images = document.querySelectorAll('img[data-src]');
    images.forEach(img => {
        img.src = img.dataset.src;
    });
    
    // Nettoyage des écouteurs d'événements quand la page est quittée
    window.addEventListener('beforeunload', function() {
        if (salesChart) {
            salesChart.destroy();
        }
    });
});

// Fonction de fallback si les données sont déjà dans la page
function initializeWithExistingData() {
    // Vérifier si les données sont déjà disponibles dans le DOM
    const salesToday = document.getElementById('salesToday').textContent;
    const totalRevenue = document.getElementById('totalRevenue').textContent;
    
    console.log('Données initiales:', { salesToday, totalRevenue });
}
</script>
@endsection