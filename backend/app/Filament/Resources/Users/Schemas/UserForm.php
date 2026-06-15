<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Họ và tên')
                    ->placeholder('Nhập họ và tên')
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->placeholder('Nhập email')
                    ->email()
                    ->required(),
                Select::make('role')
                    ->label('Vai trò')
                    ->options([
                        '1' => 'Quản trị viên',
                        '2' => 'Người mua',
                        '3' => 'Người bán',
                    ])
                    ->default('3')
                    ->required(),
                Select::make('status')
                    ->label('Trạng thái')
                    ->options([
                        '0' => 'Bị khóa',
                        '1' => 'Đang hoạt động',
                    ])
                    ->default('1')
                    ->required(),
            ]);
    }
}
