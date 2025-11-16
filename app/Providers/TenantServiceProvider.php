<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;

class TenantServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->runningInConsole()) {
            return; // Ignore Artisan commands
        }

        $host = request()->getHost(); // ex: client1.monsite.com
        $tenant = Tenant::where('domain', $host)->first();

        if ($tenant) {
            config([
                'database.connections.tenant.database' => $tenant->database_name,
                'database.connections.tenant.username' => $tenant->db_username,
                'database.connections.tenant.password' => $tenant->db_password,
            ]);

            DB::purge('tenant');
            DB::reconnect('tenant');
        }
    }
}
