<?php

namespace App\Filament\Resources\SystemSettings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SystemSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                    ->label('Nhóm cấu hình')
                    ->required(),

                TextInput::make('key')
                    ->label('Mã cấu hình (Key)')
                    ->required()
                    ->placeholder('Nhập key (ví dụ: commission_rate, rental_fee, fee_2_percent)'),

                Textarea::make('value')
                    ->label('Giá trị (Value)')
                    ->placeholder('Nhập giá trị của cấu hình')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
