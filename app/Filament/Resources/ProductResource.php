<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\ProductTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;



class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 0;

    protected static ?string $navigationLabel = 'Products';

    protected static ?string $recordTitleAttribute = 'name';

    protected static int $globalSearchResultsLimit = 5;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name','slug','description'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return[
            'Category' => $record->category->name,
            'Description' => $record->description
        ];
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['category']);
    }

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
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function(string $operation, $state, 
                                    Forms\Set $set){
                                        if ($operation !== 'create' && $operation !== 'edit'){
                                            return;
                                        }
                                        $set('slug', Str::slug($state));
                                    }),

                                Forms\Components\TextInput::make('slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->unique(Product::class,'slug', ignoreRecord: true),
                                    
                                Forms\Components\MarkdownEditor::make('description')
                                    ->columnSpan('full')
                            ])->columns(2),

                        Forms\Components\Section::make('Pricing & Inventory')
                            ->schema([
                                Forms\Components\TextInput::make('sku'),
                                Forms\Components\TextInput::make('price')->required()->numeric()->minValue(1),
                                Forms\Components\TextInput::make('quantity')->required()->numeric()->minValue(0),
                                Forms\Components\Select::make('type')
                                    ->required()
                                    ->options([
                                        'Downloadable' => ProductTypeEnum::DOWNLOADABLE->value,
                                        'Deliverable' => ProductTypeEnum::DELIVERABLE->value
                                    ])


                            ])->columns(2),
                            
                    ]),

                Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Status')
                        ->schema([
                            Forms\Components\Toggle::make('is_visible')
                                ->label('Visiblity')
                                ->helperText('Enable or disable product visibility')
                                ->default(true),
                            // Forms\Components\Toggle::make('is_featured')
                            //     ->label('Featured')
                            //     ->helperText('Enable or disable product featured status'),
                            Forms\Components\DatePicker::make('published_at')
                                ->label('Availability')
                                // ->hidden()
                                // ->disabled()
                                ->default(now()),
                        ]),

                    Forms\Components\Section::make('Image')
                        ->schema([
                            Forms\Components\FileUpload::make('image')
                                ->image()
                                ->directory('form-attachments')
                                ->imageEditor(),
                        ])->collapsible(),
                        
                    Forms\Components\Section::make('Associations')
                        ->schema([
                            Forms\Components\Select::make('categories')
                                ->relationship('categories','name')
                                ->required(),
                            // Forms\Components\Select::make('categories')
                            //     ->relationship('categories','name')
                            //     ->multiple(),
                        ]),
                    Forms\Components\Section::make('Items')
                        ->schema([
                            Forms\Components\TextInput::make('digital_item')
                                ->prefix('Download Link')
                                ->required(),
                            // Forms\Components\Select::make('categories')
                            //     ->relationship('categories','name')
                            //     ->multiple(),
                        ]),


                ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Title')
                    ->wrap()
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                // Tables\Columns\TextColumn::make('brand.name')
                //     ->searchable()
                //     ->sortable()
                //     ->toggleable(),
                Tables\Columns\IconColumn::make('is_visible')
                    ->boolean()
                    ->label('Visibility')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('price')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('published_at')
                    ->sortable()
                    ->label('Published')
                    // ->disabled()
                    // ->hidden()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label('Visibility')
                    ->boolean()
                    ->trueLabel('Only Visible Products')
                    ->falseLabel('Only Hidden Products')
                    ->native(false),
                // Tables\Filters\SelectFilter::make('brand')
                //     ->relationship('brand', 'name'),
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'Downloadable' => ProductTypeEnum::DOWNLOADABLE->value,
                        'Deliverable' => ProductTypeEnum::DELIVERABLE->value
                    ]),

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
