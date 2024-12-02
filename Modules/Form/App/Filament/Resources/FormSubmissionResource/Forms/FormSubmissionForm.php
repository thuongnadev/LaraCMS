<?php

namespace Modules\Form\App\Filament\Resources\FormSubmissionResource\Forms;

use Carbon\Carbon;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Modules\Form\Entities\FormSubmission;

class FormSubmissionForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        KeyValue::make('formFieldValues')
                            ->label('Chi tiết phản hồi')
                            ->addActionLabel('Thêm trường')
                            ->keyLabel('Tên trường')
                            ->valueLabel('Giá trị')
                            ->disableAddingRows()
                            ->disableDeletingRows()
                            ->disableEditingKeys()
                            ->columnSpanFull()
                            ->formatStateUsing(function (FormSubmission $record) {
                                return $record->formFieldValues->mapWithKeys(function ($fieldValue) {
                                    $key = $fieldValue->field->label ?? $fieldValue->field->name ?? 'Thông tin bổ sung';
                                    return [$key => $fieldValue->value];
                                })->toArray();
                            })
                    ]),
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_viewed')
                                    ->label('Đã xem')
                                    ->onIcon('heroicon-m-eye')
                                    ->offIcon('heroicon-m-eye-slash')
                                    ->default(false)
                                    ->reactive()
                                    ->helperText('Chỉ cần kích hoạt ĐÃ XEM và LƯU. NGƯỜI XEM và THỜI GIAN sẽ tự động điền vào.')
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $set('viewed_by', auth()->user()->id);
                                            $set('viewed_at', Carbon::now());
                                        } else {
                                            $set('viewed_by', null);
                                            $set('viewed_at', null);
                                        }
                                    }),
                            ]),
                    ])
            ]);
    }
}
