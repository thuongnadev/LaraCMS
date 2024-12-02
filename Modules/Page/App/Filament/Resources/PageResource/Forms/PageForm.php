<?php

namespace Modules\Page\App\Filament\Resources\PageResource\Forms;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Modules\Component\Entities\Component;
use Modules\Page\App\Filament\Resources\PageResource\Forms\Components\FieldGenerator;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Modules\Page\Entities\Page;

class PageForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('title')
                            ->label('Tiêu đề')
                            ->required()
                            ->columnSpan('full')
                            ->columnSpan(6),
                        TextInput::make('seo_title')
                            ->label('SEO tiêu đề')
                            ->id('post-seo-title')
                            ->maxLength(60)
                            ->rules(['max:60'])
                            ->columnSpan(6),
                        Textarea::make('seo_description')
                            ->label('SEO mô tả')
                            ->id('post-seo-description')
                            ->maxLength(160)
                            ->rules(['max:160'])
                            ->columnSpan(6),
                        TagsInput::make('seo_keywords')
                            ->label('Từ khóa')
                            ->color('success')
                            ->placeholder('Từ khóa cách nhau bởi dấu phẩy...')
                            ->suggestions(
                                Page::pluck('seo_keywords')
                                    ->filter()
                                    ->unique()
                                    ->flatMap(fn($keywords) => explode(',', $keywords))
                                    ->toArray()
                            )
                            ->rules(['max:255'])
                            ->separator(',')
                            ->columnSpan(6),
                    ])->columns(12),

                Section::make("Nội dung trang")
                    ->schema([
                        Repeater::make('components')
                            ->label('')
                            ->schema([
                                Select::make('component_id')
                                    ->label('Thành phần')
                                    ->required()
                                    ->options(function () {
                                        return Component::all()->pluck('name', 'id');
                                    })
                                    ->reactive()
                                    ->afterStateUpdated(function (Get $get, Set $set) {
                                        $componentId = $get('component_id');
                                        if ($componentId) {
                                            $fields = self::getComponentFields($componentId);
                                            self::resetFields($fields, $set);
                                        }
                                    }),

                                Grid::make(1)
                                    ->schema(fn(Get $get): array => self::getComponentFields($get('component_id')))
                                    ->hidden(fn(Get $get): bool => ! $get('component_id'))
                            ])
                            ->reorderableWithButtons()
                            ->orderColumn('order')
                            ->itemLabel(fn(array $state): ?string => Component::find($state['component_id'])?->name ?? null)
                            ->collapsible()
                            ->addActionLabel('Thêm thành phần')
                    ])
            ]);
    }

    /**
     * @param $fields
     * @param $set
     * @return void
     */
    protected static function resetFields($fields, $set): void
    {
        foreach ($fields as $field) {
            if ($field instanceof Fieldset) {
                self::resetFields($field->getChildComponents(), $set);
            } else {
                $set($field->getName(), null);
            }
        }
    }

    /**
     * @param $componentId
     * @return array
     */
    protected static function getComponentFields($componentId): array
    {
        if (!$componentId) {
            return [];
        }

        $component = Component::with(['configurations'])->find($componentId);

        if (!$component) {
            return [];
        }

        $configFields = (new FieldGenerator())->generateFields($component->configurations);

        return array_merge($configFields);
    }
}
