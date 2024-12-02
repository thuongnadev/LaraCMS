<?php

namespace Modules\User\App\Filament\Resources\UserResource\Forms;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Thông tin cá nhân')
                    ->schema([
                        TextInput::make('name')
                            ->label('Tên')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('password')
                            ->label('Mật khẩu')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                            ->dehydrated(fn(?string $state): bool => filled($state))
                            ->required(fn(string $operation): bool => $operation === 'create'),
                        TextInput::make('password_confirmation')
                            ->label('Xác nhận mật khẩu')
                            ->password()
                            ->revealable()
                            ->minLength(8)
                            ->maxLength(255)
                            ->same('password'),
                    ])
                    ->columnSpan([
                        'sm' => 3,
                        'xl' => 3,
                        '2xl' => 2,
                    ]),
                Section::make('Phân quyền')
                    ->schema([
                        CheckboxList::make('roles')
                            ->label('Vai trò')
                            ->required()
                            ->relationship('roles', 'name')
                            ->searchable(),
                    ])
                    ->columnSpan([
                        'sm' => 3,
                        'xl' => 3,
                        '2xl' => 1,
                    ])
            ])
            ->columns(3);
    }
}
