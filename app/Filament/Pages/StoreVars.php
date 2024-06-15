<?php

namespace App\Filament\Pages;

use App\Models\StoreSetting;
use Filament\Forms;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;


class StoreVars extends Page
{
    public $store_name;
    public $currency;
    public $logo;
    public $primary_color;
    public $secondary_color;
    public $text_dark;
    public $text_light;

    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static string $view = 'filament.pages.store-vars';


    public function mount()
    {
        $settings = StoreSetting::pluck('value', 'name')->only([
            'store_name', 'currency', 'logo', 'primary_color', 'secondary_color', 'text_dark', 'text_light'
        ])->toArray();

        $this->store_name = $settings['store_name'] ?? '';
        $this->currency = $settings['currency'] ?? '';
        $this->logo = $settings['logo'] ?? '';
        $this->primary_color = $settings['primary_color'] ?? '';
        $this->secondary_color = $settings['secondary_color'] ?? '';
        $this->text_dark = $settings['text_dark'] ?? '';
        $this->text_light = $settings['text_light'] ?? '';
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Information')
            ->schema([
            TextInput::make('store_name')
                ->required()
                ->default($this->store_name),
            Select::make('currency')
                ->required()
                ->options(['USD' => 'USD', 'EUR' => 'EUR'])
                ->default($this->currency),

                //error --maybe filament version
            // FileUpload::make('logo')
            //     ->required()
            //     ->directory('store-imgs')
            //     ->columnSpanFull(),

            // FileUpload::make('logo')
            //     ->required()
            //     ->image()
            //     ->directory('store-imgs')
            //     ->imageEditor(),
            ])->columns(2),
            Forms\Components\Section::make('Colors')
            ->schema([
                ColorPicker::make('primary_color')
                    ->required()
                    ->default($this->primary_color),
                ColorPicker::make('secondary_color')
                    ->required()
                    ->default($this->secondary_color),
                ColorPicker::make('text_dark')
                    ->required()
                    ->default($this->text_dark),
                ColorPicker::make('text_light')
                    ->required()
                    ->default($this->text_light),
            ])->columns(2),

        ];
    }

    public function submit()
    {
        DB::transaction(function () {
            StoreSetting::updateOrCreate(['name' => 'store_name'], ['value' => $this->store_name]);
            StoreSetting::updateOrCreate(['name' => 'currency'], ['value' => $this->currency]);
            StoreSetting::updateOrCreate(['name' => 'logo'], ['value' => $this->logo]);
            StoreSetting::updateOrCreate(['name' => 'primary_color'], ['value' => $this->primary_color]);
            StoreSetting::updateOrCreate(['name' => 'secondary_color'], ['value' => $this->secondary_color]);
            StoreSetting::updateOrCreate(['name' => 'text_dark'], ['value' => $this->text_dark]);
            StoreSetting::updateOrCreate(['name' => 'text_light'], ['value' => $this->text_light]);
        });

        Notification::make()
            ->title('Store settings updated successfully!')
            ->success()
            ->send();

        return redirect()->back();
    }

}
