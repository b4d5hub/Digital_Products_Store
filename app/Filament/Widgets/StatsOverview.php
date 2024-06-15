<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{

    protected static ?int $sort =1;

    protected static ?string $pollingInterval = '15s';

    protected static bool $isLazy = false;

    protected function getStats(): array
    {
        // $ordersarr = Order::where('status', '=', 'Completed')->get()->toArray();
        // $customersarr = Customer::all()->toArray();

        $startDate = Carbon::now()->subDays(6); // Get data for the last 7 days
        $endDate = Carbon::now();
        
        $ordersPerDay = Order::where('status', '=', 'Completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at')
            ->get()
            ->groupBy(function($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d'); // Group by day
            })
            ->map(function($group) {
                return $group->count(); // Count orders per day
            })
            ->values()
            ->toArray();

        $customersPerDay = Customer::select(DB::raw('COUNT(*) as count'), DB::raw('DATE(created_at) as day'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy(DB::raw('DATE(created_at)'))
            ->pluck('count', 'day')
            ->toArray();

        return [
            Stat::make('Total Customers', Customer::all()->count())
                ->description('Increase in customers')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart($customersPerDay),
            
            Stat::make('Total Orders', Order::where('status', '=', 'Completed')->count())
            ->chart($ordersPerDay),
            Stat::make('Lifetime Revenue', function () {
                return Order::whereIn('id', function ($query) {
                        $query->select('id')
                              ->from('orders')
                              ->where('status', 'Completed');
                    })->sum(DB::raw('quantity * unit_price'));
            })            
        ];
    }
}
