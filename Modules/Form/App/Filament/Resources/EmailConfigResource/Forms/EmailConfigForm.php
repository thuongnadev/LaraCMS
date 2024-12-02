<?php

namespace Modules\Form\App\Filament\Resources\EmailConfigResource\Forms;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Modules\Form\Entities\EmailConfig;

class EmailConfigForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('mailer_type')
                                    ->label('Loại Mailer')
                                    ->options([
                                        'smtp' => 'SMTP',
                                        // 'mailgun' => 'Mailgun',
                                        // 'ses' => 'SES',
                                        // 'sendgrid' => 'SendGrid',
                                    ])
                                    ->required()
                                    ->columnSpan(1)
                                    ->reactive()
                                    ->afterStateUpdated(fn (callable $set) => $set('configurations', [])),

                                KeyValue::make('configurations')
                                    ->label('Cấu hình dựa vào loại Mailer mà bạn muốn gửi')
                                    ->keyLabel('Trường')
                                    ->valueLabel('Giá trị')
                                    ->required()
                                    ->helperText(function (callable $get) {
                                        $mailerTypes = [
                                            'smtp' => 'Các trường cần thiết: MAIL_HOST, MAIL_PORT, MAIL_USERNAME, MAIL_PASSWORD, MAIL_ENCRYPTION, MAIL_FROM_ADDRESS, MAIL_FROM_NAME',
                                            'mailgun' => 'Các trường cần thiết: MAILGUN_DOMAIN, MAILGUN_SECRET, MAILGUN_ENDPOINT, MAIL_FROM_ADDRESS, MAIL_FROM_NAME',
                                            'ses' => 'Các trường cần thiết: AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, AWS_DEFAULT_REGION, MAIL_FROM_ADDRESS, MAIL_FROM_NAME',
                                            'sendgrid' => 'Các trường cần thiết: SENDGRID_API_KEY, MAIL_FROM_ADDRESS, MAIL_FROM_NAME',
                                        ];
                                        $mailerType = $get('mailer_type');
                                        return $mailerTypes[$mailerType] ?? 'Vui lòng chọn loại Mailer trước';
                                    })
                                    ->addButtonLabel('Thêm ô trường và giá trị')
                                    ->columnSpan(1),

                                Toggle::make('is_default')
                                    ->label('Sử dụng làm mailer mặc định')
                                    ->inline(false)
                                    ->columnSpan(1)
                                    ->afterStateUpdated(function ($state, $set, $record) {
                                        if ($state) {
                                            EmailConfig::where('id', '!=', $record->id)
                                                ->update(['is_default' => false]);
                                        }
                                    }),
                            ]),
                    ]),
            ]);
    }
}
