<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreSettingResource\Pages;
use App\Filament\Resources\StoreSettingResource\RelationManagers;
use App\Models\StoreSetting;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StoreSettingResource extends Resource
{
    protected static ?string $model = StoreSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->disabled()
                    ->required(),
                TextInput::make('value')
                    ->required()
                    ->label('Color Value'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('value'),
            ])
            ->filters([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStoreSettings::route('/'),
            'edit' => Pages\EditStoreSetting::route('/{record}/edit'),
        ];
    }
}
