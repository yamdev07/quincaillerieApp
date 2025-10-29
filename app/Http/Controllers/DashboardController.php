<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Client;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 📅 Dates
        $today = Carbon::today();
        $weekAgo = Carbon::today()->subDays(7);

        // 1️⃣ Ventes aujourd'hui
        $salesToday = Sale::whereDate('created_at', $today)->count();

        // 2️⃣ Chiffre d'affaires total
        $totalRevenue = Sale::whereDate('created_at', $today)->sum('total_price');

        // 3️⃣ Alertes Stock
        $lowStockThreshold = 5;      // Produits à surveiller
        $criticalStockThreshold = 2; // Stock critique

        $lowStockProducts = Product::where('stock', '<=', $lowStockThreshold)
                                   ->where('stock', '>', $criticalStockThreshold)
                                   ->get();

        $criticalStockProducts = Product::where('stock', '<=', $criticalStockThreshold)->get();

        // 4️⃣ Clients actifs (ce mois-ci)
        $activeClients = Client::whereMonth('created_at', $today->month)->count();

        // 5️⃣ Nouveaux clients (7 derniers jours)
        $newClients = Client::whereBetween('created_at', [$weekAgo, $today])->get();

        // 6️⃣ Ventes récentes
        $recentSales = Sale::with(['product', 'client'])
                           ->orderBy('created_at', 'desc')
                           ->take(10)
                           ->get();

        // 7️⃣ Données pour le graphique (7 derniers jours)
        $dates = [];
        $totals = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dates[] = $date->format('d/m');
            $totals[] = Sale::whereDate('created_at', $date)->sum('total_price');
        }

        // 8️⃣ Alerte ventes si en dessous du seuil
        $dailyTarget = 5; // Exemple : objectif minimum de ventes par jour
        $lowSalesAlert = $salesToday < $dailyTarget;

        return view('dashboard.index', compact(
            'salesToday',
            'totalRevenue',
            'lowStockProducts',
            'criticalStockProducts',
            'activeClients',
            'newClients',
            'recentSales',
            'dates',
            'totals',
            'lowSalesAlert'
        ));
    }
}
