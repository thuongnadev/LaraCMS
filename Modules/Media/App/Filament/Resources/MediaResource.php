<?php

namespace Modules\Media\App\Filament\Resources;

use Modules\Media\App\Filament\Resources\MediaResource\Pages;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Media\App\Filament\Resources\MediaResource\Forms\MediaForm;
use Modules\Media\App\Filament\Resources\MediaResource\Tables\MediaTable;
use Modules\Media\Entities\Media;
use Modules\Media\Traits\DeletesMedia;

class MediaResource extends Resource
{
    use DeletesMedia;

    protected static ?string $model = Media::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationGroup = 'Nội dung';


    public static function getNavigationLabel(): string
    {
        return 'Thư viện';
    }

    public static function getModelLabel(): string
    {
        return 'Thư viện';
    }

    public static function getPluraModelLabel(): string
    {
        return 'Thư viện';
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return MediaForm::form($form);
    }

    public static function table(Table $table): Table
    {
        return MediaTable::table($table);
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
            'index' => Pages\ListMedia::route('/'),
            'edit' => Pages\EditMedia::route('/{record}/edit'),
        ];
    }
}
