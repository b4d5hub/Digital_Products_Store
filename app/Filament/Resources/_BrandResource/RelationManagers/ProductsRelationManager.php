<?php

namespace App\Filament\Resources\BrandResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Enums\ProductTypeEnum;
use Illuminate\Support\Str;
use App\Models\Product;


class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Tabs::make('Products')
                ->tabs([
                    Forms\Components\Tabs\Tab::make('Information')
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
                    Forms\Components\Tabs\Tab::make('Pricing & Inventory')
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
                    Forms\Components\Tabs\Tab::make('Additional Information')
                        ->schema([
                                Forms\Components\Toggle::make('is_visible')
                                    ->label('Visiblity')
                                    ->helperText('Enable or disable product visibility')
                                    ->default(true),

                                Forms\Components\Toggle::make('is_featured')
                                    ->label('Featured')
                                    ->helperText('Enable or disable product featured status'),

                                Forms\Components\DatePicker::make('published_at')
                                    ->label('Availability')
                                    ->default(now()),

                                Forms\Components\Select::make('categories')
                                    ->relationship('categories','name')
                                    ->multiple()
                                    ->required(),
                                Forms\Components\FileUpload::make('image')
                                    ->image()
                                    ->directory('form-attachments')
                                    ->imageEditor()
                                    ->columnSpanFull(),
                        ])->columns(2),
                ])->columnSpanFull()
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Title')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
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
                // Tables\Columns\TextColumn::make('published_at')
                //     ->sortable()
                //     ->label('Published')
                //     ->toggleable(),
                Tables\Columns\TextColumn::make('type')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
