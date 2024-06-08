<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected static ?string $heading = 'Recent Orders';

    protected static ?int $sort = 2;

    // protected int | string | array $columnSpan = 'full';
    
    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(3)
            ->defaultSort('created_at','desc')
            ->columns([
                Tables\Columns\TextColumn::make('number')
                ->label('Order Number'),
                Tables\Columns\TextColumn::make('customer.name'),
                Tables\Columns\TextColumn::make('status')
                    ->color(fn (string $state): string => match ($state) {
                        'Declined' => 'danger',
                        'Processing' => 'warning',
                        'Completed' => 'success',
                        default => 'info',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->label('Order Date'),
            ]);
    }
}
