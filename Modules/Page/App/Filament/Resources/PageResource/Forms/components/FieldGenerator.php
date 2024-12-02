<?php

namespace Modules\Page\App\Filament\Resources\PageResource\Forms\Components;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Modules\Category\Entities\Category;
use Modules\Component\App\Enums\FieldInputType;
use Modules\Form\Entities\Form;
use Modules\Post\Entities\Post;
use Modules\Pricing\Entities\PricingGroup;
use Modules\Pricing\Entities\PricingType;
use Modules\Process\Entities\Process;
use Modules\ProductVps\Entities\ProductVps;
use TomatoPHP\FilamentIcons\Components\IconPicker;

class FieldGenerator
{
    public function generateFields($configs): array
    {
        $groupedFields = $configs->groupBy('field_set');

        $fieldsets = [];

        foreach ($groupedFields as $fieldsetName => $fields) {
            if ($fieldsetName) {
                $fieldsets[] = Fieldset::make(ucwords($fieldsetName))
                    ->schema($fields->map(fn($field) => $this->createField($field))->toArray())
                    ->columns(2);
            } else {
                $fieldsets = array_merge($fieldsets, $fields->map(fn($field) => $this->createField($field))->toArray());
            }
        }

        return $fieldsets;
    }

    private function createField($config): TextInput|Toggle|ColorPicker|FileUpload|Select|KeyValue|Repeater|Builder|Textarea
    {
        if ($config->has_options) {
            return $this->createSelectFieldWithOptions($config);
        }

        return match ($config->type_field) {
            FieldInputType::GROUP_CONTACT->value => $this->createGroupContactField($config),
            FieldInputType::TEXTAREA->value => $this->createTextareaField($config),
            FieldInputType::DOMAIN_SELECTION->value => $this->createDomainSelectionField($config),
            FieldInputType::PRICING_GROUP_SELECTION->value => $this->createPricingGroupSelectionField($config),
            FieldInputType::PRICING_CATEGORY_SELECTION->value => $this->createPricingCategorySelectionField($config),
            FieldInputType::FORM_SELECTION->value => $this->createFormSelectionField($config),
            FieldInputType::PRODUCT_SELECTION->value => $this->createProductSelectionField($config),
            FieldInputType::POST_SELECTION->value => $this->createPostSelectionField($config),
            FieldInputType::POST_SELECTION_ONE->value => $this->createPostSelectionOneField($config),
            FieldInputType::CATEGORY_SELECTION_PRODUCT->value => $this->createCategorySelectionProductField($config),
            FieldInputType::CATEGORY_SELECTION_POST->value => $this->createCategorySelectionPostField($config),
            FieldInputType::CATEGORY_SELECTION_PROCESS_DESIGN->value => $this->createCategorySelectionProcessDesignField($config),
            FieldInputType::NUMBER->value => $this->createNumberField($config),
            FieldInputType::TOGGLE->value => $this->createToggleField($config),
            FieldInputType::COLOR_PICKER->value => $this->createColorPickerField($config),
            FieldInputType::IMAGE->value, FieldInputType::IMAGE->value => $this->createMediaField($config),
            FieldInputType::IMAGES->value, FieldInputType::IMAGES->value => $this->createMediaField($config),
            FieldInputType::KEY_VALUES->value => $this->createKeyValuesField($config),
            FieldInputType::PROCESS_REPEATER->value => $this->createProcessRepeaterField($config),
            FieldInputType::BUILDER->value => $this->createBuilderField($config),
            default => $this->createTextField($config),
        };
    }

    private function createTextareaField($config): Textarea {
        return Textarea::make("config_values.{$config->id}")
            ->label($config->label)
            ->rows(4)
            ->placeholder("Nhập " . strtolower($config->label) . " ...")
            ->columnSpanFull();
    }

    private function createGroupContactField($config): Repeater {
        return Repeater::make("config_values.{$config->id}")
            ->label($config->label)
            ->schema([
                IconPicker::make('icon')
                    ->label('Icon')
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('name')
                    ->label('Tên liên hệ')
                    ->required()
                    ->placeholder('Nhập tên liên hệ...')
                    ->columnSpanFull(),

                TextInput::make('value')
                    ->label('Giá trị liên hệ')
                    ->required()
                    ->placeholder('Nhập giá trị liên hệ...')
                    ->columnSpanFull(),
            ])
            ->grid(3)
            ->collapsible()
            ->reorderable()
            ->orderColumn('order')
            ->columnSpanFull()
            ->maxItems(4)
            ->defaultItems(1)
            ->addable('Thêm liên hệ mới');
    }

