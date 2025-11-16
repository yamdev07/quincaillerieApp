<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;

class IdentifyTenant
{
    public function handle($request, Closure $next)
    {
        // 1️⃣ Identifier le domaine du client
        $host = $request->getHost();

        // 2️⃣ Trouver le tenant correspondant
        $tenant = Tenant::where('domain', $host)->firstOrFail();

        // 3️⃣ Configurer la connexion à sa base
        config([
            'database.connections.tenant.database' => $tenant->database_name,
            'database.connections.tenant.username' => $tenant->db_username,
            'database.connections.tenant.password' => $tenant->db_password,
        ]);

        DB::purge('tenant');
        DB::reconnect('tenant');

        // ✅ Le tenant est maintenant prêt
        return $next($request);
    }
}

