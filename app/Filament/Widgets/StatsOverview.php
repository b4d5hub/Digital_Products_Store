<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{

    protected static ?int $sort =1;

    protected static ?string $pollingInterval = '15s';

    protected static bool $isLazy = true;

    protected function getStats(): array
    {
        // $ordersarr = Order::where('status', '=', 'Completed')->get()->toArray();
        // $customersarr = Customer::all()->toArray();

        return [
            Stat::make('Total Customers', Customer::all()->count())
                ->description('Increase in customers')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([1,2,3,5,8,2,3,4,9]),
            
            Stat::make('Total Orders', Order::where('status', '=', 'Completed')->count())
            ->chart([1,2,3,5,8,2,3,4,9]),
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
