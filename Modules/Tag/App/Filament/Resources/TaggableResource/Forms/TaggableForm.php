<?php

namespace Modules\Tag\App\Filament\Resources\TaggableResource\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class TaggableForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
