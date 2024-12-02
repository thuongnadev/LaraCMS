<?php

namespace Modules\Setting\App\Filament\Resources\SettingResource\Forms;

use Filament\Forms;
use Filament\Forms\Form;

class SettingForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}