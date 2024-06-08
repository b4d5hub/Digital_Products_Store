<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatusEnum;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?int $navigationSort = 1;
    
    protected   static ?string $navigationGroup = 'Shop';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', '=', 'Completed')->count();
    }



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Order Details')
                        ->schema([
                            Forms\Components\TextInput::make('order_number')
                                ->default(random_int(100000,9999999))
                                ->unique(Order::class,'order_number', ignoreRecord: true)
                                ->disabled()
                                ->dehydrated()
                                ->required(),

                            // Forms\Components\Select::make('customer_id')
                            //     ->relationship('customer','name'),

                            Forms\Components\Select::make('status')
                                ->options([
                                    'Pending' => OrderStatusEnum::PENDING->value,
                                    'Processing' => OrderStatusEnum::PROCESSING->value,
                                    'Completed' => OrderStatusEnum::COMPLETED->value,
                                    'Declined' => OrderStatusEnum::DECLINED->value,
                                ]),

                            Forms\Components\MarkdownEditor::make('notes')
                                ->columnSpanFull()

                        ])->columns(2),

                    Forms\Components\Wizard\Step::make('Order Items')
                        ->schema([
                            Forms\Components\Repeater::make('items')
                                ->relationship()
                                ->schema([

                                    Forms\Components\Select::make('product_id')
                                    ->label('Products')
                                    ->options(Product::query()->pluck('name','id'))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, Forms\Set $set) => 
                                    $set('unit_price', Product::find($state)?->price ?? 0)),

                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->live()
                                    ->dehydrated()
                                    ->default(1)
                                    ->required(),

                                Forms\Components\TextInput::make('unit_price')
                                    ->label('Unit Price')
                                    ->disabled()
                                    ->dehydrated()
                                    ->numeric()
                                    ->required(),
                                
                                Forms\Components\Placeholder::make('total_price')
                                    ->label('Total Price')
                                    ->content(fn ($get)=>$get('quantity') * $get('unit_price'))
                                    ->reactive(),

                                ])->columns(4)
                        ])
                ])->columnSpanFull()
            ]);
            
        }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label('#')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->date(),
                Tables\Columns\TextColumn::make('status')
                    ->color(fn (string $state): string => match ($state) {
                        'Declined' => 'danger',
                        'Processing' => 'warning',
                        'Completed' => 'success',
                        default => 'info',
                    })
                    ->sortable()
                    ->searchable(),
                // Tables\Columns\TextColumn::make('customer.name')
                //     ->sortable()
                //     ->searchable()
                //     ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    // Tables\Actions\EditAction::make(),
                    // Tables\Actions\DeleteAction::make()
                // ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            // 'create' => Pages\CreateOrder::route('/create'),
            // 'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
    public static function canCreate(): bool
    {
       return false;
    }
}
