<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\DashboardDataController;
use Illuminate\Support\Facades\Cache;

class RefreshDashboardData extends Command
{
    protected $signature = 'dashboard:refresh';
    protected $description = 'Refresh the dashboard data cache.';

    public function handle()
    {
        $controller = new DashboardDataController();
        $freshData = $controller->fetchDashboardData();

        Cache::put('dashboarddata', $freshData, now()->addMinutes(5));

        $this->info('Dashboard data refreshed!');
    }
}
