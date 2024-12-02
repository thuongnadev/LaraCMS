<?php

namespace Modules\Process\App\Filament\Resources;

use Modules\Process\App\Filament\Resources\ProcessResource\Forms\ProcessForm;
use Modules\Process\App\Filament\Resources\ProcessResource\Tables\ProcessTable;
use Modules\Process\Entities\Process;
use Modules\Process\App\Filament\Resources\ProcessResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class ProcessResource extends Resource
{
    protected static ?string $model = Process::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static ?string $navigationGroup = 'Nội dung';

    public static function getNavigationLabel(): string
    {
        return 'Quản lí quy trình';
    }

    public static function getModelLabel(): string
    {
        return 'Quy trình';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Quy trình';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return ProcessForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return ProcessTable::table($table);
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
            'index' => Pages\ListProcess::route('/'),
            'create' => Pages\CreateProcess::route('/create'),
            'edit' => Pages\EditProcess::route('/{record}/edit'),
        ];
    }
}
