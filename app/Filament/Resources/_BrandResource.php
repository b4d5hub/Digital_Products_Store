<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;
use App\Models\Brand;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;


class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?string $navigationLabel = 'Brands';

    protected static ?int $navigationSort = 2;

    // public static function getNavigationBadge(): ?string
    // {
    //     return static::getModel()::count();
    // }    

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Group::make()
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->label('Name')
                        ->live(onBlur: true)
                        ->unique(Brand::class,'name', ignoreRecord: true)
                        ->afterStateUpdated(function (string $operation, $state,
                        Forms\Set $set) {
                            if ($operation !== 'create' && $operation !== 'edit') {
                                return;
                            }
                            $set('slug', Str::slug($state));
                        }),


                    Forms\Components\TextInput::make('slug')
                        ->disabled()
                        ->dehydrated()
                        ->required()
                        ->unique(Brand::class,'slug', ignoreRecord: true)
                        ->maxLength(255),

                    Forms\Components\TextInput::make('url')
                        ->maxLength(255)
                        ->label('Website URL')
                        ->columnSpan('full'),

                    Forms\Components\MarkdownEditor::make('description')
                        ->columnSpan('full'),
                ])->columns(2),
            ]),
            
            Forms\Components\Group::make()
            ->schema([
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_visible')
                        ->label('Visibility')
                        ->helperText('Enable or disabled brand visibility')
                        ->default(true)
                        ->required(),
                    ]),
                Forms\Components\Section::make('Color')
                    ->schema([
                        Forms\Components\ColorPicker::make('primary_hex')
                            ->label('Primary Color'),
                    ])

            ])

        ]);
        
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->label('Website URL')
                    ->searchable(),
                Tables\Columns\ColorColumn::make('primary_hex')
                    ->label('Primary Color'),
                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visibility')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProductsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }
}