    private function createBuilderField($config): Builder
    {
        return Builder::make("config_values.{$config->id}")
            ->label($config->label)
            ->blocks([
                Builder\Block::make('banner')
                    ->label('Băng chuyền')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                FileUpload::make("image")
                                    ->image()
                                    ->label('Hình ảnh')
                                    ->columnSpan(1),
                                Group::make([
                                    TextInput::make('title')
                                        ->label('Tiêu đề'),
                                    TextInput::make('description')
                                        ->label('Mô tả'),
                                    TextInput::make('cta_text')
                                        ->label('Văn bản CTA'),
                                    TextInput::make('cta_link')
                                        ->label('Liên kết CTA')
                                        ->url()
                                        ->suffixIcon('heroicon-m-link'),
                                ])->columnSpan(1),
                            ]),
                    ]),
            ])
            ->columnSpanFull()
            ->addActionLabel('Thêm phần mới')
            ->collapsible();
    }

    private function createSelectFieldWithOptions($config): Select
    {
        return Select::make("config_values.{$config->id}")
            ->label($config->label)
            ->options(function () use ($config) {
                return $config->options->pluck('option_label', 'option_value');
            })
            ->placeholder("Chọn " . strtolower($config->label) . " ...");
    }

    private function createProcessRepeaterField($config): Repeater
    {
        return Repeater::make("config_values.{$config->id}")
            ->label('Cấu hình quy trình')
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Tên quy trình'),
                Textarea::make('description')
                    ->label('Mô tả'),

                Repeater::make('steps')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên bước')
                            ->required(),
                        Textarea::make('description')
                            ->label('Mô tả bước')
                            ->nullable(),
                        FileUpload::make('icon')
                            ->image()
                            ->required()
                    ])
                    ->label('Các bước')
                    ->collapsible()
                    ->orderColumn('order')
                    ->defaultItems(1)
                    ->addable('Thêm bước mới'),
            ]);
    }


    private function createKeyValuesField($config): KeyValue
    {
        return KeyValue::make("config_values.{$config->id}")
            ->label($config->label)
            ->keyLabel('Từ khóa');
    }


    private function createMediaField($config): FileUpload
    {
        $field = FileUpload::make("config_values.{$config->id}")
            ->image()
            ->label($config->label);

        if ($config->type_field === FieldInputType::IMAGES->value) {
            $field->multiple();
        }

        return $field;
    }

    private function createColorPickerField($config): ColorPicker
    {
        return ColorPicker::make("config_values.{$config->id}")
            ->label($config->label)
            ->helperText("Chọn " . strtolower($config->label) . " ...");
    }

    private function createToggleField($config): Toggle
    {
        return Toggle::make("config_values.{$config->id}")
            ->label($config->label)
            ->inline(false)
            ->helperText("Bật/Tắt " . strtolower($config->label) . " ...");
    }

    private function createCategorySelectionProductField($config): Select
    {
        return Select::make("config_values.{$config->id}")
            ->label($config->label)
            ->options(
                Category::all()->where('status', 1)->where('category_type', 'product')->pluck('name', 'id')->toArray()
            )
            ->placeholder("Chọn " . strtolower($config->label) . " ...")
            ->multiple();
    }

    private function createCategorySelectionPostField($config): Select
    {
        return Select::make("config_values.{$config->id}")
            ->label($config->label)
            ->options(
                Category::all()->where('status', 1)->where('category_type', 'post')->pluck('name', 'id')->toArray()
            )
            ->placeholder("Chọn " . strtolower($config->label) . " ...")
            ->multiple();
    }

    private function createCategorySelectionProcessDesignField($config): Select
    {
        return Select::make("config_values.{$config->id}")
            ->label($config->label)
            ->options(
                Process::all()->pluck('name', 'id')->toArray()
            )
            ->placeholder("Chọn " . strtolower($config->label) . " ...");
    }

    private function createPricingCategorySelectionField($config): Select
    {
        return Select::make("config_values.{$config->id}")
            ->label($config->label)
            ->options(function () {
                return PricingType::query()->pluck('name', 'id');
            })
            ->placeholder("Chọn " . strtolower($config->label) . " ...")
            ->multiple();
    }

    private function createDomainSelectionField($config): Select
    {
        return Select::make("config_values.{$config->id}")
            ->label($config->label)
            ->options([
                // Phổ biến
                '.vn' => 'Tên miền Việt Nam .vn',
                '.com.vn' => 'Tên miền Việt Nam .com.vn',
                '.net.vn' => 'Tên miền Việt Nam .net.vn',
                '.com' => 'Tên miền quốc tế .com',
                '.net' => 'Tên miền quốc tế .net',
                '.info' => 'Tên miền quốc tế .info',
                '.org' => 'Tên miền quốc tế .org',
                '.asia' => 'Tên miền quốc tế .asia',

                // Tên miền Việt Nam
                '.edu.vn' => 'Tên miền giáo dục .edu.vn',
                '.gov.vn' => 'Tên miền chính phủ .gov.vn',
                '.biz.vn' => 'Tên miền doanh nghiệp .biz.vn',
                '.org.vn' => 'Tên miền tổ chức .org.vn',
                '.name.vn' => 'Tên miền cá nhân .name.vn',
                '.info.vn' => 'Tên miền thông tin .info.vn',
                '.pro.vn' => 'Tên miền chuyên nghiệp .pro.vn',
                '.health.vn' => 'Tên miền y tế .health.vn',

                // Tên miền Quốc tế
                '.biz' => 'Tên miền doanh nghiệp .biz',
                '.name' => 'Tên miền cá nhân .name',
                '.cc' => 'Tên miền quốc tế .cc',
                '.co' => 'Tên miền quốc tế .co',
                '.eu' => 'Tên miền châu Âu .eu',
                '.pro' => 'Tên miền chuyên nghiệp .pro',
                '.bz' => 'Tên miền quốc tế .bz',
                '.tv' => 'Tên miền truyền hình .tv',
                '.me' => 'Tên miền cá nhân .me',
                '.ws' => 'Tên miền quốc tế .ws',
                '.in' => 'Tên miền Ấn Độ .in',
                '.us' => 'Tên miền Hoa Kỳ .us',
                '.co.uk' => 'Tên miền Vương Quốc Anh .co.uk',
                '.mobi' => 'Tên miền di động .mobi',
                '.tel' => 'Tên miền liên lạc .tel',
            ])
            ->placeholder("Chọn " . strtolower($config->label) . " ...")
            ->multiple();
    }

    private function createPricingGroupSelectionField($config): Select
    {
        return Select::make("config_values.{$config->id}")
            ->label($config->label)
            ->options(function () {
                return PricingGroup::query()->pluck('name', 'id');
            })
            ->placeholder("Chọn " . strtolower($config->label) . " ...");
    }

    private function createFormSelectionField($config): Select
    {
        return Select::make("config_values.{$config->id}")
            ->label($config->label)
            ->options(function () {
                return Form::query()->pluck('name', 'id');
            })
            ->placeholder("Chọn " . strtolower($config->label) . " ...");
    }

    private function createProductSelectionField($config): Select
    {
        return Select::make("config_values.{$config->id}")
            ->label($config->label)
            ->options(function () {
                return ProductVps::query()->pluck('name', 'id');
            })
            ->placeholder("Chọn " . strtolower($config->label) . " ...")
            ->multiple();
    }

    private function createPostSelectionField($config): Select
    {
        return Select::make("config_values.{$config->id}")
            ->label($config->label)
            ->options(function () {
                return Post::query()->where(['status' => 'published'])->pluck('title', 'id');
            })
            ->placeholder("Chọn " . strtolower($config->label) . " ...")
            ->multiple();
    }

    private function createPostSelectionOneField($config): Select
    {
        return Select::make("config_values.{$config->id}")
            ->label($config->label)
            ->options(function () {
                return Post::query()->where(['status' => 'published'])->pluck('title', 'id');
            })
            ->placeholder("Chọn " . strtolower($config->label) . " ...");
    }

    private function createNumberField($config): TextInput
    {
        return TextInput::make("config_values.{$config->id}")
            ->label($config->label)
            ->numeric()
            ->placeholder("Nhập " . strtolower($config->label) . " ...");
    }

    private function createTextField($config): TextInput
    {
        return TextInput::make("config_values.{$config->id}")
            ->label($config->label)
            ->placeholder("Nhập " . strtolower($config->label) . " ...");
    }
}
