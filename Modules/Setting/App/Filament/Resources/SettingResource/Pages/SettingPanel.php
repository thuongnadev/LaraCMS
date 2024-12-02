<?php

namespace Modules\Setting\App\Filament\Resources\SettingResource\Pages;

use Filament\Forms;
use Filament\Resources\Pages\Page;
use Filament\Support\Colors\Color;
use Awcodes\Palette\Forms\Components\ColorPicker;
use Filament\Notifications\Notification;
use Modules\Setting\App\Filament\Resources\SettingResource;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Wiebenieuwenhuis\FilamentCodeEditor\Components\CodeEditor;

class SettingPanel extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
    use WithFileUploads;

    public $logo = null;
    public $logo_size = '100';
    public $favicon = null;
    public $primary_color = Color::Indigo;
    public $secondary_color = Color::Gray;
    public $info_color = Color::Blue;
    public $success_color = Color::Emerald;
    public $danger_color = Color::Rose;
    public $warning_color = Color::Orange;
    public $font_family = 'Poppins';
    public $theme = 'light';
    public $pageLayout = 'full_width';
    public $custom_css = '';

    protected static string $resource = SettingResource::class;
    protected static string $view = 'setting::filament.resources.setting-resource.pages.setting-panel';

    protected static ?string $title = 'Cài đặt giao diện';

    protected static ?string $navigationLabel = 'Cài đặt gia0 diện';

    public function mount()
    {
        $this->form->fill([
            'logo' => Config::get('theme.logo'),
            'logo_size' => Config::get('theme.logo_size', '100'),
            'favicon' => Config::get('theme.favicon'),
            'primary_color' => Config::get('theme.primary_color.500', Color::Indigo),
            'secondary_color' => Config::get('theme.secondary_color.500', Color::Gray),
            'info_color' => Config::get('theme.info_color.500', Color::Blue),
            'success_color' => Config::get('theme.success_color.500', Color::Emerald),
            'danger_color' => Config::get('theme.danger_color.500', Color::Rose),
            'warning_color' => Config::get('theme.warning_color.500', Color::Orange),
            'font_family' => Config::get('theme.font_family', 'Poppins'),
            'theme' => Config::get('theme.theme', 'light'),
            'pageLayout' => Config::get('theme.layout', 'full_width'),
            'custom_css' => Config::get('theme.custom_css', ''),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Fieldset::make('Hình ảnh')
                ->schema([
                    Forms\Components\FileUpload::make('logo')
                        ->label('Logo')
                        ->image()
                        ->directory('logos')
                        ->disk('public')
                        ->preserveFilenames()
                        ->maxSize(5000)
                        ->imagePreviewHeight('150')
                        ->default(function () {
                            $logoPath = Config::get('theme.logo');
                            return $logoPath ? asset($logoPath) : null;
                        }),

                    Forms\Components\TextInput::make('logo_size')
                        ->label('Kích thước logo (px)')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(1000)
                        ->default('100')
                        ->suffix('px')
                        ->helperText('Nhập kích thước logo theo pixel'),


                    Forms\Components\FileUpload::make('favicon')
                        ->label('Favicon')
                        ->image()
                        ->directory('favicons')
                        ->disk('public')
                        ->preserveFilenames()
                        ->acceptedFileTypes(['image/x-icon', 'image/png'])
                        ->maxSize(512)
                ])
                ->columns(2),

            Forms\Components\Fieldset::make('Màu sắc')
                ->schema([
                    ColorPicker::make('primary_color')
                        ->label('Màu chủ đạo')
                        ->colors([
                            'Indigo' => Color::Indigo,
                            'Rose' => Color::Rose,
                            'Orange' => Color::Orange,
                            'Amber' => Color::Amber,
                            'Emerald' => Color::Emerald,
                            'Blue' => Color::Blue,
                            'Purple' => Color::Purple,
                            'Pink' => Color::Pink,
                            'Gray' => Color::Gray,
                        ])
                        ->default(Color::Indigo),

                    ColorPicker::make('secondary_color')
                        ->label('Màu phụ')
                        ->colors([
                            'Gray' => Color::Gray,
                            'Rose' => Color::Rose,
                            'Orange' => Color::Orange,
                            'Amber' => Color::Amber,
                            'Emerald' => Color::Emerald,
                            'Blue' => Color::Blue,
                            'Purple' => Color::Purple,
                            'Pink' => Color::Pink,
                        ])
                        ->default(Color::Gray),

                    ColorPicker::make('info_color')
                        ->label('Màu thông tin')
                        ->colors([
                            'Blue' => Color::Blue,
                            'Cyan' => Color::Cyan,
                            'Teal' => Color::Teal,
                        ])
                        ->default(Color::Blue),

                    ColorPicker::make('success_color')
                        ->label('Màu thành công')
                        ->colors([
                            'Emerald' => Color::Emerald,
                            'Lime' => Color::Lime,
                        ])
                        ->default(Color::Emerald),

                    ColorPicker::make('danger_color')
                        ->label('Màu nguy hiểm')
                        ->colors([
                            'Rose' => Color::Rose,
                            'Orange' => Color::Orange,
                        ])
                        ->default(Color::Rose),

                    ColorPicker::make('warning_color')
                        ->label('Màu cảnh báo')
                        ->colors([
                            'Amber' => Color::Amber,
                            'Orange' => Color::Orange,
                        ])
                        ->default(Color::Orange),
                ])
                ->columns(2),

            Forms\Components\Fieldset::make('Font và giao diện')
                ->schema([
                    Forms\Components\Select::make('font_family')
                        ->label('Font chữ')
                        ->options([
                            'Arial' => 'Arial',
                            'Verdana' => 'Verdana',
                            'Tahoma' => 'Tahoma',
                            'Poppins' => 'Poppins',
                            'Roboto' => 'Roboto',
                            'Open Sans' => 'Open Sans',
                            'Lato' => 'Lato',
                            'Montserrat' => 'Montserrat',
                            'Georgia' => 'Georgia',
                            'Times New Roman' => 'Times New Roman',
                            'Courier New' => 'Courier New',
                        ])
                        ->default('Poppins'),

                ])
                ->columns(1),

            Forms\Components\Fieldset::make('Cài đặt giao diện')
                ->schema([
                    Forms\Components\Select::make('pageLayout')
                        ->label('Kiểu giao diện')
                        ->options([
                            'boxed' => 'Giao diện dạng hộp',
                            'full_width' => 'Giao diện toàn màn hình',
                        ])
                        ->default('full_width')
                        ->helperText('Chọn kiểu hiển thị trang web'),

                    CodeEditor::make('custom_css')
                        ->label('Custom CSS')
                        ->extraAttributes(['class' => 'h-64 rounded-lg']),
                ])
                ->columns(1),
        ];
    }

    public function submit()
    {
        $data = $this->form->getState();

        $colorFields = [
            'primary_color',
            'secondary_color',
            'info_color',
            'success_color',
            'danger_color',
            'warning_color',
        ];

        foreach ($colorFields as $field) {
            if (isset($data[$field])) {
                $this->updateSetting($field, $this->convertColor($data[$field]));
            }
        }

        if ($data['logo']) {
            $this->handleFileUpload('logo', $data['logo']);
        }

        if ($data['favicon']) {
            $this->handleFileUpload('favicon', $data['favicon']);
        }

        $this->updateSetting('logo_size', $data['logo_size']); // Add this line
        $this->updateSetting('font_family', $data['font_family']);
        $this->updateSetting('layout', $data['pageLayout']);
        $this->updateSetting('custom_css', $data['custom_css']);

        $customCssPath = public_path('css/thuongna/filament-theme/custom.css');
        if (!empty($data['custom_css']) && file_exists($customCssPath)) {
            file_put_contents($customCssPath, $data['custom_css']);
        }

        Notification::make()
            ->title('Thành công')
            ->body('Cài đặt đã được lưu thành công.')
            ->success()
            ->send();
    }

    protected function handleFileUpload($settingKey, $file)
    {
        $oldFilePath = Config::get("theme.{$settingKey}");

        if ($file instanceof \Illuminate\Http\UploadedFile) {
            $extension = $file->getClientOriginalExtension();
            $originalName = $file->getClientOriginalName();
            $tempPath = $file->getRealPath();
        } else {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            $originalName = basename($file);
            $tempPath = $this->findFile($file);
        }

        $newFileName = ($settingKey === 'logo' || $settingKey === 'favicon')
            ? $settingKey . '.' . $extension
            : $originalName;

        $storageFolder = ($settingKey === 'logo') ? 'logos' : (($settingKey === 'favicon') ? 'favicons' : 'uploads');
        $newFilePath = $storageFolder . '/' . $newFileName;

        try {
            if (!$tempPath || !file_exists($tempPath)) {
                throw new \Exception("File không tồn tại: " . $file);
            }

            $content = file_get_contents($tempPath);
            if ($content === false) {
                throw new \Exception("Không thể đọc nội dung file: " . $tempPath);
            }

            $saved = Storage::disk('public')->put($newFilePath, $content);
            if (!$saved) {
                throw new \Exception("Không thể lưu file vào storage");
            }

            if ($oldFilePath && $oldFilePath !== $newFilePath) {
                Storage::disk('public')->delete($oldFilePath);
            }
        } catch (\Exception $e) {
            \Log::error("Không thể lưu file: " . $e->getMessage());
            return false;
        }

        $this->updateSetting($settingKey, $newFilePath);
        return true;
    }

    protected function findFile($path)
    {
        // Kiểm tra trong storage
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->path($path);
        }

        // Kiểm tra trong public
        $publicPath = public_path($path);
        if (file_exists($publicPath)) {
            return $publicPath;
        }

        // Kiểm tra như một đường dẫn tương đối
        $basePath = base_path();
        $fullPath = $basePath . '/' . $path;
        if (file_exists($fullPath)) {
            return $fullPath;
        }

        return null;
    }

    // protected function generateUniqueFileName($baseFileName, $extension, $folder)
    // {
    //     $fileName = $baseFileName . '.' . $extension;
    //     $fullPath = $folder . '/' . $fileName;

    //     if (!Storage::disk('public')->exists($fullPath)) {
    //         return $fileName;
    //     }

    //     $counter = 1;
    //     do {
    //         $fileName = $baseFileName . '(' . $counter . ').' . $extension;
    //         $fullPath = $folder . '/' . $fileName;
    //         $counter++;
    //     } while (Storage::disk('public')->exists($fullPath));

    //     return $fileName;
    // }


    protected function convertColor($color)
    {
        return "rgb(" . $color['value'] . ")";
    }

    public function updateSetting($key, $value)
    {
        $configFilePath = config_path('theme.php');

        $settings = file_exists($configFilePath) ? include($configFilePath) : [];

        $settings[$key] = $value;

        $this->writeConfigFile($settings, $configFilePath);
    }

    protected function writeConfigFile($settings, $configFilePath)
    {
        $configContent = "<?php\n\nreturn [\n";
        foreach ($settings as $key => $value) {
            $configContent .= is_string($value) ? "    '{$key}' => '{$value}',\n" : "    '{$key}' => '{$value}',\n";
        }
        $configContent .= "];";

        file_put_contents($configFilePath, $configContent);
    }
}
