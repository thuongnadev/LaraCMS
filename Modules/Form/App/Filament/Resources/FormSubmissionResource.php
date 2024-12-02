<?php

namespace Modules\Form\App\Filament\Resources;

use Modules\Form\App\Filament\Resources\FormSubmissionResource\Forms\FormSubmissionForm;
use Modules\Form\App\Filament\Resources\FormSubmissionResource\Tables\FormSubmissionTable;
use Modules\Form\App\Filament\Resources\FormSubmissionResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Form\Entities\FormSubmission;

class FormSubmissionResource extends Resource
{
    protected static ?string $model = FormSubmission::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';

    protected static ?string $navigationGroup = 'Biểu mẫu';


    public static function getNavigationLabel(): string
    {
        return 'Biểu mẫu đến';
    }

    public static function getModelLabel(): string
    {
        return 'Biểu mẫu đến';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Biểu mẫu đến';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return FormSubmissionForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return FormSubmissionTable::table($table);
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
            'index' => Pages\ListFormSubmission::route('/'),
            'create' => Pages\CreateFormSubmission::route('/create'),
            'edit' => Pages\EditFormSubmission::route('/{record}/edit'),
        ];
    }
}